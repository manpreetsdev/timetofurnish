<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderEmailLog;
use App\Models\ServiceEmailTemplate;
use Illuminate\Support\Facades\Mail;

class OrderEmailService
{
    // Marks emails this service already logged, so the global listener doesn't log them twice
    public const LOGGED_HEADER = 'X-TTF-Email-Logged';

    // Placeholders staff can use in a template subject / body
    public static function placeholders(): array
    {
        return [
            '{customer_name}' => 'Customer name',
            '{customer_email}' => 'Customer email',
            '{customer_phone}' => 'Customer phone',
            '{order_code}' => 'Order code',
            '{order_date}' => 'Order date',
            '{order_total}' => 'Order total',
            '{service_name}' => 'Service name',
            '{products}' => 'List of products in the order',
            '{delivery_address}' => 'Delivery address',
            '{site_name}' => 'Website name',
        ];
    }

    // Services the customer bought with this order: [['id' => .., 'name' => .., 'product_name' => ..], ...]
    public function orderServices(Order $order): array
    {
        $info = json_decode((string) $order->additional_info, true);
        $services = is_array($info) ? ($info['services'] ?? []) : [];

        return collect($services)->filter(fn ($s) => is_array($s) && !empty($s['name']))->values()->all();
    }

    public function customerEmail(Order $order): ?string
    {
        $address = json_decode((string) $order->shipping_address);

        return optional($order->user)->email ?: ($address->email ?? null);
    }

    public function placeholderValues(Order $order, ?string $serviceName = null): array
    {
        $address = json_decode((string) $order->shipping_address);
        $order->loadMissing('orderDetails.product', 'user');

        $products = $order->orderDetails
            ->filter(fn ($detail) => $detail->product)
            ->map(fn ($detail) => e($detail->product->name) . ' &times; ' . $detail->quantity)
            ->implode('<br>');

        $addressLine = collect([
            $address->address ?? null,
            $address->city ?? null,
            $address->state ?? null,
            $address->postal_code ?? null,
            $address->country ?? null,
        ])->filter()->map(fn ($part) => e($part))->implode(', ');

        if ($serviceName === null) {
            $serviceName = collect($this->orderServices($order))->pluck('name')->unique()->implode(', ');
        }

        return [
            '{customer_name}' => e($address->name ?? optional($order->user)->name ?? 'Customer'),
            '{customer_email}' => e($this->customerEmail($order) ?? ''),
            '{customer_phone}' => e($address->phone ?? optional($order->user)->phone ?? ''),
            '{order_code}' => e($order->code),
            '{order_date}' => date('d M Y', (int) $order->date),
            '{order_total}' => single_price($order->grand_total),
            '{service_name}' => e($serviceName),
            '{products}' => $products,
            '{delivery_address}' => $addressLine,
            '{site_name}' => e(get_setting('website_name') ?: config('app.name')),
        ];
    }

    public function render(string $text, Order $order, ?string $serviceName = null, bool $plain = false): string
    {
        $values = $this->placeholderValues($order, $serviceName);

        if ($plain) {
            $values = array_map(fn ($value) => html_entity_decode(strip_tags(str_replace('<br>', ', ', $value)), ENT_QUOTES), $values);
        }

        return strtr($text, $values);
    }

    // "From" choices: the site default plus any extra addresses saved in settings
    public function fromOptions(): array
    {
        $options = [];
        $default = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS');

        if ($default) {
            $options[strtolower($default)] = ['email' => $default, 'name' => config('mail.from.name') ?: env('MAIL_FROM_NAME')];
        }

        foreach (preg_split('/\r\n|\r|\n/', (string) get_setting('order_email_from_addresses')) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // "Name <email>" or just "email"
            if (preg_match('/^(.*)<\s*([^>]+)\s*>$/', $line, $match)) {
                $name = trim($match[1], " \t\"'");
                $email = trim($match[2]);
            } else {
                $name = '';
                $email = $line;
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $options[strtolower($email)] = ['email' => $email, 'name' => $name ?: (config('mail.from.name') ?: env('MAIL_FROM_NAME'))];
            }
        }

        return array_values($options);
    }

    public function send(Order $order, array $data): OrderEmailLog
    {
        $log = new OrderEmailLog([
            'order_id' => $order->id,
            'checkout_service_id' => $data['checkout_service_id'] ?? null,
            'service_email_template_id' => $data['template_id'] ?? null,
            'type' => 'service_email',
            'to_email' => $data['to'],
            'cc' => $data['cc'] ?? null,
            'from_email' => $data['from_email'],
            'from_name' => $data['from_name'] ?? null,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_by' => auth()->id(),
        ]);

        try {
            $html = view('emails.service_email', ['body' => $data['body'], 'subject' => $data['subject']])->render();

            Mail::html($html, function ($message) use ($data) {
                $message->to($data['to'])
                    ->from($data['from_email'], $data['from_name'] ?? null)
                    ->replyTo($data['from_email'], $data['from_name'] ?? null)
                    ->subject($data['subject']);

                foreach ($this->parseEmails($data['cc'] ?? '') as $cc) {
                    $message->cc($cc);
                }

                $message->getHeaders()->addTextHeader(self::LOGGED_HEADER, '1');
            });

            $log->status = 'sent';
        } catch (\Throwable $e) {
            $log->status = 'failed';
            $log->error = mb_substr($e->getMessage(), 0, 1000);
        }

        $log->save();

        return $log;
    }

    public function parseEmails(?string $list): array
    {
        return collect(preg_split('/[\s,;]+/', (string) $list))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    // The same service often exists several times (one per category group), so
    // templates are matched to services by name, not only by id.
    public static function normaliseName(?string $name): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', (string) $name)));
    }

    public function templateMatchesOrder(ServiceEmailTemplate $template, Order $order): bool
    {
        if (!$template->checkout_service_id) {
            return false;
        }

        $services = collect($this->orderServices($order));

        return $services->pluck('id')->contains($template->checkout_service_id)
            || $services->pluck('name')->map(fn ($name) => self::normaliseName($name))
                ->contains(self::normaliseName(optional($template->service)->name));
    }

    // Templates to offer for an order: its services' templates first, then general ones
    public function templatesForOrder(Order $order)
    {
        return ServiceEmailTemplate::with('service')
            ->where('status', 1)
            ->get()
            ->sortBy(function ($template) use ($order) {
                if ($this->templateMatchesOrder($template, $order)) {
                    return 0;
                }
                return $template->checkout_service_id ? 2 : 1;
            })
            ->values();
    }

    // Example content shown in the "new template" form so staff can see how a template is written
    public static function exampleTemplate(): array
    {
        return [
            'name' => 'Room photos request',
            'subject' => 'Order {order_code} – a few details we need for your {service_name}',
            'body' => '<p>Dear {customer_name},</p>'
                . '<p>Thank you for your order <strong>{order_code}</strong> placed on {order_date}.</p>'
                . '<p>You selected <strong>{service_name}</strong>. To help our team prepare, please reply to this email with:</p>'
                . '<ul><li>2–3 photos or a short video of the room</li><li>A photo of the entrance, hallway and any stairs</li><li>Anything else we should know before we arrive</li></ul>'
                . '<p><strong>Your order:</strong><br>{products}</p>'
                . '<p><strong>Delivery address:</strong><br>{delivery_address}</p>'
                . '<p>Kind regards,<br>{site_name}</p>',
        ];
    }

    // Ready-made template for a service, built from the words in its name
    public static function starterTemplateFor(string $serviceName): array
    {
        $name = self::normaliseName($serviceName);
        $assembly = str_contains($name, 'assembl');
        $takeAway = str_contains($name, 'take away') || str_contains($name, 'takeaway');
        $unpack = str_contains($name, 'unpack');
        $roomOfChoice = str_contains($name, 'room of choice') || str_contains($name, 'room');
        $hallway = str_contains($name, 'hallway');

        $asks = [];
        $notes = [];

        if ($hallway) {
            $asks[] = 'A photo of your front door / main entrance and the hallway where the goods will be left';
            $asks[] = 'Parking and access details (permit parking, narrow road, gates, flat number or floor)';
            $notes[] = 'This service is delivery to your hallway or ground-floor entrance only. Our team will not carry items to other rooms or upstairs.';
        }
        if ($roomOfChoice && !$hallway) {
            $asks[] = '2–3 photos or a short video of the room where you would like the furniture placed';
            $asks[] = 'Photos of the route from the entrance to that room (hallway, doorways, stairs or lift) and which floor it is on';
            $asks[] = 'Parking and access details (permit parking, narrow road, gates, flat number)';
            $notes[] = 'Please make sure the route and the room are clear before our team arrives.';
        }
        if ($assembly) {
            $asks[] = 'The exact spot in the room where the furniture should be assembled, with enough clear floor space around it';
            $asks[] = 'Whether any existing furniture needs to be moved first (our team cannot dismantle or remove old furniture unless agreed in advance)';
            $notes[] = 'Assembly usually takes 30–90 minutes per item. An adult must be at home for the whole visit.';
        }
        if ($unpack) {
            $notes[] = 'Our team will unpack your items in the room so you can check them before we leave.';
        }
        if ($takeAway) {
            $notes[] = 'All cardboard and packaging will be taken away by our team after the delivery.';
        }
        if (!$assembly && !$takeAway && !$unpack && !$hallway && !$roomOfChoice) {
            $asks[] = '2–3 photos or a short video of the room or area where the service is needed';
            $asks[] = 'Access and parking details for your address';
        }

        $asks[] = 'The best phone number and time of day to reach you on the delivery day';

        $body = '<p>Dear {customer_name},</p>'
            . '<p>Thank you for your order <strong>{order_code}</strong> placed on {order_date}.</p>'
            . '<p>You have chosen our <strong>{service_name}</strong> service. So that everything goes smoothly, please reply to this email with the following:</p>'
            . '<ul><li>' . implode('</li><li>', $asks) . '</li></ul>';

        if ($notes) {
            $body .= '<p><strong>Good to know</strong></p><ul><li>' . implode('</li><li>', $notes) . '</li></ul>';
        }

        $body .= '<p><strong>Your order:</strong><br>{products}</p>'
            . '<p><strong>Delivery address:</strong><br>{delivery_address}</p>'
            . '<p>You can attach photos or a video directly to your reply. If anything is unclear, just reply to this email and we will help.</p>'
            . '<p>Kind regards,<br>{site_name}</p>';

        return [
            'name' => trim($serviceName) . ' – details request',
            'subject' => 'Order {order_code} – details needed for your ' . trim($serviceName) . ' service',
            'body' => $body,
        ];
    }
}
