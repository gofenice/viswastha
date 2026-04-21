<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .no-border {
            border: none !important;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .declaration-section {
            font-size: 13px;
            margin-top: 10px;
        }

        .footer-note {
            text-align: center;
            font-size: 13px;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="9" class="invoice-title">TAX INVOICE</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="3" style="text-align: center; vertical-align: middle;">
                {{-- <strong>VISHWASTHA</strong><br> --}}
                <img src="{{ public_path('bgremlogo.png') }}" alt="VISHWASTHA" class="company-logo"
                    style="max-width: 320px; display: block; margin: 0 auto 8px auto;"><br>
                GSTIN/UIN: 32AAKCV5595Q1ZD<br>
                Address: V Holidays, Vaga Copper Castle
                Building no - 17/537-C, Vagamon Idukki
                Pin No: 685503
                <br>
                vishwasthamarketing@gmail.com<br>
                +91 9207771603
            </td>
            <td colspan="3">
                <strong>Invoice No:</strong> VH-0{{ $receipt_number }}
            </td>
            <td colspan="3">
                <strong>Dated:</strong> {{ date('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>Supplier's Ref:</strong>
            </td>
            <td colspan="3">
                <strong>Reverse Charge:</strong> No
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <strong>Buyer:-</strong><br>
                {{ $user->name }}<br>
                {{ $user->connection }}<br>
                {{ $address }}<br>
                {{ $phone_no }}
            </td>
        </tr>
        <tr class="bold text-center">
            <th>Sl</th>
            <th colspan="2">Particulars</th>
            <th>HSN/SAC</th>
            <th>GST</th>
            <th>Rate</th>
            <th>Quantity</th>
            <th colspan="2">Amount</th>
        </tr>
        <tr>
            <td class="text-center">1</td>
            <td colspan="2"><strong>{{ $product->product_name }}</strong></td>
            <td></td>
            <td>{{ $totalper }}</td>
            <td class="text-right">{{ number_format($product_price, 2) }}</td>
            <td class="text-center">1</td>
            <td colspan="2" class="text-right">{{ number_format($product_price, 2) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" class="text-right"><strong>CGST</strong></td>
            <td></td>
            <td>{{ $per }}</td>
            <td></td>
            <td></td>
            <td colspan="2" class="text-right">{{ number_format($cgst, 2) }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" class="text-right"><strong>SGST</strong></td>
            <td></td>
            <td>{{ $per }}</td>
            <td></td>
            <td></td>
            <td colspan="2" class="text-right">{{ number_format($sgst, 2) }}</td>
        </tr>
        <tr>
            <td colspan="8" class="text-right bold">Total</td>
            <td class="text-right bold">{{ number_format($totalprdamt, 2) }}</td>
        </tr>
        <tr>
            <td colspan="9" class="bold">
                Amount Chargeable (in words):<br>
                Indian Rupees
                {{ \Illuminate\Support\Str::title(NumberFormatter::create('en_IN', NumberFormatter::SPELLOUT)->format($totalprdamt)) }}
                Only
            </td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td rowspan="6" colspan="2" style="width: 50%; vertical-align: top;">
                <i>Remarks:</i><br><br><br><br><br><br><br><br><br>
            </td>
            <th class="text-center" rowspan="2">Taxable<br>Value</th>
            <th class="text-center" colspan="2">Central Tax</th>
            <th class="text-center" colspan="2">State Tax</th>
            <th class="text-center" rowspan="2">Total<br>Tax Amount</th>
        </tr>
        <tr>
            <th class="text-center">Rate</th>
            <th class="text-center">Amount</th>
            <th class="text-center">Rate</th>
            <th class="text-center">Amount</th>
        </tr>
        <tr>
            <td class="text-center">{{ number_format($product_price, 2) }}</td>
            <td class="text-center">{{ $per }}</td>
            <td class="text-center">{{ number_format($cgst, 2) }}</td>
            <td class="text-center">{{ $per }}</td>
            <td class="text-center">{{ number_format($sgst, 2) }}</td>
            <td class="text-center">{{ number_format($cgst + $sgst, 2) }}</td>
        </tr>
        <tr>
            <td class="text-center bold">Total</td>
            <td class="text-center bold"></td>
            <td class="text-center bold">{{ number_format($cgst, 2) }}</td>
            <td class="text-center bold"></td>
            <td class="text-center bold">{{ number_format($sgst, 2) }}</td>
            <td class="text-center bold">{{ number_format($cgst + $sgst, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="bold"> Indian Rupees
                {{ \Illuminate\Support\Str::title(NumberFormatter::create('en_IN', NumberFormatter::SPELLOUT)->format($totalprdamt)) }}
                Only</td>
        </tr>
        <tr>
            <td colspan="6">
                <strong><u>Company's Bank Details</u></strong><br>
                Bank Name: Kotak Mahindra Bank <br>
                A/c No.: 3750039023 <br>
                IFS Code: KKBK0000204
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 70%;">
                <strong>Declaration</strong><br>
                We declare that the amount charged is for the services provided or to be
                provided as mentioned in the invoice. The contents of the invoice are true and correct.
            </td>
            <td style="text-align: center;">
                <strong>For VISHWASTHA</strong><br><br><br>
                Authorised Signatory
            </td>
        </tr>
    </table>

    <div class="footer-note">
        This is a Computer Generated Invoice
    </div>
</body>

</html>
