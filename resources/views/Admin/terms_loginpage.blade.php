<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Terms & Conditions</title>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- iCheck Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- AdminLTE Theme Style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .content-wrapper {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            /* padding: 30px; */
            border-radius: 10px;
        }
        .terms-container {
            background: #f8f9fa;
            /* padding: 40px; */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .terms-box {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: start;
        }
        .terms-box img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            margin-top: 5px;
        }
        .terms-text {
            flex: 1;
        }
        h1 {
            font-size: 2rem;
            font-weight: bold;
        }
        h4 {
            margin-top: 20px;
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="content-wrapper mx-auto">
    <section class="text-center mb-4">
        <h1>Terms and Conditions</h1>
        <!-- <p class="text-muted">Last Updated: {{ date('F d, Y') }}</p> -->
    </section>

    <div class="container mt-5 terms-container">
        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text"> The product will be delivered to the customer within thirty days of payment.</p>
        </div>

        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text">If there is any damage to the said product, the product should be sent back to the company within seven days and the customer care should be informed.</p>
        </div>

        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text">If the product is unsatisfied or returned for any other reason, the product will be replaced. There will not be any refund.</p>
        </div>

        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text"> After a customer receives the activation product from the company, they have no right to participate in the financial and non financial activities of the company, but can earn income by promoting the marketing plan of the company.</p>
        </div>

        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text">The Company reserves the right to cancel their ID without giving a notice if they misrepresent the Company's marketing plan or act against the Company.</p>
        </div>

        <div class="terms-box">
            <img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">
            <p class="terms-text">These terms are governed by the laws of the Country/State. The company reserves the right to modify these terms at any time.</p>
        </div>
        <button class="btn btn-primary" onclick="goBack()">Accept and Continue</button>
    </div>
</div>

<script> function goBack() { window.history.back(); }</script>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap Bundle -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

</body>
</html>