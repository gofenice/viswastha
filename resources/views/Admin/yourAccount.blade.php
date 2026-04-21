@extends('Admin.admin_header')
@section('title', 'vishwastha | Your Account')
@section('content')
    <style>
        .bg-light-danger {
            background-color: #f8d7da !important;
            /* Light red background */
        }

        .bg-custom {
            background-color: #007bff !important;
        }

        .inner {
            color: #fff !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Lifetime Earnings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Earnings</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container">
            <div class="row" style="justify-content: center">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $user->name }}</h3>

                            <p>{{ $user->connection }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                            <h3>₹ {{ $user->total_income }}</h3>

                            <p>Current Wallet</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                            <h3>₹ {{ $totalearnings }}</h3>

                            <p>Total Earnings</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                            <h3>₹ {{ $totalwithdrawal }}</h3>

                            <p>Total Withdrawal</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totalrefferalincomebasic }}</h3>

                            <p>Basic Referral Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totallevelbasic }}</h3>

                            <p>Basic Level Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totalrefferalincomepremium }}</h3>

                            <p>Premium Referral Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totallevelpremium }}</h3>

                            <p>⁠Premium Level Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totalrankincome }}</h3>
                            <p>Premium Rank Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totalRoyaltyIncome }}</h3>

                            <p>Royalty Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>₹ {{ $BonusWallet }}</h3>

                            {{-- <p>Bonus</p> --}}
                            <p>Special Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $totalPrivilegeIncome }}</h3>

                            <p>Privilege Member Incentive </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3> {{ $totalBoardIncome }}</h3>

                            <p>Board Member Incentive </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3> {{ $totalExecutiveIncome }}</h3>

                            <p>Executive Incentive </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3> {{ $totalBasicrankincome }}</h3>

                            <p>Basic Rank Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3> {{ $totalIncentiveIncome }}</h3>

                            <p>Incentive income </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div> --}}

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>₹{{ $repurchaseTotal }}</h3>

                            <p>Repurchase Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>₹{{ $selfpurchaseTotal }}</h3>

                            <p>Self Purchase Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>₹{{ $franchiseeTotal }}</h3>

                            <p>Franchisee Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.earning');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
