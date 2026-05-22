<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000000;
            background-color: #f8f9fa; /* Light background for screen view */
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.35;
        }
        
        /* Interactive Admin Print Bar */
        .admin-action-bar {
            max-width: 640px;
            margin: 0 auto 15px auto;
            text-align: right;
        }
        .btn-print {
            background-color: #f26522; /* Wooflix Orange */
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background 0.2s ease;
        }
        .btn-print:hover {
            background-color: #d44e11;
        }

        /* Outer structural bounding border box */
        .label-outer-border {
            border: 4px solid #000000;
            max-width: 600px;
            margin: 0 auto;
            padding: 24px 20px 10px 20px;
            background: #ffffff;
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        /* Generic grid layout fallbacks */
        .w-100 { width: 100%; }
        table { border-collapse: collapse; }
        td { vertical-align: top; padding: 0; }
        
        /* Brand Identity Header Formatting Block */
        .logo-container {
            text-align: right;
            padding-bottom: 25px;
        }
        .logo-img {
            height: 52px;
            width: auto;
            display: inline-block;
        }
        
        /* Primary document metadata descriptors */
        .meta-text-block {
            font-size: 16px;
            margin-bottom: 25px;
        }
        .meta-text-block div {
            margin-bottom: 4px;
        }
        
        /* Payment badge asset emulation */
        .payment-badge-cell {
            text-align: right;
            padding-top: 5px;
        }
        .payment-badge {
            background-color: #000000;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 45px;
            display: inline-block;
            text-align: center;
        }
        
        /* Component Section Title Headers */
        .section-header {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #000000;
            display: inline-block;
            padding-bottom: 2px;
            margin-bottom: 12px;
        }
        
        /* Main recipient delivery text coordinates */
        .shipping-address-container {
            font-size: 15px;
            margin-bottom: 35px;
            max-width: 95%;
        }
        .customer-name {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 15px;
            margin-bottom: 2px;
        }
        .customer-phone {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .address-lines {
            text-transform: uppercase;
            line-height: 1.4;
        }
        
        /* Consolidated structural inventory layout grids */
        .inventory-table {
            width: 100%;
            margin-bottom: 6px;
        }
        .inventory-table th {
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            padding: 5px 8px;
            border: 1px solid #000000;
            text-transform: capitalize;
        }
        .inventory-table td {
            padding: 6px 8px;
            font-size: 11px;
            border: 1px solid #bcbec0;
            text-align: center;
        }
        .inventory-table .text-left {
            text-align: left;
        }
        
        /* Declaration disclaimer notation */
        .declaration-text {
            font-size: 9px;
            color: #414143;
            text-align: right;
            margin-bottom: 45px;
        }
        
        /* Authorised business returns node context block */
        .return-address-container {
            font-size: 14px;
            margin-bottom: 25px;
        }
        .return-title {
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Footer channel utilities cross-strip */
        .footer-utility-strip {
            border-top: 1px solid #bcbec0;
            padding-top: 10px;
            margin-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #000000;
        }
        .utility-node {
            display: inline-block;
            margin: 0 14px;
        }
        .utility-icon {
            font-size: 13px;
            margin-right: 3px;
            vertical-align: middle;
        }

        /* CRITICAL PRINT INSTRUCTION: STRIP OUT ACTIONS ONCE HARDWARE FIRES */
        @media print {
            /* 1. Hide everything else on the page completely */
            body * {
                visibility: hidden;
            }

            /* 2. Make only your specific div and its contents visible */
            #shipping-label-card, #shipping-label-card * {
                visibility: visible;
            }

            /* 3. Position and absolute center the div on the printed sheet */
            #shipping-label-card {
                position: absolute;
                left: 50%;
                top: 35%;
                transform: translate(-50%, -50%);
                
                /* Reset margins/shadows for clean physical paper rendering */
                margin: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

<div class="admin-action-bar">
    <button onclick="window.print();" class="btn-print">
        Print Shipping Label
    </button>
</div>

<div id="shipping-label-card" class="label-outer-border">

    <table class="w-100">
        <tr>
            <td style="width: 50%;">
                <div class="meta-text-block">
                    <div><strong>Order ID:</strong> #{{ $order->order_number ?? 'WFX-26003' }}</div>
                    <div><strong>Shipping Date:</strong> {{ $order->created_at ? $order->created_at->format('m/d/Y') : date('m/d/Y') }}</div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="logo-container">
                    @if(!empty($settings['logo_desktop']))
                        <img src="{{ asset('storage/' . ($settings['logo_desktop'] ?? '')) }}" class="logo-img" alt="Wooflix Logo">
                    @else
                        <h1 style="margin:0; padding:0; font-size:28px; font-style:italic; line-height:1;"><span style="color:#000000; font-weight:900;">WOOF</span><span style="color:#f26522;">LIX</span></h1>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="w-100" style="margin-bottom: 25px;">
        <tr>
            <td style="width: 50%; vertical-align: bottom;">
                <div class="section-header">Shipping Details</div>
            </td>
            <td style="width: 50%;" class="payment-badge-cell">
                <div class="payment-badge">
                    {{ strtoupper($order->payment_status ?? 'Prepaid') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="shipping-address-container">
        <div class="customer-name">{{ $order->shipping_name ?? 'Customer Name' }}</div>
        <div class="customer-phone">+91 {{ $order->shipping_phone ?? '0000000000' }}</div>
        <div class="address-lines">
            {{ $order->shipping_address_line1 ?? 'Address Details' }},<br>
            {{ $order->shipping_city ?? 'City' }}, {{ $order->shipping_state ?? 'State' }} - {{ $order->shipping_postal_code ?? '000000' }}<br>
            {{ strtoupper($order->shipping_country ?? 'India') }}
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 6%;">#</th>
                <th style="width: 74%; text-align: left;">Description</th>
                <th style="width: 12%;">HSN</th>
                <th style="width: 8%;">Qty.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        {{ $item->product->name ?? 'Product Line Item Name' }}
                        @if(!empty($item->variant))
                            - {{ $item->variant->name }}
                        @endif
                    </td>
                    <td>{{ $item->product->hsn ?? '4201' }}</td>
                    <td>{{ str_pad($item->quantity ?? 1, 2, '0', STR_PAD_LEFT) }}</td>
                </tr>
            @empty
                <tr>
                    Not available.
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="declaration-text">
        <strong>Customer Self Declaration:</strong> The goods are intended for end user consumption. Not for resale
    </div>

    <div class="return-address-container">
        <div class="section-header" style="margin-bottom: 10px;">If Undelivered Return To</div>
        <div class="return-title">Wooflix</div>
        <div style="font-weight: bold; margin-bottom: 2px;">+91 98106 74769</div>
        <div>
            {{ $settings['office_address'] ?? 'D-15, Paryavaran Complex, IGNOU Road, New Delhi 110030' }}
        </div>
    </div>

    <div class="footer-utility-strip">
        <div class="utility-node">✉ {{ $settings['contact_email'] ?? '' }}</div>
        <div class="utility-node">📞 {{ $settings['contact_phone'] ?? '' }}</div>
        <div class="utility-node">🌐 www.wooflix.in</div>
    </div>

</div>

</body>
</html>