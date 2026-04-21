<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/DejaVuSans.ttf') }}") format('truetype');
        }

        .total-cost {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            position: relative;
        }

        .receipt-number {
            position: absolute;
            top: 260px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border: 2px solid #007bff;
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-header h2 {
            color: #007bff;
            margin: 0;
        }

        .company-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-logo {
            max-width: 250px;
            margin-bottom: 10px;
        }

        .invoice-details{
            margin-bottom: 20px;
        }

        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="company-details">
            <img src="{{ public_path('bgremlogo.png') }}" alt="VISHWASTHA" class="company-logo">
            <p>101, Pratap Nagar, Mayur Vihar, Ph l, East Delhi - 110091, Delhi</p>
            <p>Email: vishwasthamarketing@gmail.com | Phone: +91 9074831316</p>
            <p>GST - 07AAKCV5595Q1Z6 </p>
        </div>

        <div class="invoice-header">
            <h2>Invoice</h2>
        </div>
        <div class="receipt-number">
            Invoice No: V-0{{ $receipt_number }}
        </div>
        
        <div class="invoice-details">
            <p><strong>Customer Details </strong></p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>ID:</strong> {{ $user->connection }}</p>
            <p><strong>Phone:</strong> {{ $phone_no }}</p>
            <p><strong>Address:</strong> {{ $address }}</p>
        </div>

        <table>
            <tr>
                <th>Product</th>
                <th>Package</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $package->name }}</td>
                <td class="total-cost">&#8377;{{ number_format($productAmt, 2) }}</td>
            </tr>
        </table>

        <p class="total total-cost"><strong>Total: &#8377; {{ number_format($productAmt, 2) }}</strong></p>

        <p><strong>Date:</strong> {{ now()->format('d-m-Y') }}</p>

        <div class="footer">
            <p>Thank you for your shopping!</p>
            <p><strong>This is a computerized document signature is not required.</strong></p>
        </div>
    </div>
</body>

</html>
