<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\OrderMessageFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderMessageService
{
    public const MAX_FILE_BYTES = 209715200; // 200 MB per file
    public const MAX_FILES_PER_MESSAGE = 10;
    public const CHUNK_BYTES = 1048576; // 1 MB pieces, below PHP's default upload limit

    // Any file type can be uploaded except these, which could run as code.
    // Files are stored outside the public folder under random names and anything
    // that is not a known photo / video / PDF is always sent as a download.
    public const BLOCKED = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar', 'phps', 'cgi', 'pl', 'py', 'rb', 'asp', 'aspx', 'jsp',
        'exe', 'msi', 'bat', 'cmd', 'com', 'scr', 'sh', 'bash', 'ps1', 'vbs', 'jar', 'app', 'dmg', 'apk', 'dll', 'so',
        'htaccess', 'htm', 'html', 'xhtml', 'js', 'mjs', 'svg',
    ];

    // extension => [kind, mime] for types the browser may show directly
    public const ALLOWED = [
        'jpg' => ['image', 'image/jpeg'],
        'jpeg' => ['image', 'image/jpeg'],
        'png' => ['image', 'image/png'],
        'webp' => ['image', 'image/webp'],
        'gif' => ['image', 'image/gif'],
        'heic' => ['file', 'image/heic'],
        'mp4' => ['video', 'video/mp4'],
        'm4v' => ['video', 'video/mp4'],
        'mov' => ['video', 'video/quicktime'],
        'webm' => ['video', 'video/webm'],
        'pdf' => ['file', 'application/pdf'],
    ];

    public static function extension(string $name): string
    {
        return strtolower(pathinfo($name, PATHINFO_EXTENSION));
    }

    public static function isAllowed(string $name): bool
    {
        $ext = self::extension($name);

        return $ext !== '' && preg_match('/^[a-z0-9]{1,10}$/', $ext) && !in_array($ext, self::BLOCKED, true);
    }

    // [kind, mime] for a file name
    public static function typeOf(string $name): array
    {
        return self::ALLOWED[self::extension($name)] ?? ['file', 'application/octet-stream'];
    }

    public function tempDir(int $userId): string
    {
        $dir = storage_path('app/order-message-tmp/' . $userId);
        File::ensureDirectoryExists($dir);

        return $dir;
    }

    // Receives one piece of a file. Returns true when the whole file has arrived.
    public function receiveChunk(int $userId, string $uploadId, int $index, int $total, string $name, string $chunkPath): bool
    {
        if (!preg_match('/^[a-zA-Z0-9]{16,40}$/', $uploadId)) {
            abort(422, 'Invalid upload');
        }
        if (!self::isAllowed($name)) {
            abort(422, 'This file type is not allowed');
        }

        $dir = $this->tempDir($userId);
        $part = $dir . '/' . $uploadId . '.part';

        if ($index === 0 && file_exists($part)) {
            unlink($part);
        }

        $size = (file_exists($part) ? filesize($part) : 0) + filesize($chunkPath);
        if ($size > self::MAX_FILE_BYTES) {
            @unlink($part);
            abort(422, 'File is too large');
        }

        file_put_contents($part, file_get_contents($chunkPath), FILE_APPEND);

        if ($index + 1 < $total) {
            return false;
        }

        rename($part, $dir . '/' . $uploadId . '.done');
        file_put_contents($dir . '/' . $uploadId . '.json', json_encode(['name' => mb_substr(basename($name), 0, 180)]));

        return true;
    }

    public function post(Order $order, string $senderType, ?string $body, array $tokens = [], bool $isAlert = false): OrderMessage
    {
        $userId = (int) auth()->id();

        $message = OrderMessage::create([
            'order_id' => $order->id,
            'customer_id' => $order->user_id,
            'sender_id' => $userId,
            'sender_type' => $senderType,
            'is_alert' => $senderType === 'admin' && $isAlert,
            'body' => $body !== null && trim($body) !== '' ? trim($body) : null,
            // the sender has obviously seen their own message
            'read_by_customer_at' => $senderType === 'customer' ? now() : null,
            'read_by_admin_at' => $senderType === 'admin' ? now() : null,
        ]);

        $tmp = $this->tempDir($userId);
        $target = storage_path('app/order-messages/' . $order->id);
        File::ensureDirectoryExists($target);

        foreach (array_slice(array_unique($tokens), 0, self::MAX_FILES_PER_MESSAGE) as $token) {
            if (!preg_match('/^[a-zA-Z0-9]{16,40}$/', (string) $token) || !file_exists("$tmp/$token.done")) {
                continue;
            }

            $meta = json_decode((string) @file_get_contents("$tmp/$token.json"), true) ?: [];
            $name = $meta['name'] ?? 'file';
            $ext = self::extension($name);
            if (!self::isAllowed($name)) {
                continue;
            }

            $stored = Str::random(32) . '.' . $ext;
            rename("$tmp/$token.done", "$target/$stored");
            @unlink("$tmp/$token.json");

            OrderMessageFile::create([
                'order_message_id' => $message->id,
                'order_id' => $order->id,
                'path' => 'order-messages/' . $order->id . '/' . $stored,
                'original_name' => $name,
                'mime' => self::typeOf($name)[1],
                'kind' => self::typeOf($name)[0],
                'size' => filesize("$target/$stored"),
            ]);
        }

        return $message->load('files');
    }

    // Tell the customer by email that there is a new message waiting in their account
    public function notifyCustomer(Order $order, OrderMessage $message): bool
    {
        $email = app(OrderEmailService::class)->customerEmail($order);
        if (!$email) {
            return false;
        }

        try {
            $address = json_decode((string) $order->shipping_address);
            $name = e($address->name ?? optional($order->user)->name ?? 'Customer');
            $link = route('order-messages.show', $order->id);
            $subject = ($message->is_alert ? 'Action needed' : 'New message') . ' for your order ' . $order->code;

            $body = "<p>Dear {$name},</p>"
                . '<p>We have sent you a message about your order <strong>' . e($order->code) . '</strong>.</p>'
                . ($message->body ? '<p style="padding:12px 14px;background:#faf8f5;border-left:3px solid #8a6f4d;">' . nl2br(e($message->body)) . '</p>' : '')
                . '<p>Please sign in to your account to read it and reply. You can also upload photos or videos there.</p>'
                . '<p><a href="' . e($link) . '" style="display:inline-block;padding:11px 22px;background:#685b4e;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:bold;">View message &amp; reply</a></p>';

            Mail::html(view('emails.service_email', ['body' => $body, 'subject' => $subject])->render(), function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            return true;
        } catch (\Throwable $e) {
            logger()->error('Order message notification failed: ' . $e->getMessage());

            return false;
        }
    }
}
