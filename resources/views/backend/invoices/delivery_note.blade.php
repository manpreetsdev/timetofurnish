<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Note {{ $order->code }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #222222;
            font-family: Roboto, Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
        }

        td,
        th {
            vertical-align: top;
            text-align: left;
        }

        .sheet {
            width: 100%;
            background: #ffffff;
        }

        .wrap {
            padding-left: 36px;
            padding-right: 36px;
        }

        /* Header – same treatment as the invoice */
        .header {
            background: #fbfaf8;
            border-bottom: 1px solid #e3ddd5;
        }

        .doc-title {
            color: #8a6f4d;
            font-size: 22px;
            font-weight: bold;
            line-height: 1.15;
            white-space: nowrap;
        }

        .badge {
            display: inline-block;
            background: #8a6f4d;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 5px 9px;
            text-transform: uppercase;
        }

        .label {
            color: #8a6f4d;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .muted {
            color: #666666;
        }

        /* Boxed grids */
        .grid {
            width: 100%;
            border: 1px solid #d7c8b8;
        }

        .grid th {
            background: #f5efe7;
            border: 1px solid #d7c8b8;
            color: #8a6f4d;
            font-size: 9.5px;
            font-weight: bold;
            letter-spacing: 0.4px;
            padding: 8px 10px;
            text-transform: uppercase;
        }

        .grid td {
            border: 1px solid #e1d6c9;
            font-size: 11px;
            padding: 8px 10px;
        }

        .grid td.addr {
            line-height: 1.6;
        }

        .section-title {
            color: #222222;
            font-size: 16px;
            font-weight: bold;
            padding-bottom: 7px;
            border-bottom: 2px solid #222222;
        }

        /* Items – same look as the invoice details table, minus prices */
        .items th {
            color: #555555;
            font-size: 10px;
            font-weight: bold;
            padding: 9px 7px;
            border-bottom: 1px solid #dddddd;
            text-transform: uppercase;
        }

        .items td {
            font-size: 11.5px;
            padding: 12px 7px;
            border-bottom: 1px solid #ececec;
        }

        .addons {
            width: 100%;
            background: #faf8f5;
            border: 1px solid #e5ddd3;
            margin-top: 7px;
        }

        .addons td {
            border-bottom: 1px solid #e5ddd3;
            font-size: 10px;
            padding: 5px 8px;
        }

        .check-box {
            width: 16px;
            height: 16px;
            line-height: 16px;
            font-size: 1px;
            margin: 0 auto;
            border: 1.5px solid #8a6f4d;
            background: #ffffff;
        }

        .item-no {
            width: 22px;
            height: 22px;
            line-height: 22px;
            margin: 0 auto;
            border-radius: 11px;
            background: #8a6f4d;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            text-align: center;
        }

        .center {
            text-align: center !important;
        }

        .footer {
            border-top: 1px solid #e3ddd5;
            background: #fbfaf8;
        }

        .show-mobile {
            display: none;
        }

        /* ---------- On-screen (HTML) view only ---------- */
        .screen-only {
            display: none;
        }

        @media screen {
            body.is-screen {
                background: #f2f2f0;
                padding: 24px 12px;
            }

            body.is-screen .sheet {
                max-width: 820px;
                margin: 0 auto;
                border: 1px solid #ded8d0;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            }

            body.is-screen .screen-only {
                display: block;
            }

            .toolbar {
                max-width: 820px;
                margin: 0 auto 12px;
                text-align: right;
            }

            .toolbar a {
                display: inline-block;
                margin-left: 6px;
                padding: 8px 14px;
                border-radius: 6px;
                background: #8a6f4d;
                color: #ffffff;
                font-size: 12px;
                font-weight: bold;
                text-decoration: none;
            }

            .toolbar a.secondary {
                background: #ffffff;
                color: #8a6f4d;
                border: 1px solid #d7c8b8;
            }
        }

        @media screen and (max-width: 640px) {
            body.is-screen {
                padding: 0;
            }

            body.is-screen .sheet {
                border: 0;
                box-shadow: none;
            }

            .toolbar {
                padding: 10px 12px 0;
                text-align: left;
            }

            .wrap {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .stack td.wrap {
                display: block;
                width: 100% !important;
                text-align: left !important;
                box-sizing: border-box;
            }

            .stack td.head-right {
                padding-top: 0 !important;
            }

            /* Reference grid: 2 x 2 */
            .grid.refs tr {
                display: flex;
                flex-wrap: wrap;
            }

            .grid.refs th,
            .grid.refs td {
                display: block;
                width: 50% !important;
                box-sizing: border-box;
            }

            .grid.refs tr:first-child {
                display: none;
            }

            .grid.refs td::before {
                content: attr(data-label);
                display: block;
                color: #8a6f4d;
                font-size: 9px;
                font-weight: bold;
                text-transform: uppercase;
            }

            /* Two-column boxes stack */
            .grid.parties tr {
                display: block;
            }

            .grid.parties td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            /* Items become cards */
            .items thead {
                display: none;
            }

            .items tr,
            .items > tbody > tr > td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            .items > tbody > tr {
                border-bottom: 1px solid #ececec;
                padding: 10px 0;
            }

            .items > tbody > tr > td {
                border: 0 !important;
                padding: 3px 0 !important;
                text-align: left !important;
            }

            .items .addons tr {
                display: table-row;
            }

            .grid,
            .grid > tbody,
            .items,
            .items > tbody {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            .grid.refs > tbody > tr {
                display: flex;
            }

            .addons {
                table-layout: auto;
            }

            .sig-line {
                overflow: hidden;
                white-space: nowrap;
            }

            body.is-screen .sheet {
                overflow: hidden;
            }

            .item-no-cell {
                display: none !important;
            }

            .check-box-wrap {
                display: inline-table;
                vertical-align: middle;
                margin: 0 !important;
            }

            .show-mobile {
                display: inline !important;
            }

            .show-mobile.block {
                display: block !important;
            }

            .hide-mobile {
                display: none !important;
            }
        }
    </style>
</head>

<body class="{{ !empty($screen) ? 'is-screen' : '' }}">
    @php
        $deliveryAddress = json_decode($order->shipping_address);

        $companyName = 'Time To Furnish';
        $companyEmail = 'sales@timetofurnish.com';
        $companyPhone = '+44 7751510365';
        $companyWebsite = 'www.timetofurnish.com';
        $companyVatNumber = 'GB 519 7742 56';

        $assetPath = function ($path) {
            $absolutePath = public_path(ltrim($path, '/'));
            if (!is_file($absolutePath)) {
                return '';
            }
            $mime = mime_content_type($absolutePath) ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absolutePath));
        };

        $addressLines = function ($address) {
            if (!$address) {
                return [];
            }
            return collect([
                $address->name ?? null,
                $address->address ?? null,
                $address->street ?? null,
                $address->city ?? null,
                $address->state ?? null,
                $address->postal_code ?? null,
                $address->country ?? null,
            ])->filter()->values()->all();
        };

        $services = [];
        if (!empty($order->additional_info)) {
            $additionalInfo = json_decode($order->additional_info, true);
            $services = is_array($additionalInfo) ? ($additionalInfo['services'] ?? []) : [];
        }

        $sellerLines = [];
        $sellerAddress = $order->shop && $order->shop->user
            ? $order->shop->user->addresses->sortByDesc('set_default')->first()
            : null;
        if ($sellerAddress) {
            $sellerLines = collect([
                $sellerAddress->address ?? null,
                collect([
                    $sellerAddress->street ?? null,
                    optional($sellerAddress->city)->name,
                    optional($sellerAddress->state)->name,
                    $sellerAddress->postal_code ?? null,
                ])->filter()->implode(', '),
                optional($sellerAddress->country)->name,
            ])->filter()->values()->all();
        }

        $orderDetails = $order->orderDetails->filter(fn ($detail) => $detail->product)->values();
        $productIds = $orderDetails->pluck('product_id');
        $orderLevelServices = collect($services)->reject(fn ($s) => $productIds->contains($s['product_id'] ?? null));
        $invoiceNumber = app(\App\Services\OrderInvoiceService::class)->invoiceNumber($order);
    @endphp

    @if (!empty($screen))
        <div class="toolbar screen-only">
            <a href="{{ request()->fullUrlWithQuery(['format' => null, 'download' => null]) }}" class="secondary">View PDF</a>
            <a href="{{ request()->fullUrlWithQuery(['format' => null, 'download' => 1]) }}">Download PDF</a>
        </div>
    @endif

    <div class="sheet">
        {{-- Header --}}
        <table width="100%" class="header stack" cellpadding="0" cellspacing="0">
            <tr>
                <td class="wrap" style="padding-top:24px;padding-bottom:20px;" width="58%" valign="middle">
                    <img src="{{ $assetPath('assets/img/TTF.jpg') }}" alt="{{ $companyName }}"
                        style="width:160px;height:auto;display:block;">
                    <div style="font-size:9.5px;color:#666666;margin-top:10px;">
                        {{ $companyWebsite }} &nbsp;|&nbsp; {{ $companyEmail }} &nbsp;|&nbsp; {{ $companyPhone }}
                    </div>
                </td>
                <td class="wrap head-right" style="padding-top:24px;padding-bottom:20px;text-align:right;" width="42%"
                    valign="middle" align="right">
                    <div class="doc-title">Delivery Note</div>
                    <div class="label" style="margin-top:3px;">Proof of delivery</div>
                    <div class="muted" style="font-size:10px;margin-top:2px;">Order {{ $order->code }}</div>
                </td>
            </tr>
        </table>

        <div class="wrap" style="padding-top:24px;">
            {{-- Reference numbers --}}
            <table class="grid refs" cellpadding="0" cellspacing="0">
                <tr>
                    <th width="25%">Invoice No</th>
                    <th width="25%">Order No</th>
                    <th width="25%">Order Date</th>
                    <th width="25%">Delivery Date</th>
                </tr>
                <tr>
                    <td data-label="Invoice No">{{ $invoiceNumber }}</td>
                    <td data-label="Order No">{{ $order->code }}</td>
                    <td data-label="Order Date">{{ date('d/m/Y', $order->date) }}</td>
                    <td data-label="Delivery Date">&nbsp;</td>
                </tr>
            </table>

            {{-- Invoice to / Ship to --}}
            <table class="grid parties" cellpadding="0" cellspacing="0" style="margin-top:18px;">
                <tr class="hide-mobile">
                    <th width="50%">Invoice To</th>
                    <th width="50%">Ship To</th>
                </tr>
                <tr>
                    <td class="addr" width="50%">
                        <div class="label show-mobile block">Invoice To</div>
                        @foreach ($addressLines($deliveryAddress) as $line)
                            <div @if ($loop->first) style="font-weight:bold;" @endif>{{ $line }}</div>
                        @endforeach
                        @if (!empty($deliveryAddress->phone))
                            <div>{{ $deliveryAddress->phone }}</div>
                        @endif
                        @if (!empty($deliveryAddress->email))
                            <div>{{ $deliveryAddress->email }}</div>
                        @endif
                    </td>
                    <td class="addr" width="50%">
                        <div class="label show-mobile block">Ship To</div>
                        @foreach ($addressLines($deliveryAddress) as $line)
                            <div @if ($loop->first) style="font-weight:bold;" @endif>{{ $line }}</div>
                        @endforeach
                    </td>
                </tr>
            </table>

            {{-- Items --}}
            <div class="section-title" style="margin-top:24px;">Delivery details</div>
            <table class="items" width="100%" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="7%" class="center">#</th>
                        <th width="63%">Item description</th>
                        <th width="13%" class="center">Qty del</th>
                        <th width="17%" class="center">Checked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orderDetails as $orderDetail)
                        @php
                            $addons = [];
                            if (!empty($orderDetail->addons)) {
                                $addons = json_decode($orderDetail->addons, true) ?: [];
                            } elseif (!empty($orderDetail->addon)) {
                                $addons = json_decode($orderDetail->addon, true) ?: [];
                            }
                            $itemServices = collect($services)->filter(fn ($s) => ($s['product_id'] ?? null) == $orderDetail->product_id);
                        @endphp
                        <tr>
                            <td class="center item-no-cell">
                                <table cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                    <tr><td style="width:22px;height:22px;padding:0;border:0;border-radius:11px;background:#8a6f4d;color:#ffffff;font-size:10.5px;font-weight:bold;text-align:center;vertical-align:middle;">{{ $loop->iteration }}</td></tr>
                                </table>
                            </td>
                            <td>
                                <div class="show-mobile block label" style="margin-bottom:3px;">Item {{ $loop->iteration }}</div>
                                <div style="font-weight:bold;">{{ $orderDetail->product->name }}</div>
                                @if ($orderDetail->variation)
                                    <div class="muted" style="font-size:10.5px;margin-top:2px;">
                                        Variant: {{ $orderDetail->variation }}</div>
                                @endif
                                @if ($itemServices->count() > 0)
                                    <div class="muted" style="font-size:10.5px;margin-top:2px;">
                                        Services: {{ $itemServices->map(fn ($s) => $s['name'] ?? 'Service')->implode(', ') }}
                                    </div>
                                @endif
                                @if (!empty($addons))
                                    <table class="addons" cellpadding="0" cellspacing="0">
                                        @foreach ($addons as $addon)
                                            <tr>
                                                <td width="42%" style="color:#6b5a45;">
                                                    {{ $addon['addon_name'] ?? ($addon['key'] ?? 'Add-on') }}</td>
                                                <td width="58%">{{ $addon['name'] ?? ($addon['value'] ?? '-') }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="center" style="font-weight:bold;">
                                <span class="show-mobile muted" style="font-weight:normal;">Qty del: </span>{{ $orderDetail->quantity }}
                            </td>
                            <td align="center" class="center">
                                <span class="show-mobile muted">Checked: </span>
                                <table class="check-box-wrap" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                    <tr><td style="width:15px;height:15px;padding:0;border:1.5px solid #8a6f4d;background:#ffffff;font-size:1px;line-height:1px;">&nbsp;</td></tr>
                                </table>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="center muted">No items</td>
                        </tr>
                    @endforelse

                    @foreach ($orderLevelServices as $service)
                        <tr>
                            <td class="center item-no-cell">
                                <table cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                    <tr><td style="width:22px;height:22px;padding:0;border:0;border-radius:11px;background:#d7c8b8;color:#5c4a36;font-size:10.5px;font-weight:bold;text-align:center;vertical-align:middle;">{{ $orderDetails->count() + $loop->iteration }}</td></tr>
                                </table>
                            </td>
                            <td>
                                Additional service
                                <div class="muted" style="font-size:10.5px;margin-top:2px;">
                                    {{ $service['name'] ?? 'Service' }}
                                    @if (!empty($service['type']))
                                        ({{ ucfirst($service['type']) }})
                                    @endif
                                </div>
                            </td>
                            <td class="center hide-mobile">&ndash;</td>
                            <td align="center" class="center">
                                <span class="show-mobile muted">Checked: </span>
                                <table class="check-box-wrap" cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;">
                                    <tr><td style="width:15px;height:15px;padding:0;border:1.5px solid #8a6f4d;background:#ffffff;font-size:1px;line-height:1px;">&nbsp;</td></tr>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Signatures --}}
            <table class="grid parties" cellpadding="0" cellspacing="0" style="margin-top:24px;">
                <tr class="hide-mobile">
                    <th width="50%">Delivery By</th>
                    <th width="50%">Customer</th>
                </tr>
                <tr>
                    @foreach (['Delivery By', 'Customer'] as $party)
                        <td width="50%" style="padding:12px 10px 14px;font-size:10px;color:#444444;line-height:2.6;">
                            <div class="label show-mobile block">{{ $party }}</div>
                            @foreach (['Signature', 'Print Name', 'Date'] as $field)
                                <div class="sig-line">{{ strtoupper($field) }}{{ str_repeat('.', $field === 'Print Name' ? 66 : ($field === 'Date' ? 80 : 70)) }}</div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            </table>

            <div class="muted" style="font-size:9px;margin-top:8px;">
                Please check all goods against this note before signing. Any shortages or damage must be recorded here
                at the time of delivery.
            </div>

            {{-- Company block --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:22px;margin-bottom:24px;">
                <tr>
                    <td style="font-size:10px;line-height:1.6;">
                        <div class="label" style="margin-bottom:3px;">{{ $companyName }}</div>
                        <div><b>Dispatched by:</b> {{ $order->shop->name ?? $companyName }}</div>
                        @foreach ($sellerLines as $line)
                            <div>{{ $line }}</div>
                        @endforeach
                        <div><b>Telephone:</b> {{ $companyPhone }} &nbsp; <b>E-mail:</b> {{ $companyEmail }}</div>
                        <div><b>VAT Reg No.</b> {{ $companyVatNumber }}</div>
                    </td>
                </tr>
            </table>
        </div>

        @if (!empty($screen))
            <div class="footer wrap" style="padding-top:12px;padding-bottom:14px;font-size:10px;color:#555555;text-align:center;">
                <b style="color:#222222;">Thank you for shopping with {{ $companyName }}.</b><br>
                {{ $companyWebsite }} &nbsp;|&nbsp; {{ $companyEmail }} &nbsp;|&nbsp; {{ $companyPhone }}
            </div>
        @endif
    </div>

    @if (empty($screen))
        <htmlpagefooter name="dnFooter">
            <table width="100%" class="footer" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:10px 0 10px 36px;font-size:9px;color:#555555;" width="80%">
                        <b style="color:#222222;">Thank you for shopping with {{ $companyName }}.</b>
                        &nbsp; {{ $companyWebsite }} &nbsp;|&nbsp; {{ $companyEmail }} &nbsp;|&nbsp; {{ $companyPhone }}
                    </td>
                    <td style="padding:10px 36px 10px 0;font-size:9px;color:#555555;text-align:right;" width="20%"
                        align="right">
                        Page {PAGENO} of {nbpg}
                    </td>
                </tr>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="dnFooter" value="on" />
    @endif
</body>

</html>
