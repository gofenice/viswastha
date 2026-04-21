@extends('Admin.admin_header')
@section('title', 'vishwastha | Transfer Section')
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
                        <h1>Transfer To Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transfer</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $user->total_income }}</h3>

                            <p>Current Wallet</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $levelbasic ?? 0 }}</h3>

                            <p>Basic Level income</p>
                            @if ($levelbasic > 0)
                                <form id="basiclevelForm" action="{{ route('basicLevelIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmbasicLevelBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $levelpremium ?? 0 }}</h3>

                            <p>Premium Level income</p>

                            @if ($levelpremium > 0)
                                <form id="levelForm" action="{{ route('levelIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmLevelBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>₹{{ $rankincome ?? 0 }}</h3>
                            <p>Rank Income</p>

                            @if ($rankincome > 0)
                                <form id="rankForm" action="{{ route('rankIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmrankBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $referrelbasic ?? 0 }}<h3>

                                    <p>Basic Referral Income</p>

                                    @if ($referrelbasic > 0)
                                        <form id="basicreferralForm" action="{{ route('basicReferralIncomeTransfer') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $user->id }}" name="user_id">
                                            <button type="button" id="confirmBasicReferralBtn"
                                                class="btn btn-light">Transfer to
                                                Wallet</button>
                                        </form>
                                    @endif
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $referrelpremium }}</h3>

                            <p>Premium Referral Income</p>

                            @if ($referrelpremium > 0)
                                <form id="referralForm" action="{{ route('referralIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmReferralBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-indigo">
                        <div class="inner">
                            <h3>{{ $royaltyIncome }}</h3>

                            <p>Royalty Incentives</p>

                            @if ($royaltyIncome > 0)
                                <form id="royaltyForm" action="{{ route('royaltyIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmroyaltyBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-fuchsia">
                        <div class="inner">
                            <h3>Coming Soon</h3>

                            <p>Special Incentive</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>₹{{ $bonus }}</h3>

                            {{-- <p>Bonus</p> --}}
                            <p>Special Incentive</p>

                            @if ($bonus > 0)
                                <form id="bonusForm" action="{{ route('bonusIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmbonusBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $repurchaseTotal }}</h3>

                            <p>Vishwastha Income</p>

                            @if ($repurchaseTotal > 0)
                                <form id="repurchaseForm" action="{{ route('repurchaseIncomeTransfer') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmrepurchaseBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $privilegeTotal }}</h3>

                            <p>Privilege member Incentive</p>

                            @if ($privilegeTotal > 0)
                                <form id="privilegForm" action="{{ route('privilegeIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmPrivilegeBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $boardTotal }}</h3>

                            <p>Board member Incentive</p>

                            @if ($boardTotal > 0)
                                <form id="boardForm" action="{{ route('boardIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmBoardBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $executiveTotal }}</h3>

                            <p>Executive Incentive</p>

                            @if ($executiveTotal > 0)
                                <form id="executiveForm" action="{{ route('executiveIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmExecutiveBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $incentiveTotal }}</h3>

                            <p>Incentive Income</p>

                            @if ($incentiveTotal > 0)
                                <form id="referralForm" action="{{ route('incentiveIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmIncentiveBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>  --}}


                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $basicRankIncome }}</h3>

                            <p>Basic Rank Income</p>

                            @if ($basicRankIncome > 0)
                                <form id="basicRankForm" action="{{ route('basicRankIncomeTransfer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                                    <button type="button" id="confirmBasicRankBtn" class="btn btn-light">Transfer to
                                        Wallet</button>
                                </form>
                            @endif

                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="widthdrawl" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($WalletTransactionDetail as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->user->name }} <br> {{ $detail->user->connection }}</td>
                                    <td>
                                        @if ($detail->type == 1)
                                            Level Income
                                        @elseif ($detail->type == 2)
                                            Referral Income
                                        @elseif ($detail->type == 3)
                                            Rank Income
                                        @elseif ($detail->type == 4)
                                            Royalty Income
                                        @elseif ($detail->type == 5)
                                            Bonus Income
                                        @elseif ($detail->type == 6)
                                            Repurchase Income
                                        @elseif ($detail->type == 7)
                                            Privilege Income
                                        @elseif ($detail->type == 8)
                                            Board Income
                                        @elseif ($detail->type == 9)
                                            Executive Income
                                        @elseif ($detail->type == 10)
                                            Incentive Income
                                        @elseif ($detail->type == 11)
                                            Basic Rank Income
                                        @endif
                                    </td>
                                    <td>{{ $detail->amount }}</td>
                                    <td>{{ $detail->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        @if (session()->has('error'))
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Error!",
                text: "{{ session()->get('error') }}",
                showConfirmButton: true,
                confirmButtonText: "OK"
            });
        @endif
        @if (session()->has('success'))
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
        $(document).ready(function() {
            const motherLink = $('.nav-link.wallet');
            const motherviewLink = $('.nav.nav-treeview.wallet');
            const mainLink = $('.nav-item.has-treeview.wallet');
            const motherIdLink = $('.nav-link.transftow');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
        $(function() {
            $("#widthdrawl").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const levelBtn = document.getElementById("confirmLevelBtn");
            if (levelBtn) {
                levelBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("levelForm").submit();
                        }
                    });
                });
            }

            const basiclevelBtn = document.getElementById("confirmbasicLevelBtn");
            if (basiclevelBtn) {
                basiclevelBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("basiclevelForm").submit();
                        }
                    });
                });
            }

            const referralBtn = document.getElementById("confirmReferralBtn");
            if (referralBtn) {
                referralBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("referralForm").submit();
                        }
                    });
                });
            }
            const basicreferralBtn = document.getElementById("confirmBasicReferralBtn");
            if (basicreferralBtn) {
                basicreferralBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("basicreferralForm").submit();
                        }
                    });
                });
            }

            const rankBtn = document.getElementById("confirmrankBtn");
            if (rankBtn) {
                rankBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("rankForm").submit();
                        }
                    });
                });
            }

            const royaltyBtn = document.getElementById("confirmroyaltyBtn");
            if (royaltyBtn) {
                royaltyBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("royaltyForm").submit();
                        }
                    });
                });
            }
            const bonusBtn = document.getElementById("confirmbonusBtn");
            if (bonusBtn) {
                bonusBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("bonusForm").submit();
                        }
                    });
                });
            }

            const repurchaseBtn = document.getElementById("confirmrepurchaseBtn");
            if (repurchaseBtn) {
                repurchaseBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the repurchase income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("repurchaseForm").submit();
                        }
                    });
                });
            }



            //----------------

            const privilegeBtn = document.getElementById("confirmPrivilegeBtn");
            if (privilegeBtn) {
                privilegeBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("privilegForm").submit();
                        }
                    });
                });
            }
            const boardBtn = document.getElementById("confirmBoardBtn");
            if (boardBtn) {
                boardBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("boardForm").submit();
                        }
                    });
                });
            }
            const executiveBtn = document.getElementById("confirmExecutiveBtn");
            if (executiveBtn) {
                executiveBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("executiveForm").submit();
                        }
                    });
                });
            }
            const incentiveBtn = document.getElementById("confirmIncentiveBtn");
            if (incentiveBtn) {
                incentiveBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("incentiveForm").submit();
                        }
                    });
                });
            }

            const basicRankBtn = document.getElementById("confirmBasicRankBtn");
            if (basicRankBtn) {
                basicRankBtn.addEventListener("click", function() {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to process the income transaction!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, proceed!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById("basicRankForm").submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection
