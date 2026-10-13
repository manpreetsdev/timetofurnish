<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\OrderMessageFile;
use App\Services\OrderMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderMessageController extends Controller
{
    public function __construct(protected OrderMessageService $messages)
    {
    }

    protected function isBackOffice(): bool
    {
        return auth()->check() && in_array(auth()->user()->user_type, ['admin', 'staff'], true);
    }

    protected function customerOrder($orderId): Order
    {
        $order = Order::findOrFail($orderId);
        abort_unless((int) $order->user_id === (int) auth()->id(), 403);

        // Customers can only reply: the conversation must have been started by us.
        if (!OrderMessage::where('order_id', $order->id)->where('sender_type', 'admin')->exists()) {
            abort(redirect()->route('order-messages.index')->with('om_error', translate('You can reply here once we have sent you a message about this order.')));
        }

        return $order;
    }

    protected function validateMessage(Request $request): array
    {
        $data = $request->validate([
            'body' => 'nullable|string|max:5000',
            'tokens' => 'nullable|array|max:' . OrderMessageService::MAX_FILES_PER_MESSAGE,
            'tokens.*' => 'string|max:40',
        ]);

        if (trim((string) ($data['body'] ?? '')) === '' && empty($data['tokens'])) {
            abort(back()->withErrors(['body' => translate('Please write a message or add a file.')])->withInput());
        }

        return $data;
    }

    /* ---------------- Uploads & files (admin + customer) ---------------- */

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'upload_id' => 'required|string|max:40',
            'index' => 'required|integer|min:0',
            'total' => 'required|integer|min:1|max:400',
            'name' => 'required|string|max:255',
            'chunk' => 'required|file|max:' . (int) ceil(OrderMessageService::CHUNK_BYTES / 1024 * 1.5),
        ]);

        $done = $this->messages->receiveChunk(
            (int) auth()->id(),
            $request->upload_id,
            (int) $request->index,
            (int) $request->total,
            $request->name,
            $request->file('chunk')->getRealPath()
        );

        return response()->json(['done' => $done, 'token' => $done ? $request->upload_id : null]);
    }

    public function file($id)
    {
        $file = OrderMessageFile::findOrFail($id);
        $order = Order::findOrFail($file->order_id);

        abort_unless($this->isBackOffice() || (int) $order->user_id === (int) auth()->id(), 403);

        $path = storage_path('app/' . $file->path);
        abort_unless(is_file($path), 404);

        $inline = in_array($file->kind, ['image', 'video'], true) || $file->mime === 'application/pdf';

        return response()->file($path, [
            'Content-Type' => $file->mime ?: 'application/octet-stream',
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . addslashes($file->original_name) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /* ---------------- Customer ---------------- */

    // Unread messages for the floating bubble, one entry per order (newest first)
    public function customerUnread()
    {
        $unread = OrderMessage::with('order')
            ->where('customer_id', auth()->id())
            ->where('sender_type', 'admin')
            ->whereNull('read_by_customer_at')
            ->latest('id')
            ->get();

        $items = $unread->groupBy('order_id')->map(function ($messages) {
            $latest = $messages->first();
            if (!$latest->order) {
                return null;
            }

            return [
                'code' => $latest->order->code,
                'body' => $latest->body ? \Illuminate\Support\Str::limit($latest->body, 110) : translate('We have sent you some files.'),
                'time' => $latest->created_at->format('j M, g:i A'),
                'is_alert' => $messages->contains('is_alert', true),
                'url' => route('order-messages.show', $latest->order_id),
            ];
        })->filter()->values();

        return response()->json([
            'count' => $unread->count(),
            'alerts' => $unread->where('is_alert', true)->count(),
            'items' => $items,
        ]);
    }

    public function customerIndex()
    {
        $userId = auth()->id();

        $threads = OrderMessage::select(
            'order_id',
            DB::raw('MAX(created_at) as last_at'),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN sender_type = 'admin' AND read_by_customer_at IS NULL THEN 1 ELSE 0 END) as unread"),
            DB::raw("SUM(CASE WHEN sender_type = 'admin' AND read_by_customer_at IS NULL AND is_alert = 1 THEN 1 ELSE 0 END) as unread_alerts")
        )
            ->where('customer_id', $userId)
            ->whereIn('order_id', OrderMessage::where('customer_id', $userId)->where('sender_type', 'admin')->select('order_id'))
            ->groupBy('order_id')
            ->orderByDesc('last_at')
            ->get();

        $orders = Order::whereIn('id', $threads->pluck('order_id'))->get()->keyBy('id');
        $lastMessages = OrderMessage::whereIn('id', OrderMessage::selectRaw('MAX(id)')->where('customer_id', $userId)->groupBy('order_id'))
            ->get()->keyBy('order_id');

        return view('frontend.user.order_messages.index', compact('threads', 'orders', 'lastMessages'));
    }

    public function customerShow($orderId)
    {
        $order = $this->customerOrder($orderId);

        $messages = OrderMessage::with('files', 'sender')->where('order_id', $order->id)->orderBy('id')->get();

        // Opening the conversation marks our messages as read
        OrderMessage::where('order_id', $order->id)->where('sender_type', 'admin')->whereNull('read_by_customer_at')
            ->update(['read_by_customer_at' => now()]);

        return view('frontend.user.order_messages.show', compact('order', 'messages'));
    }

    public function customerStore(Request $request, $orderId)
    {
        $order = $this->customerOrder($orderId);
        $data = $this->validateMessage($request);

        $this->messages->post($order, 'customer', $data['body'] ?? null, $data['tokens'] ?? []);

        flash(translate('Your message has been sent'))->success();
        return redirect()->route('order-messages.show', $order->id);
    }

    /* ---------------- Admin ---------------- */

    public function adminIndex(Request $request)
    {
        $query = OrderMessage::select(
            'order_id',
            'customer_id',
            DB::raw('MAX(created_at) as last_at'),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN sender_type = 'customer' AND read_by_admin_at IS NULL THEN 1 ELSE 0 END) as unread"),
            DB::raw("SUM(CASE WHEN sender_type = 'admin' AND read_by_customer_at IS NULL THEN 1 ELSE 0 END) as waiting")
        )->groupBy('order_id', 'customer_id');

        if ($request->filter === 'unread') {
            $query->havingRaw("SUM(CASE WHEN sender_type = 'customer' AND read_by_admin_at IS NULL THEN 1 ELSE 0 END) > 0");
        } elseif ($request->filter === 'waiting') {
            // the customer has not opened our message yet
            $query->havingRaw("SUM(CASE WHEN sender_type = 'admin' AND read_by_customer_at IS NULL THEN 1 ELSE 0 END) > 0");
        } elseif ($request->filter === 'files') {
            $query->whereIn('order_id', OrderMessageFile::select('order_id'));
        }

        if ($request->filled('date')) {
            $parts = array_map('trim', explode(' to ', $request->date));
            try {
                $from = \Carbon\Carbon::createFromFormat('d-m-Y', $parts[0])->startOfDay();
                $to = \Carbon\Carbon::createFromFormat('d-m-Y', $parts[1] ?? $parts[0])->endOfDay();
                $query->whereIn('order_id', OrderMessage::whereBetween('created_at', [$from, $to])->select('order_id'));
            } catch (\Throwable $e) {
                // ignore an unreadable date range
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereIn('order_id', Order::where('code', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                ->pluck('id'));
        }

        // Group the conversations by customer, most recently active customer first
        $allThreads = $query->orderByDesc('last_at')->get();
        $grouped = $allThreads->groupBy('customer_id');

        $perPage = 15;
        $page = max(1, (int) $request->query('page', 1));
        $groups = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->slice(($page - 1) * $perPage, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $orderIds = $groups->getCollection()->flatten(1)->pluck('order_id');
        $orders = Order::with('user', 'orderDetails.product')->whereIn('id', $orderIds)->get()->keyBy('id');
        $messages = OrderMessage::with('files')->whereIn('order_id', $orderIds)->orderBy('id')->get()->groupBy('order_id');

        return view('backend.order_messages.index', [
            'groups' => $groups,
            'orders' => $orders,
            'messages' => $messages,
            'customers' => $this->customersWithPaidOrders(),
            'stats' => [
                'conversations' => OrderMessage::distinct('order_id')->count('order_id'),
                'unread' => OrderMessage::where('sender_type', 'customer')->whereNull('read_by_admin_at')->distinct('order_id')->count('order_id'),
                'waiting' => OrderMessage::where('sender_type', 'admin')->whereNull('read_by_customer_at')->distinct('order_id')->count('order_id'),
                'files' => OrderMessageFile::count(),
            ],
        ]);
    }

    protected function orderSummary(Order $order): array
    {
        $address = json_decode((string) $order->shipping_address);

        return [
            'id' => $order->id,
            'code' => $order->code,
            'date' => date('j M, Y', (int) $order->date),
            'total' => single_price($order->grand_total),
            'payment' => ucfirst((string) $order->payment_status),
            'products' => $order->orderDetails->map(fn ($detail) => optional($detail->product)->name)->filter()->implode(', '),
            'customer_id' => $order->user_id,
            'customer' => optional($order->user)->name ?: ($address->name ?? 'Guest'),
            'email' => optional($order->user)->email,
        ];
    }

    // Paid orders of one customer, for the "New message" order dropdown
    public function adminLookup(Request $request)
    {
        $orders = Order::with('orderDetails.product', 'user')
            ->where('user_id', $request->customer_id)
            ->where('payment_status', 'paid')
            ->latest('id')
            ->limit(200)
            ->get();

        return response()->json(['orders' => $orders->map(fn ($order) => $this->orderSummary($order))->values()]);
    }

    // Every customer who has at least one paid order, for the "New message" customer dropdown
    protected function customersWithPaidOrders()
    {
        $counts = Order::where('payment_status', 'paid')->whereNotNull('user_id')
            ->selectRaw('user_id, COUNT(*) as c')->groupBy('user_id')->pluck('c', 'user_id');

        return \App\Models\User::whereIn('id', $counts->keys())->orderBy('name')->get(['id', 'name', 'email', 'phone'])
            ->map(fn ($user) => [
                'value' => $user->id,
                'title' => $user->name ?: translate('Customer') . ' #' . $user->id,
                'subtitle' => trim(($user->email ?: '') . ($user->phone ? ' · ' . $user->phone : '')),
                'meta' => $counts[$user->id] . ' ' . ($counts[$user->id] == 1 ? translate('paid order') : translate('paid orders')),
                'search' => $user->name . ' ' . $user->email . ' ' . $user->phone,
            ])->values();
    }

    public function adminShow($orderId)
    {
        $order = Order::with('user', 'orderDetails.product')->findOrFail($orderId);
        $messages = OrderMessage::with('files', 'sender')->where('order_id', $order->id)->orderBy('id')->get();

        OrderMessage::where('order_id', $order->id)->where('sender_type', 'customer')->whereNull('read_by_admin_at')
            ->update(['read_by_admin_at' => now()]);

        return view('backend.order_messages.show', [
            'order' => $order,
            'messages' => $messages,
            'files' => OrderMessageFile::with('message')->where('order_id', $order->id)->latest('id')->get(),
        ]);
    }

    public function adminStore(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order->user_id) {
            flash(translate('This order was placed by a guest without an account, so messages cannot be shown to them.'))->error();
            return back();
        }

        $data = $this->validateMessage($request);

        $message = $this->messages->post($order, 'admin', $data['body'] ?? null, $data['tokens'] ?? [], $request->boolean('is_alert'));

        $notified = $request->boolean('notify_email') && $this->messages->notifyCustomer($order, $message);

        flash(translate('Message sent to the customer') . ($notified ? ' ' . translate('(email notification sent)') : ''))->success();
        return redirect()->route('admin.order-messages.show', $order->id);
    }
}
