@extends('Admin.admin_header')
@section('title', 'vishwastha | View Product')
@section('content')
    <style>
        .bg-light-danger {
            background-color: #f8d7da !important;
            /* Light red background */
        }

        button.btn.btn-default {
            background: transparent;
            border: 0;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Our Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Our Products</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container">
            <div class="card">
                {{-- <div class="card-header text-center">
                    <h3 class="card-title">Products</h3>
                </div> --}}
                <div class="card-body">
                    <div class="card-header text-center">
                        <h6 class="mb-3 text-left " style="font-weight: 600;">Premium Package Products</h6>
                    </div>

                    <div class="d-flex flex-nowrap overflow-auto gap-3">

                        @foreach ($packagesPremium as $premiumFirst)
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div class="card-product" style="flex-shrink: 0;">
                                    <div class="card-body product">
                                        <img src="{{ $premiumFirst->product_image }}" class="card-img-top" alt="Premium One"
                                            style="width: 12rem; height: 12rem; object-fit: cover;">
                                    </div>
                                </div>
                                <h5 class="text-center"
                                    style="margin-right: 18px; max-width: 240px; display: inline-block; word-wrap: break-word;">
                                    {{ $premiumFirst->product_name }}</h5>
                                <div class="" style="padding-left: 20%;">
                                    <p>{!! $premiumFirst->product_description !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-header text-center">
                        <h6 class="mb-3 mt-3 text-left" style="font-weight: 600;">Basic Package Product</h6>
                    </div>

                    <div class="d-flex flex-nowrap overflow-auto gap-3">
                        @foreach ($packagesBasic as $premiumSeconds)
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div class="card-product" style="flex-shrink: 0;">
                                    <div class="card-body product">
                                        <img src="{{ $premiumSeconds->product_image }}" class="card-img-top"
                                            alt="Premium Two" style="width: 12rem; height: 12rem; object-fit: cover;">
                                    </div>
                                </div>
                                <h5 class="text-center"
                                    style="margin-right: 18px; max-width: 240px; display: inline-block; word-wrap: break-word;">
                                    {{ $premiumSeconds->product_name }}
                                </h5>
                                <div class="" style="width: 12rem;">
                                    <p>{!! $premiumSeconds->product_description !!}</p>
                                </div>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: true,
                confirmButtonText: "OK"
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session()->get('error') }}",
            });
        </script>
    @endif
    <script>
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });

        $(document).ready(function() {
            const pinLink = $('.nav-link.product');
            const treeviewLink = $('.nav.nav-treeview.product');
            const mainLiLink = $('.nav-item.has-treeview.product');
            const viewpinLink = $('.nav-link.viewproduct');
            if (viewpinLink.length) {
                viewpinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
