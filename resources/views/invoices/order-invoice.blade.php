<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* ACTIONS ACTION BAR ELEMENT PANEL BUTTONS */
        .invoice-actions-bar {
            max-width: 800px;
            margin: 20px auto 0 auto;
            text-align: right;
            padding: 0 20px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            background-color: #414143;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
            transition: background 0.2s ease;
        }
        .btn-action:hover {
            background-color: #2b2b2d;
        }
        .btn-pdf {
            background-color: #f26522; /* Matches Wooflix orange accent colors */
        }
        .btn-pdf:hover {
            background-color: #d44e11;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td {
            vertical-align: top;
        }
        
        /* Header styling */
        .header-table td {
            padding-bottom: 20px;
        }
        .company-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0 0 4px 0;
        }
        .invoice-badge {
            background-color: #414143;
            color: #fff;
            padding: 6px 16px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            display: inline-block;
            float: right;
            letter-spacing: 1px;
        }
        .brand-logo {
            float: right;
            margin-top: 10px;
            text-align: right;
        }
        .brand-logo h2 {
            margin: 0;
            font-size: 20px;
            color: #f26522;
            font-style: italic;
        }
        
        /* Address blocks */
        .address-table {
            margin-top: 10px;
        }
        .address-header {
            font-weight: bold;
            border-bottom: 1px solid #777;
            padding-bottom: 3px;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-size: 11px;
            color: #111;
            width: 90%;
        }
        
        /* Meta table */
        .meta-table td {
            padding: 2px 0;
            font-size: 11px;
        }
        .meta-label {
            color: #555;
            width: 40%;
        }
        .meta-value {
            font-weight: bold;
            text-align: right;
            width: 60%;
        }

        /* Items Grid styling */
        .items-table {
            width: 100%;
            margin-top: 15px;
        }
        .items-table th {
            background-color: #bcbec0;
            color: #111;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #fff;
            font-size: 10px;
        }
        .items-table td {
            padding: 6px 4px;
            border: 1px solid #e6e7e8;
            text-align: center;
            font-size: 10px;
        }
        .items-table .left-align {
            text-align: left;
        }
        .items-table .right-align {
            text-align: right;
        }
        .stripe-row {
            background-color: #f1f2f2;
        }
        .blank-row {
            height: 22px;
        }
        .blank-row td {
            border-bottom: 1px solid #e6e7e8;
        }
        .total-row td {
            background-color: #bcbec0;
            font-weight: bold;
            color: #000;
            border: 1px solid #fff;
        }

        /* Bottom sections */
        .amount-words {
            font-size: 11px;
            margin-bottom: 15px;
        }
        .bank-terms-table {
            border-top: 1px solid #bcbec0;
            border-bottom: 1px solid #bcbec0;
            margin-top: 15px;
        }
        .bank-terms-table td {
            padding: 10px;
            width: 50%;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
            font-size: 10px;
            color: #222;
        }
        .footer-note {
            text-align: right;
            margin-top: 15px;
            font-size: 11px;
        }
        .signature-space {
            margin-top: 45px;
            font-weight: bold;
        }

        /* Fixed utility contact strip */
        .contact-strip {
            text-align: center;
            font-size: 10px;
            color: #414143;
        }
        .contact-item {
            display: inline-block;
            margin: 0 15px;
        }

        /* CRITICAL: HIDE BAR ELEMENT UTILITIES DURING RENDER PRINT / PDF */
        @media print {
            body {
                background-color: #ffffff;
            }
            .invoice-actions-bar {
                display: none !important;
            }
            .invoice-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

    </style>
</head>
<body>

@if(!$isPdf)
    <div class="invoice-actions-bar">
        <button onclick="window.print();" class="btn-action">
             Print Invoice
        </button>
        <a href="{{ route('front.orderInvoicePdf', $order->id) }}" class="btn-action btn-pdf">
             Download PDF
        </a>
    </div>
@endif

<div class="invoice-container">

    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <h1 class="company-title">VKY TECHNOLOGIES</h1>
                <div>
                    {{ $settings['office_address'] ?? '' }}
                </div>
                <div style="margin-top: 4px;"><strong>GSTIN :</strong> 07DCXPS5858C2ZS</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="invoice-badge">Tax Invoice</div>
                <div style="clear: both;"></div>
                <div class="brand-logo">
                    <img
                        src="{{ asset('storage/' . ($settings['logo_desktop'] ?? '')) }}"
                        style="height: 35px; margin-bottom: 15px;"
                    >
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 35%;">
                <div class="address-header">Billed to</div>
                <strong>{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address_line1 }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}<br>
                @if($order->shipping_phone)
                    <div style="margin-top:4px;"><strong>Phone:</strong> {{ $order->shipping_phone }}</div>
                @endif
            </td>

            <td></td>

            <td style="width: 30%;">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Invoice Number</td>
                        <td class="meta-value">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Date</td>
                        <td class="meta-value">{{ $order->created_at ? $order->created_at->format('d-M-Y') : date('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Place of Supply</td>
                        <td class="meta-value">{{ $order->shipping_city }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Mode of payment</td>
                        <td class="meta-value">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? 'A/C TFR')) }}</td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr style="padding-top: 25px;">
            <td style="width: 35%;">
                <br>
                <br>
                <div class="address-header">Shipped to</div>
                <strong>{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address_line1 }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}
            </td>
        </tr>

    </table>
    

    <div style="text-align: right; font-size: 9px; color: #666; margin-bottom: 2px;">(in INR)</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 36%;">Description</th>
                <th style="width: 10%;">HSN</th>
                <th style="width: 6%;">Qty.</th>
                <th style="width: 10%;">Rate/PC</th>
                <th style="width: 12%;">Taxable Value</th>
                <th style="width: 6%;">GST</th>
                <th style="width: 8%;">SGST</th>
                <th style="width: 8%;">CGST</th>
                <th style="width: 8%;">IGST</th>
                <th style="width: 12%;">Inv. Value</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalTaxable = 0;
                $totalSgst = 0;
                $totalCgst = 0;
                $totalIgst = 0;
                $totalQty = 0;
                $isLocal = strtolower($order->shipping_state) == 'delhi' || strtolower($order->shipping_city) == 'delhi';
            @endphp

            @foreach($order->items as $index => $item)
                @php
                    // Dynamic calculation extraction based on totals
                    $qty = $item->quantity ?? 1;
                    $rate = $item->price ?? 0;
                    $taxable = $rate * $qty;
                    
                    // Standard 12% layout mock matching sticky roller calculations
                    $gstRate = 12; 
                    $taxAmount = $taxable * ($gstRate / 100);
                    
                    if($isLocal) {
                        $sgst = $taxAmount / 2;
                        $cgst = $taxAmount / 2;
                        $igst = 0;
                    } else {
                        $sgst = 0;
                        $cgst = 0;
                        $igst = $taxAmount;
                    }

                    $invValue = $taxable + $taxAmount;

                    // Accumulators
                    $totalTaxable += $taxable;
                    $totalSgst += $sgst;
                    $totalCgst += $cgst;
                    $totalIgst += $igst;
                    $totalQty += $qty;
                @endphp
                <tr class="{{ $index % 2 == 1 ? 'stripe-row' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td class="left-align">
                        {{ $item->product->name ?? 'Product Item' }}
                        @if(!empty($item->variant))
                            <br><small style="color:#666;">Variant: {{ $item->variant->name }}</small>
                        @endif
                    </td>
                    <td>{{ $item->product->hsn }}</td>
                    <td>{{ $qty }}</td>
                    <td class="right-align">{{ number_format($rate, 2, '.', ',') }}</td>
                    <td class="right-align">{{ number_format($taxable, 2, '.', ',') }}</td>
                    <td>{{ $gstRate }}%</td>
                    <td class="right-align">{{ $sgst > 0 ? number_format($sgst, 2, '.', ',') : '-' }}</td>
                    <td class="right-align">{{ $cgst > 0 ? number_format($cgst, 2, '.', ',') : '-' }}</td>
                    <td class="right-align">{{ $igst > 0 ? number_format($igst, 2, '.', ',') : '-' }}</td>
                    <td class="right-align">{{ number_format($invValue, 2, '.', ',') }}</td>
                </tr>
            @endforeach

            {{-- @for($i = count($order->items); $i < 6; $i++)
                <tr class="{{ $i % 2 == 1 ? 'stripe-row' : '' }} blank-row">
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
            @endfor --}}

            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Grand Total</td>
                <td>{{ $totalQty }}</td>
                <td></td>
                <td class="right-align">{{ number_format($totalTaxable, 0, '.', ',') }}</td>
                <td></td>
                <td class="right-align">{{ $totalSgst > 0 ? number_format($totalSgst, 0, '.', ',') : '-' }}</td>
                <td class="right-align">{{ $totalCgst > 0 ? number_format($totalCgst, 0, '.', ',') : '-' }}</td>
                <td class="right-align">{{ $totalIgst > 0 ? number_format($totalIgst, 0, '.', ',') : '-' }}</td>
                <td class="right-align">{{ number_format($order->total_amount, 0, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: right; font-size: 8px; color: #555; margin-top: -15px; margin-bottom: 15px;">(Round off to nearest zero)</div>

    <div class="amount-words">
        <span>Amount Chargeable (in words):</span><br>
        <strong>Rupees {{ NoToWords($order->total_amount) }} Only</strong>
    </div>

    <div style="font-size: 9px; color:#555; margin-bottom:10px;">
        We certify that <strong>WOOFLIX</strong> is our registered Trade Mark (TM No. 4925829)
    </div>

    <table class="bank-terms-table">
        <tr>
            <td style="border-right: 1px solid #bcbec0;">
                <div class="section-title">Bank Details</div>
                <table style="margin: 0; padding: 0; width: 100%; font-size: 11px;">
                    <tr><td style="width:30%; padding:2px 0; color:#555;">Bank Name</td><td style="width:70%; padding:2px 0;"><strong>AXIS BANK</strong></td></tr>
                    <tr><td style="padding:2px 0; color:#555;">Name</td><td style="padding:2px 0;">VKY TECHNOLOGIES</td></tr>
                    <tr><td style="padding:2px 0; color:#555;">A/c No.</td><td style="padding:2px 0;"><strong>924020028234947</strong></td></tr>
                    <tr><td style="padding:2px 0; color:#555;">IFS Code</td><td style="padding:2px 0;"><strong>UTIB0000160</strong></td></tr>
                    <tr><td style="padding:2px 0; color:#555;">Branch</td><td style="padding:2px 0;">Saket, New Delhi</td></tr>
                </table>
                
                <div style="margin-top: 10px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=65x65&data=https://www.wooflix.in" alt="Verification QR" style="width:65px; height:65px;">
                </div>
            </td>
            <td>
                <div class="section-title" style="text-decoration: underline; font-style: italic;">Terms & Conditions</div>
                <div style="font-size: 9px; color: #444; line-height: 1.5;">
                    <div>E. & O.E.</div>
                    <div>1. Goods once sold will not be taken back</div>
                    <div>2. Interest @24% p.a will be charged if the payment is not made within the stipulated time.</div>
                    <div>3. Subject to 'Delhi' Jurisdiction only</div>
                </div>

                <div class="footer-note">
                    <strong>For VKY TECHNOLOGIES</strong>
                    <div class="signature-space">Authorised Signatory</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="contact-strip">
        <div class="contact-item">✉ {{ $settings['contact_email'] ?? '' }}</div>
        <div class="contact-item">📞 {{ $settings['contact_phone'] ?? '' }}</div>
        <div class="contact-item">🌐 www.wooflix.in</div>
    </div>

</div>

</body>
</html>