<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Models\CheckoutService;
use App\Models\Order;
use App\Models\OrderEmailLog;
use App\Models\ServiceEmailTemplate;
use App\Services\OrderEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class OrderEmailController extends Controller
{
    public function __construct(protected OrderEmailService $emails)
    {
    }

    /* ---------------- Templates ---------------- */

    public function templates(Request $request)
    {
        $allTemplates = ServiceEmailTemplate::with('service')->orderBy('checkout_service_id')->orderBy('name')->get();
        $services = CheckoutService::orderBy('name')->get();

        // Optional filter: templates for one service (matched by name, so copies of a service share templates)
        $filterService = $request->filled('service') ? CheckoutService::find($request->service) : null;
        $templates = $filterService
            ? $allTemplates->filter(fn ($template) => $template->service
                && OrderEmailService::normaliseName($template->service->name) === OrderEmailService::normaliseName($filterService->name))->values()
            : $allTemplates;

        $coveredNames = $allTemplates->filter(fn ($t) => $t->service)->map(fn ($t) => OrderEmailService::normaliseName($t->service->name))->unique();
        $serviceNames = $services->map(fn ($s) => OrderEmailService::normaliseName($s->name))->unique();

        return view('backend.order_emails.templates', [
            'templates' => $templates,
            'filterService' => $filterService,
            'stats' => [
                'templates' => $allTemplates->count(),
                'active' => $allTemplates->where('status', 1)->count(),
                'services' => $serviceNames->count(),
                'uncovered' => $serviceNames->diff($coveredNames)->count(),
            ],
            'fromOptions' => $this->emails->fromOptions(),
            'fromAddressesRaw' => get_setting('order_email_from_addresses'),
        ]);
    }

    // Separate screen for writing a new template or editing one
    public function templateForm(Request $request, $id = null)
    {
        return view('backend.order_emails.template_form', [
            'editing' => $id ? ServiceEmailTemplate::findOrFail($id) : null,
            'filterService' => $request->filled('service') ? CheckoutService::find($request->service) : null,
            'services' => CheckoutService::orderBy('name')->get(),
            'example' => OrderEmailService::exampleTemplate(),
            'placeholders' => OrderEmailService::placeholders(),
            'fromOptions' => $this->emails->fromOptions(),
        ]);
    }

    // Email preview with sample order details: a saved template ($id) or unsaved form content
    public function previewTemplate(Request $request, $id = null)
    {
        if ($id) {
            $template = ServiceEmailTemplate::with('service')->findOrFail($id);
            $subject = $template->subject;
            $body = $template->body;
            $serviceName = optional($template->service)->name;
        } else {
            $subject = (string) $request->input('subject');
            $body = (string) $request->input('body');
            $serviceName = optional(CheckoutService::find($request->input('checkout_service_id')))->name;
        }

        $values = [
            '{customer_name}' => 'John Smith',
            '{customer_email}' => 'john.smith@example.com',
            '{customer_phone}' => '07123 456789',
            '{order_code}' => 'TTF-12345-678-1',
            '{order_date}' => now()->format('d M Y'),
            '{order_total}' => single_price(499),
            '{service_name}' => e($serviceName ?: 'Room Of Choice and Assembly'),
            '{products}' => 'Soraya Deluxe Bed &times; 1<br>Bedside Table &times; 2',
            '{delivery_address}' => '12 Example Street, London, W1 2AB, United Kingdom',
            '{site_name}' => e(get_setting('website_name') ?: config('app.name')),
        ];

        $subject = strtr($subject, array_map(fn ($value) => html_entity_decode(strip_tags(str_replace('<br>', ', ', $value)), ENT_QUOTES), $values));

        return response()->json([
            'subject' => $subject,
            'html' => view('emails.service_email', ['body' => strtr($body, $values), 'subject' => $subject])->render(),
        ]);
    }

    public function saveTemplate(Request $request, $id = null)
    {
        $data = $request->validate([
            'checkout_service_id' => 'nullable|exists:checkout_services,id',
            'name' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'body' => 'required|string',
            'from_email' => 'nullable|email|max:191',
            'from_name' => 'nullable|string|max:191',
        ]);
        $data['status'] = $request->boolean('status') ? 1 : 0;

        $template = $id ? ServiceEmailTemplate::findOrFail($id) : new ServiceEmailTemplate();
        $template->fill($data)->save();

        flash(translate('Email template saved'))->success();
        return redirect()->route('order-emails.templates');
    }

    public function destroyTemplate($id)
    {
        ServiceEmailTemplate::findOrFail($id)->delete();

        flash(translate('Email template deleted'))->success();
        return redirect()->route('order-emails.templates');
    }

    // Create a ready-made template for every service that doesn't have one yet
    public function generateTemplates()
    {
        $covered = ServiceEmailTemplate::with('service')->get()
            ->filter(fn ($template) => $template->service)
            ->map(fn ($template) => OrderEmailService::normaliseName($template->service->name))
            ->unique()
            ->all();

        $created = 0;
        foreach (CheckoutService::orderBy('id')->get() as $service) {
            $key = OrderEmailService::normaliseName($service->name);
            if ($key === '' || in_array($key, $covered, true)) {
                continue;
            }

            ServiceEmailTemplate::create(OrderEmailService::starterTemplateFor($service->name) + [
                'checkout_service_id' => $service->id,
                'status' => 1,
            ]);
            $covered[] = $key;
            $created++;
        }

        flash($created > 0
            ? $created . ' ' . translate('starter templates created. Review and edit them as needed.')
            : translate('Every service already has a template.'))->success();

        return redirect()->route('order-emails.templates');
    }

    // Download all templates as a JSON file (services are referenced by name so it works on another site)
    public function exportTemplates()
    {
        $templates = ServiceEmailTemplate::with('service')->orderBy('id')->get()->map(fn ($template) => [
            'name' => $template->name,
            'service_name' => optional($template->service)->name,
            'subject' => $template->subject,
            'body' => $template->body,
            'from_email' => $template->from_email,
            'from_name' => $template->from_name,
            'status' => (int) $template->status,
        ]);

        $payload = [
            'type' => 'ttf-service-email-templates',
            'version' => 1,
            'exported_at' => now()->toDateTimeString(),
            'from_addresses' => get_setting('order_email_from_addresses'),
            'templates' => $templates,
        ];

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, 'service-email-templates-' . now()->format('Y-m-d') . '.json', ['Content-Type' => 'application/json']);
    }

    public function importTemplates(Request $request)
    {
        $request->validate(['file' => 'required|file|max:5120']);

        $payload = json_decode((string) file_get_contents($request->file('file')->getRealPath()), true);

        if (!is_array($payload) || ($payload['type'] ?? null) !== 'ttf-service-email-templates' || !is_array($payload['templates'] ?? null)) {
            flash(translate('This is not a valid service email templates file.'))->error();
            return back();
        }

        $servicesByName = CheckoutService::orderBy('id')->get()
            ->groupBy(fn ($service) => OrderEmailService::normaliseName($service->name))
            ->map(fn ($group) => $group->first()->id);

        $existing = ServiceEmailTemplate::with('service')->get();
        $overwrite = $request->boolean('overwrite');
        $created = $updated = $skipped = $unmatched = 0;

        foreach ($payload['templates'] as $row) {
            if (!is_array($row) || empty($row['name']) || empty($row['subject']) || empty($row['body'])) {
                $skipped++;
                continue;
            }

            $serviceKey = OrderEmailService::normaliseName($row['service_name'] ?? '');
            $serviceId = $serviceKey !== '' ? ($servicesByName[$serviceKey] ?? null) : null;
            if ($serviceKey !== '' && !$serviceId) {
                $unmatched++; // service doesn't exist here: imported as a general template
            }

            $data = [
                'checkout_service_id' => $serviceId,
                'name' => mb_substr((string) $row['name'], 0, 191),
                'subject' => mb_substr((string) $row['subject'], 0, 191),
                'body' => (string) $row['body'],
                'from_email' => filter_var($row['from_email'] ?? null, FILTER_VALIDATE_EMAIL) ?: null,
                'from_name' => isset($row['from_name']) ? mb_substr((string) $row['from_name'], 0, 191) : null,
                'status' => !empty($row['status']) ? 1 : 0,
            ];

            $match = $existing->first(fn ($template) => OrderEmailService::normaliseName($template->name) === OrderEmailService::normaliseName($data['name'])
                && OrderEmailService::normaliseName(optional($template->service)->name) === $serviceKey);

            if ($match) {
                if ($overwrite) {
                    $match->update($data);
                    $updated++;
                } else {
                    $skipped++;
                }
                continue;
            }

            $existing->push(ServiceEmailTemplate::create($data)->load('service'));
            $created++;
        }

        if ($request->boolean('import_senders') && !empty($payload['from_addresses']) && !get_setting('order_email_from_addresses')) {
            BusinessSetting::updateOrCreate(['type' => 'order_email_from_addresses'], ['value' => (string) $payload['from_addresses']]);
            Artisan::call('cache:clear');
        }

        $message = "{$created} " . translate('created') . ", {$updated} " . translate('updated') . ", {$skipped} " . translate('skipped');
        if ($unmatched > 0) {
            $message .= '. ' . $unmatched . ' ' . translate('template(s) had a service that does not exist here and were imported as General.');
        }
        flash(translate('Import finished') . ': ' . $message)->success();

        return redirect()->route('order-emails.templates');
    }

    public function saveSenders(Request $request)
    {
        $request->validate(['order_email_from_addresses' => 'nullable|string|max:5000']);

        BusinessSetting::updateOrCreate(
            ['type' => 'order_email_from_addresses'],
            ['value' => $request->order_email_from_addresses]
        );
        Artisan::call('cache:clear');

        flash(translate('Sender addresses saved'))->success();
        return redirect()->route('order-emails.templates');
    }

    /* ---------------- Send from an order ---------------- */

    public function compose($orderId)
    {
        $order = Order::with('user', 'orderDetails.product')->findOrFail($orderId);

        $templates = $this->emails->templatesForOrder($order);
        $orderServices = $this->emails->orderServices($order);

        // Pre-rendered versions so choosing a template fills the form instantly
        $rendered = $templates->mapWithKeys(function ($template) use ($order) {
            $serviceName = optional($template->service)->name;

            return [$template->id => [
                'matches' => $this->emails->templateMatchesOrder($template, $order),
                'subject' => $this->emails->render($template->subject, $order, $serviceName, true),
                'body' => $this->emails->render($template->body, $order, $serviceName),
                'from_email' => $template->from_email,
                'service_id' => $template->checkout_service_id,
            ]];
        });

        return view('backend.order_emails.compose', [
            'order' => $order,
            'templates' => $templates,
            'rendered' => $rendered,
            'orderServices' => $orderServices,
            'orderServiceIds' => collect($orderServices)->pluck('id')->filter()->unique()->all(),
            'fromOptions' => $this->emails->fromOptions(),
            'customerEmail' => $this->emails->customerEmail($order),
            'logs' => OrderEmailLog::with('sender', 'service')->where('order_id', $order->id)->latest()->get(),
            'canSend' => $order->payment_status === 'paid',
        ]);
    }

    public function send(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->payment_status !== 'paid') {
            flash(translate('Service emails can only be sent for paid orders'))->error();
            return back();
        }

        $data = $request->validate([
            'template_id' => 'nullable|exists:service_email_templates,id',
            'from_email' => 'required|email',
            'to' => 'required|email',
            'cc' => 'nullable|string|max:500',
            'subject' => 'required|string|max:191',
            'body' => 'required|string',
        ]);

        // The sender must be one of the configured addresses
        $from = collect($this->emails->fromOptions())->first(fn ($option) => strcasecmp($option['email'], $data['from_email']) === 0);
        if (!$from) {
            flash(translate('Please choose a valid From address'))->error();
            return back()->withInput();
        }

        $template = !empty($data['template_id']) ? ServiceEmailTemplate::find($data['template_id']) : null;

        $log = $this->emails->send($order, [
            'template_id' => optional($template)->id,
            'checkout_service_id' => optional($template)->checkout_service_id,
            'to' => $data['to'],
            'cc' => implode(', ', $this->emails->parseEmails($data['cc'] ?? '')),
            'from_email' => $from['email'],
            'from_name' => ($template && $template->from_name && strcasecmp((string) $template->from_email, $from['email']) === 0)
                ? $template->from_name
                : $from['name'],
            'subject' => $data['subject'],
            'body' => $data['body'],
        ]);

        if ($log->status === 'sent') {
            flash(translate('Email sent to') . ' ' . $log->to_email)->success();
        } else {
            flash(translate('Email could not be sent') . ': ' . $log->error)->error();
        }

        return redirect()->route('order-emails.compose', $order->id);
    }

    /* ---------------- History ---------------- */

    public function history(Request $request)
    {
        $query = OrderEmailLog::with('order', 'sender', 'service')->latest();

        // Opened from an order: show everything sent to that order's customer
        $customer = null;
        if ($request->filled('order_id') && ($forOrder = Order::with('user')->find($request->order_id))) {
            $email = $this->emails->customerEmail($forOrder);
            $orderIds = $forOrder->user_id
                ? Order::where('user_id', $forOrder->user_id)->pluck('id')
                : collect([$forOrder->id]);

            $query->where(function ($builder) use ($orderIds, $email) {
                $builder->whereIn('order_id', $orderIds);
                if ($email) {
                    $builder->orWhere('to_email', $email);
                }
            });

            $address = json_decode((string) $forOrder->shipping_address);
            $customer = [
                'name' => $address->name ?? optional($forOrder->user)->name ?? translate('Guest'),
                'email' => $email,
                'order' => $forOrder,
                'orders' => $orderIds->count(),
            ];
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($builder) use ($search) {
                $builder->where('to_email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($order) => $order->where('code', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('backend.order_emails.history', [
            'logs' => $query->paginate(25)->appends($request->query()),
            'customer' => $customer,
            'totals' => [
                'all' => OrderEmailLog::count(),
                'service' => OrderEmailLog::where('type', 'service_email')->count(),
                'system' => OrderEmailLog::where('type', 'system')->count(),
                'failed' => OrderEmailLog::where('status', 'failed')->count(),
            ],
        ]);
    }

    public function showLog($id)
    {
        $log = OrderEmailLog::with('order', 'sender', 'service', 'template')->findOrFail($id);

        return view('backend.order_emails.show', compact('log'));
    }
}
