<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Commercial Invoice #{{ $order['order_no'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
        }
        .header-table, .meta-table, .items-table, .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .brand-sub {
            font-size: 10px;
            text-transform: uppercase;
            color: #777;
            letter-spacing: 1px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            color: #111;
        }
        .meta-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .total-box {
            float: right;
            width: 280px;
            margin-top: 10px;
        }
        .total-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-box td {
            padding: 5px 0;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #4f46e5;
            border-top: 2px solid #e5e7eb;
            padding-top: 8px !important;
        }
        .footer-note {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand-title">APEX ERP</div>
                    <div class="brand-sub">Enterprise Resource Planning</div>
                    <div style="margin-top: 5px; color: #555; font-size: 11px;">
                        100 Enterprise Way, Suite 400<br>
                        Chicago, IL 60601, USA<br>
                        contact@apexerp.com &bull; +1 (555) 902-1200
                    </div>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <div class="invoice-title">COMMERCIAL INVOICE</div>
                    <div style="margin-top: 5px; font-size: 12px; color: #555;">
                        <strong>Invoice No:</strong> {{ $order['order_no'] }}<br>
                        <strong>Date:</strong> {{ $order['date'] }}<br>
                        <strong>Payment Status:</strong> 
                        <span class="badge {{ $order['payment'] === 'Paid' ? 'badge-paid' : 'badge-pending' }}">
                            {{ $order['payment'] }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Billed To -->
        <table class="meta-table" style="background: #f9fafb; padding: 10px; border-radius: 6px;">
            <tr>
                <td style="width: 50%;">
                    <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-bottom: 4px;">Billed To:</div>
                    <div style="font-weight: bold; font-size: 14px; color: #111;">{{ $order['customer'] }}</div>
                    <div style="font-size: 11px; color: #555;">Customer Code: {{ $order['customer_code'] }}</div>
                    <div style="font-size: 11px; color: #555;">Corporate Account &bull; Net 30 Terms</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-bottom: 4px;">Fulfillment Details:</div>
                    <div style="font-size: 11px; color: #555;"><strong>Assigned Sales Rep:</strong> {{ $order['sales_rep'] }}</div>
                    <div style="font-size: 11px; color: #555;"><strong>Fulfillment Status:</strong> {{ $order['status'] }}</div>
                    <div style="font-size: 11px; color: #555;"><strong>Dispatch Hub:</strong> Main Warehouse (WH-A)</div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th style="width: 45%;">Item Description</th>
                    <th class="text-center" style="width: 15%;">Qty</th>
                    <th class="text-right" style="width: 15%;">Unit Price</th>
                    <th class="text-right" style="width: 15%;">Line Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Industrial Hydraulic Flange Assembly 4"</strong><br>
                        <span style="font-size: 10px; color: #777;">SKU: PRD-HD-101 &bull; Heavy Duty Series</span>
                    </td>
                    <td class="text-center">10 pcs</td>
                    <td class="text-right">$650.00</td>
                    <td class="text-right">$6,500.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <strong>High-Precision Stepper Motor Assembly</strong><br>
                        <span style="font-size: 10px; color: #777;">SKU: PRD-EL-204 &bull; Electronics & Motors</span>
                    </td>
                    <td class="text-center">15 pcs</td>
                    <td class="text-right">$185.00</td>
                    <td class="text-right">$2,775.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <strong>Pneumatic Actuator Valve 2"</strong><br>
                        <span style="font-size: 10px; color: #777;">SKU: PRD-PN-408 &bull; Pneumatics</span>
                    </td>
                    <td class="text-center">8 pcs</td>
                    <td class="text-right">$130.00</td>
                    <td class="text-right">$1,040.00</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Calculation -->
        <div class="total-box">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">$10,315.00</td>
                </tr>
                <tr>
                    <td>Tax / VAT (9%):</td>
                    <td class="text-right">{{ $order['tax'] }}</td>
                </tr>
                <tr>
                    <td>Shipping & Freight:</td>
                    <td class="text-right">$250.00</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total:</td>
                    <td class="text-right">{{ $order['amount'] }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <!-- Payment Instructions & Banking -->
        <div style="margin-top: 30px; padding: 12px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 11px;">
            <strong>Payment Remittance Details:</strong><br>
            Bank Name: First Enterprise Commercial Bank &bull; Routing (ABA): 071000288<br>
            Account Name: Apex ERP Systems Inc. &bull; Account No: 998822104921<br>
            Please reference invoice number <strong>{{ $order['order_no'] }}</strong> on your wire transfer.
        </div>

        <!-- Footer -->
        <div class="footer-note">
            Generated automatically via DomPDF on {{ date('Y-m-d H:i:s') }} &bull; APEX ERP Enterprise Suite &bull; Official Digital Tax Document
        </div>
    </div>
</body>
</html>
