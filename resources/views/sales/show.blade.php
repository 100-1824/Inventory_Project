@extends('layouts.app')

@section('title', 'Invoice ' . $sale->invoice_no . ' - Unique Hardware')

@section('content')
    <div class="container">
        <div class="mb-3 d-flex justify-content-between align-items-center d-print-none">
            <a href="{{ route('sales.index') }}" class="text-secondary text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to History
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </button>
        </div>

        <div class="card border-0 shadow-sm" id="invoice_card">
            <div class="card-body p-4">
                <!-- Print Styles -->
                <style>
                    @media print {
                        @page {
                            margin: 0.5cm;
                            size: A4 portrait;
                        }

                        body {
                            font-family: 'Arial', sans-serif;
                            color: #000;
                            background: #fff;
                        }

                        .d-print-none {
                            display: none !important;
                        }

                        .card {
                            border: none !important;
                            shadow: none !important;
                        }

                        .invoice-header {
                            text-align: center;
                            margin-bottom: 20px;
                        }

                        .company-name {
                            font-size: 24px;
                            font-weight: bold;
                            text-transform: uppercase;
                        }

                        .company-info {
                            font-size: 14px;
                            margin-bottom: 5px;
                        }

                        .invoice-title {
                            font-size: 18px;
                            font-weight: bold;
                            text-decoration: underline;
                            margin-top: 10px;
                        }

                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 10px;
                            font-size: 14px;
                        }

                        .info-col {
                            width: 48%;
                        }

                        .info-label {
                            font-weight: bold;
                            width: 80px;
                            display: inline-block;
                        }

                        .invoice-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 5px;
                            font-size: 13px;
                        }

                        .invoice-table th {
                            border-top: 1px solid #000;
                            border-bottom: 1px solid #000;
                            padding: 5px;
                            text-align: left;
                        }

                        .invoice-table td {
                            padding: 5px;
                            border-bottom: 1px solid #eee;
                        }

                        .invoice-table th.text-end,
                        .invoice-table td.text-end {
                            text-align: right;
                        }

                        .invoice-table th.text-center,
                        .invoice-table td.text-center {
                            text-align: center;
                        }

                        .footer-section {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 20px;
                        }

                        .terms-box {
                            width: 60%;
                            border: 1px solid #000;
                            padding: 10px;
                            font-size: 12px;
                            text-align: right;
                            direction: rtl;
                            font-family: 'Noto Nastaliq Urdu', 'Arial', sans-serif;
                        }

                        .terms-list {
                            list-style: none;
                            padding: 0;
                            margin: 0;
                        }

                        .terms-list li {
                            margin-bottom: 4px;
                            position: relative;
                            padding-right: 15px;
                        }

                        .terms-list li::before {
                            content: "•";
                            position: absolute;
                            right: 0;
                        }

                        .summary-box {
                            width: 35%;
                            font-size: 14px;
                        }

                        .summary-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 5px;
                        }

                        .summary-label {
                            font-weight: bold;
                        }

                        .amount-words {
                            margin-top: 10px;
                            font-weight: bold;
                            font-style: italic;
                            font-size: 14px;
                        }
                    }

                    /* Screen styles usually handled by Bootstrap, adding specific tweaks */
                    .invoice-header {
                        text-align: center;
                    }

                    .terms-box {
                        border: 1px solid #dee2e6;
                        padding: 15px;
                        border-radius: 4px;
                    }
                </style>

                <!-- Header -->
                <div class="invoice-header">
                    <div class="company-name">Unique Hardware</div>
                    <div class="company-info">B-Block, Gulberg Green, Islamabad</div>
                    <div class="company-info">Mob # 0336-9009418, 0303-5877464</div>
                    <div class="invoice-title">INVOICE</div>
                </div>

                <!-- Customer & Invoice Info -->
                <div class="row mb-3 font-monospace" style="font-size: 14px;">
                    <div class="col-6">
                        <div class="d-flex">
                            <span class="fw-bold" style="width: 100px;">Shop Name:</span>
                            <span>{{ $sale->customer_name ?? 'Walking Customer' }}</span>
                        </div>
                        <div class="d-flex">
                            <span class="fw-bold" style="width: 100px;">Address:</span>
                            <span>{{ $sale->customer_phone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="d-flex justify-content-end">
                            <span class="fw-bold me-2">Date:</span>
                            <span>{{ $sale->created_at->format('d-M-Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <span class="fw-bold me-2">Invoice #:</span>
                            <span>{{ $sale->invoice_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="table invoice-table table-sm">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Sr#</th>
                            <th>Desc</th>
                            <th class="text-center" style="width: 60px;">Qty</th>
                            <th class="text-end" style="width: 100px;">Price</th>
                            <th class="text-end" style="width: 80px;">Discount</th>
                            <th class="text-end" style="width: 120px;">Net Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sr = 1; @endphp
                        @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $sr++ }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">0.00</td>
                                <td class="text-end">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 2px solid #000;">
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">Total:</td>
                            <td class="text-center">{{ $sale->items->sum('quantity') }}</td>
                            <td colspan="2"></td>
                            <td class="text-end">{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="amount-words mb-4">
                    Amount in words: <span class="text-uppercase" id="amountInWords"></span>
                </div>

                <!-- Footer Section -->
                <div class="row">
                    <!-- Terms Box (Right-to-Left for Urdu) -->
                    <div class="col-7">
                        <div class="terms-box bg-light"
                            style="direction: rtl; text-align: right; font-family: 'Noto Nastaliq Urdu', serif; line-height: 1.8;">
                            <ul class="list-unstyled mb-0 pe-0">
                                <li>سامان گندا ہونے کی صورت میں ۱۰٪ کٹوتی ہوگی۔</li>
                                <li>ایک مہینے بعد واپس آنے والے سامان سے ۱۰٪ کٹوتی ہوگی۔</li>
                                <li>ہارڈویئر اور ٹولز آئٹمز کی واپسی نہیں ہوگی۔</li>
                                <li>اصل بل کے بغیر سامان کی واپسی نہیں ہوگی۔</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Summary Box -->
                    <div class="col-5">
                        <div class="ps-3 font-monospace">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">Inv Total:</span>
                                <span>{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">Inv Discount:</span>
                                <span>0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 border-top pt-1 text-dark fw-bold">
                                <span>Net Total:</span>
                                <span>{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">Paid:</span>
                                <span>0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 border-top pt-1">
                                <span class="fw-bold">Inv Balance:</span>
                                <span>{{ number_format($sale->total_amount, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1 text-muted">
                                <span class="fw-bold">Pre Balance:</span>
                                <span>0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-muted">
                                <span class="fw-bold">New Balance:</span>
                                <span>{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5 small text-muted d-print-none">
                    <p>Generated by Unique Hardware POS</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple Number to Words Converter
        function numberToWords(number) {
            const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
            const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            if (number === 0) return 'Zero Only';

            // Simple implementation for demo (supports up to thousands)
            // For production, use a robust library or complete function
            let words = '';

            if (number >= 1000) {
                words += numberToWords(Math.floor(number / 1000)) + ' Thousand ';
                number %= 1000;
            }

            if (number >= 100) {
                words += units[Math.floor(number / 100)] + ' Hundred ';
                number %= 100;
            }

            if (number >= 20) {
                words += tens[Math.floor(number / 10)] + ' ';
                number %= 10;
            }

            if (number >= 10) {
                words += teens[number - 10] + ' ';
                number = 0;
            }

            if (number > 0) {
                words += units[number] + ' ';
            }

            return words.trim();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const total = {{ $sale->total_amount }};
            document.getElementById('amountInWords').textContent = numberToWords(Math.floor(total)) + ' Only';
        });
    </script>
@endsection