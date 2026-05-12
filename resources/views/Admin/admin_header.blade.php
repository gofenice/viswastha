<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'vishwastha  | Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
</head>

<style>
    img.mlm-logo {
        width: 100%;
    }
</style>
{{-- <style>
    .diwali-message {
        background-color: #fff7e6;
        color: #ff6f00;
        border-radius: 20px;
        padding: 6px 12px;
        font-weight: 600;
        animation: glow 1.5s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from {
            text-shadow: 0 0 5px #ffcc80;
        }

        to {
            text-shadow: 0 0 15px #ff6f00;
        }
    }
</style> --}}

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                @if (session()->has('impersonate'))
                    <li class="nav-item">
                        <a href="{{ route('admin.impersonate-leave') }}" class="btn btn-sm btn-danger ml-2 mt-2">
                            Return to Admin
                        </a>
                    </li>
                @endif
            </ul>

            {{-- <div class="mx-auto text-center">
                <span class="diwali-message">
                    🎉 Happy Diwali! 🎆 We’re excited to announce something new — the Basic Rank is now open!
                </span>
            </div> --}}


            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                {{-- <li class="nav-item d-none d-sm-inline-block">
          <a href="{{route('logout')}}" class="nav-link">Logout</a>
        </li> --}}
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('adminhome') }}" class="brand-link" style="background: #e9ecef">
                <img class="mlm-logo" src="{{ asset('assets/dist/img/whitelogo.png') }}" alt="logo">
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Test</a>
          </div>
        </div> -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview">
                            <a href="{{ route('adminhome') }}" class="nav-link dashboard">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <!-- <i class="right fas fa-angle-left"></i> -->
                                </p>
                            </a>
                        </li>

                        <li class="nav-item has-treeview profile">
                            <a href="https://myvstore.in" target="_blank" class="nav-link profile">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    My Vstore
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview profile">
                            <a href="{{ route('view_profile') }}" class="nav-link profile">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Profile
                                </p>
                            </a>
                        </li>
                        @if (Auth::check() && Auth::user()->role == 'superadmin')
                            <li class="nav-item has-treeview profile">
                                <a href="{{ route('adminWallet') }}" class="nav-link adminWallet">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>
                                        Admin Wallet
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview profile">
                                <a href="{{ route('donationWallet') }}" class="nav-link donationwall">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>
                                        Donation Wallet
                                    </p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item has-treeview mother">
                            <a href="#" class="nav-link mother">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>
                                    Mother ID
                                    {{-- <span class="right badge badge-danger">Coming soon</span> --}}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview mother">
                                @if (Auth::check() && Auth::user()->role != 'superadmin')
                                    <li class="nav-item">
                                        <a href="{{ route('motheridlist') }}" class="nav-link mother_id">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Child ID</p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::check() && Auth::user()->role == 'superadmin')
                                    <li class="nav-item">
                                        <a href="{{ route('bank_detail_list') }}" class="nav-link bank-list">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Bank Detail list</p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::check() && Auth::user()->role != 'superadmin' && Auth::user()->mother_id === '1')
                                    <li class="nav-item">
                                        <a href="{{ route('childToMotherIncome_list') }}" class="nav-link mother-list">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Mother ID Transactions</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @if (Auth::check() && Auth::user()->mother_id === '1')
                            <li class="nav-item has-treeview wallet">
                                <a href="#" class="nav-link wallet">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Wallet
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview wallet">
                                    <li class="nav-item">
                                        <a href="{{ route('edit_bank_details') }}" class="nav-link bank_details">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Update Bank Details</p>
                                        </a>
                                    </li>
                                    @if (Auth::check() && Auth::user()->role != 'superadmin' && Auth::user()->mother_id === '1')
                                        <li class="nav-item">
                                            <a href="{{ route('transferToWallet') }}" class="nav-link transftow">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Transfer To Wallet</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ route('withdrawal_view') }}" class="nav-link withdrawal">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Withdrawal</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item has-treeview team">
                            <a href="#" class="nav-link team">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Team
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview team">
                                @php $migrationDone = \App\Models\BinaryTreeSetting::current()->migration_complete; @endphp
                                @if(!in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <li class="nav-item">
                                    <a href="{{ route('user.binary_tree') }}" class="nav-link binary">
                                        <i class="fas fa-sitemap nav-icon"></i>
                                        <p>My Tree @if(!$migrationDone)<span class="badge badge-warning badge-sm ml-1">Migration</span>@endif</p>
                                    </a>
                                </li>
                                @if(!$migrationDone)
                                <li class="nav-item">
                                    <a href="{{ route('sunflower') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>My Team (Sunflower)</p>
                                    </a>
                                </li>
                                @endif
                                @endif
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                <li class="nav-item">
                                    <a href="{{ route('admin.binary_tree') }}" class="nav-link">
                                        <i class="fas fa-sitemap nav-icon text-warning"></i>
                                        <p>Binary Tree <span class="badge badge-warning badge-sm ml-1">Admin</span></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.binary_income') }}" class="nav-link">
                                        <i class="fas fa-chart-bar nav-icon text-success"></i>
                                        <p>Binary Income <span class="badge badge-success badge-sm ml-1">Admin</span></p>
                                    </a>
                                </li>
                                @endif
                                @if($migrationDone || auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                <li class="nav-item">
                                    <a href="{{ route('binary_income_details') }}" class="nav-link">
                                        <i class="fas fa-money-bill-wave nav-icon text-primary"></i>
                                        <p>My Binary Income</p>
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item has-treeview rank_details">
                                    <a href="{{ route('rank_details') }}" class="nav-link rank_details">
                                        <i class="nav-icon fas fa-medal">‌</i>
                                        <p>
                                            Premium Rank
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item has-treeview rank_details">
                                    <a href="{{ route('BasicRank_details') }}" class="nav-link basicrank_details">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Basic Ranks
                                        </p>
                                    </a>
                                </li>
                                {{-- @if (auth()->user()->role === 'user' || auth()->user()->role === 'admin')
                                    <li class="nav-item">
                                        <a href="{{ route('rank_tree') }}" class="nav-link rank_tree">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rank Tree</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('view_sponsor') }}" class="nav-link sponsor">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Sponsor List</p>
                                        </a>
                                    </li>
                                @endif --}}

                            </ul>
                        </li>
                        {{-- @if (Auth::check() && Auth::user()->role === 'superadmin')
                            <li class="nav-item has-treeview">
                                <a href="{{ route('product_view') }}" class="nav-link product">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>
                                        Product
                                    </p>
                                </a>
                            </li>
                        @endif --}}
                        <li class="nav-item has-treeview pin">
                            <a href="#" class="nav-link pin">
                                <i class="nav-icon fas fa-key"></i>
                                <p>
                                    Pin Management
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pin">
                                <li class="nav-item">
                                    <a href="{{ route('add_wallet') }}" class="nav-link addwallet">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Receipt Upload</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('generate_pin') }}" class="nav-link generatepin">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Generate Pin(s)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('view_pin') }}" class="nav-link viewpin">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Pin(s)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (Auth::check() && Auth::user()->role === 'superadmin')
                            <li class="nav-item has-treeview">
                                <a href="{{ route('package') }}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>
                                        Package
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('purchase_wallets') }}" class="nav-link">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>Purchase Wallets</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('statement') }}" class="nav-link">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Statement
                                        {{-- <span class="right badge badge-danger">don't open</span> --}}
                                    </p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item has-treeview income">
                            <a href="#" class="nav-link income">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Income
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview income">
                                <li class="nav-item">
                                    <a href="{{ route('sponsor_income_details') }}" class="nav-link sponsorincome">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Referral Income</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('referral_income') }}" class="nav-link referal">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Referral Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('levelincomelistbasic') }}" class="nav-link basiclevel">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Basic Level Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('levelincomelist') }}" class="nav-link match">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Premium Level Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('rank_income_list') }}" class="nav-link rank">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Premium Rank Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('basicRank_incomeList') }}" class="nav-link basicrank">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Basic Rank Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('royalty_user_wallet') }}" class="nav-link incentive">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Royalty Incentive</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('repurchase_income_list') }}"
                                        class="nav-link repurchaseincomeli">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Repurchase Incentive</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="{{ route('bonus_user_wallet') }}" class="nav-link special">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Special Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('privilege_user_wallet') }}" class="nav-link privilegeinc">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Privilege Member Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('board_user_wallet') }}" class="nav-link boardinc">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Board Member Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('executive_user_wallet') }}" class="nav-link executiveinc">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Executive Incentive</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ route('incentive_user_wallet') }}"
                                        class="nav-link incentiveinc">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Incentive</p>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Special Incentive</p>
                                        <span class="right badge badge-danger">Coming soon</span>
                                    </a>
                                </li> --}}

                            </ul>
                        </li>
                        @if (Auth::check() && Auth::user()->role === 'superadmin')
                            <li class="nav-item has-treeview preRank">
                                <a href="#" class="nav-link preRank">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Premium Rank
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview preRank">
                                    <li class="nav-item">
                                        <a href="{{ route('rank_income') }}" class="nav-link rank">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rank List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('rank_histories') }}" class="nav-link rankht">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rank History</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('premium_rank_list') }}" class="nav-link rankpreList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Premium rank list</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('companyRank_income') }}" class="nav-link company_rank">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Company Rank Incentive</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview basicRank">
                                <a href="#" class="nav-link basicRank">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Basic Rank
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview basicRank">
                                    <li class="nav-item">
                                        <a href="{{ route('basicRank_income') }}" class="nav-link ranklist">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rank List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('basicCompanyRank_income') }}"
                                            class="nav-link basicCompany">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Basic Rank Incentive</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="{{ route('view_sponsor_superadmin') }}" class="nav-link sponsor_admin">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Sponsor List
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{ route('userlist') }}" class="nav-link userlist">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        User List
                                    </p>
                                </a>
                            </li>
                            {{-- <li class="nav-item ">
                                <a href="{{ route('companyRank_income') }}" class="nav-link company_rank">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Company Rank Incentive
                                    </p>
                                </a>
                            </li> --}}
                            <li class="nav-item ">
                                <a href="{{ route('trash_wallet') }}" class="nav-link trash_wallet">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Trash Wallet
                                    </p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role === 'superadmin')
                            <li class="nav-item has-treeview royalty">
                                <a href="#" class="nav-link royalty">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Royalty Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview royalty">
                                    <li class="nav-item">
                                        <a href="{{ route('royalty_users') }}" class="nav-link royaltyuser">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Royalty Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('royalty_wallet') }}" class="nav-link royaltywallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Royalty wallet</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('royaltyUsersAmtList') }}"
                                            class="nav-link royaltyUsersAmtList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Royalty Income Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview privilege">
                                <a href="#" class="nav-link privilege">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Privilege Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview privilege">
                                    <li class="nav-item">
                                        <a href="{{ route('privilege_users') }}" class="nav-link privilegeuser">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Privilege Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('privilege_wallet') }}" class="nav-link privilegewallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Privilege wallet</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('privilegeUsersAmtList') }}"
                                            class="nav-link privilegeUsersAmtList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Privilege Incentive Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview board">
                                <a href="#" class="nav-link board">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Board Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview board">
                                    <li class="nav-item">
                                        <a href="{{ route('board_users') }}" class="nav-link boarduser">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Board Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('board_wallet') }}" class="nav-link boardwallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Board wallet</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('boardUsersAmtList') }}"
                                            class="nav-link boardUsersAmtList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Board Incentive Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview executive">
                                <a href="#" class="nav-link executive">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Executive Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview executive">
                                    <li class="nav-item">
                                        <a href="{{ route('executive_users') }}" class="nav-link executiveuser">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Executive Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('executive_wallt') }}" class="nav-link executivewallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Executive wallet</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('executiveUsersAmtList') }}"
                                            class="nav-link executiveUsersAmtList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Executive Incentive Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- <li class="nav-item has-treeview incentive">
                                <a href="#" class="nav-link incentive">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview incentive">
                                    <li class="nav-item">
                                        <a href="{{ route('incentive_users') }}" class="nav-link incentiveuser">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Incentive Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('incentive_wallet') }}" class="nav-link incentivewallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Incentive Wallet</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('incentiveUsersAmtList') }}"
                                            class="nav-link incentiveUsersAmtList">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Incentive Income Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <li class="nav-item has-treeview bonus">
                                <a href="#" class="nav-link bonus">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Special Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview bonus">
                                    <li class="nav-item">
                                        <a href="{{ route('bonus_users') }}" class="nav-link bonususer">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Special Incentive Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('bonus_wallet') }}" class="nav-link bonuswallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Special Incentive wallet</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        {{-- @if (Auth::check() && Auth::user()->role === 'superadmin') --}}
                        <li class="nav-item has-treeview product">
                            <a href="#" class="nav-link product">
                                <i class="nav-icon fas fa-tree"></i>
                                <p>
                                    Product
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview product">
                                @if (Auth::check() && Auth::user()->role === 'superadmin')
                                    <li class="nav-item">
                                        <a href="{{ route('product_view') }}" class="nav-link addproduct">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Product</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('view_products') }}" class="nav-link viewproduct">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Product</p>
                                    </a>
                                </li>
                                @if (Auth::check() && Auth::user()->role !== 'superadmin')
                                    <li class="nav-item">
                                        <a href="{{ route('order_product') }}" class="nav-link orderproduct">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Order Product</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('view_order') }}" class="nav-link viewOrder">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>View Order</p>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::check() && Auth::user()->role === 'superadmin')
                                    {{-- <li class="nav-item">
                                        <a href="{{ route('holiday_package_list') }}" class="nav-link urpddy">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Holiday Package Booking</p>
                                        </a>
                                    </li> --}}
                                    <li class="nav-item">
                                        <a href="{{ route('user_product_list') }}" class="nav-link urpddy">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>User Orders</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item has-treeview myvstore">
                            <a href="#" class="nav-link myvstore">
                                <i class="nav-icon ion ion-bag"></i>
                                <p>
                                    My Vstore App Incentive
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview myvstore">
                                <li class="nav-item">
                                    <a href="{{ route('repurchase_list') }}" class="nav-link repurchaseli">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Repurchase Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('selfpurchase_income_list') }}"
                                        class="nav-link selfpurchaseincome">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Self purchase Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('franchisee_income_list') }}"
                                        class="nav-link franchiseeincome">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Franchisee Incentive</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview myvstoreon">
                            <a href="#" class="nav-link myvstoreon">
                                <i class="nav-icon ion ion-bag"></i>
                                <p>
                                    My Vstore Online Incentive
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview myvstore">
                                <li class="nav-item">
                                    <a href="{{ route('repurchase_list_online') }}" class="nav-link repurchaselion">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Repurchase Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('selfpurchase_income_list_online') }}"
                                        class="nav-link selfpurchaseincomeon">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Self purchase Incentive</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('franchisee_income_list_online') }}"
                                        class="nav-link franchiseeincomeon">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Franchisee Incentive</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (Auth::check() && Auth::user()->role == 'superadmin')
                            <li class="nav-item has-treeview repurchase">
                                <a href="#" class="nav-link repurchase">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        Repurchase Incentive
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview repurchase">
                                    <li class="nav-item">
                                        <a href="{{ route('repurchase_wallet') }}" class="nav-link repurchasewallet">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Repurchase wallet</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview repurchases">
                                <a href="#" class="nav-link repurchases">
                                    <i class="nav-icon ion ion-bag"></i>
                                    <p>
                                        App Control
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview repurchases">

                                    <li class="nav-item">
                                        <a href="{{ route('addcategoryper') }}" class="nav-link cat">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add category</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('offlinePurchase_list') }}" class="nav-link ofpurlist">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Offline Purchase List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('shopCoupn_list') }}" class="nav-link shcolit">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Shop Coupon List</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('shopReceipt_list') }}" class="nav-link shreli">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Shop Receipt List</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('franchisee') }}" class="nav-link franc">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Franchisee
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('gst_tcs_list') }}" class="nav-link gst_tcs_list">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        GST TCS List
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('view_board_members') }}" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                        Board Members
                                    </p>
                                </a>
                            </li>
                        @endif
                        {{-- @endif --}}
                        <li class="nav-item">
                            <a href="{{ route('shop_list') }}" class="nav-link shoplist">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Offline Shops</p>
                            </a>
                        </li>

                        @if (Auth::check() && Auth::user()->role !== 'superadmin')
                            <li class="nav-item has-treeview">
                                <a href="{{ route('your_account') }}" class="nav-link earning">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Earnings
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('user_donation') }}" class="nav-link userdonate">
                                    <i class="nav-icon fas fa-university"></i>
                                    <p>
                                        Donation
                                    </p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="{{ Auth::check() && Auth::user()->role === 'superadmin' ? route('support_view_admin') : route('support_view') }}"
                                class="nav-link support">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    Support
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('logout') }}" class="nav-link">
                                <i class="nav-icon fa fa-power-off"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#!" class="nav-link">
                                <i class="nav-icon fa fa-"></i>
                                <p>

                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        @yield('content')

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024-2025 <a href="#">vishwastha </a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script src="{{ asset('assets/dist/js/sweetalert.js') }}"></script>
    @yield('footer');
</body>

</html>
