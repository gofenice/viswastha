@extends('Admin.admin_header')
@section('title', 'vishwastha  | Terms and Conditions')
@section('content')

<style>
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
    .content-wrapper{
        padding-bottom: 2rem;
    }
    p {
        font-size: 1.1rem;
        line-height: 1.6;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Terms and Conditions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Terms</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container mt-5 terms-container">
        <!-- <h2 class="text-center text-dark font-weight-bold mb-4">Our Policies</h2> -->

        <div class="terms-box">
            <p class="terms-text" ><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid"> The product will be delivered to the customer within thirty days of payment.</p>
        </div>

        <div class="terms-box">
            <p><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">If there is any damage to the said product, the product should be sent back to the company within seven days and the customer care should be informed.</p>
        </div>

        <div class="terms-box">
            <p><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">If the product is unsatisfied or returned for any other reason, the product will be replaced. There will not be any refund.</p>
        </div>

        <div class="terms-box">
            <p><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">  After a customer receives the activation product from the company, they have no right to participate in the financial and non financial activities of the company, but can earn income by promoting the marketing plan of the company.</p>
        </div>

        <div class="terms-box">
            <p><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid"> The Company reserves the right to cancel their ID without giving a notice if they misrepresent the Company's marketing plan or act against the Company.</p>
        </div>

        <div class="terms-box">
            <p><img src="{{ asset('assets/maintenance/images/check-mark.png') }}" class="img-fluid">  These terms are governed by the laws of the Country/State. The company reserve the right to modify these terms at any time.</p>
        </div>
        <button class="btn btn-primary" onclick="goBack()">Accept and Continue</button>
    </div>
</div>

<script> function goBack() { window.history.back(); }</script>

@endsection