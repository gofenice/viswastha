@extends('Admin.admin_header')
@section('title', 'vishwastha | Your Product')
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
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Your Products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @if ($userProducts)
            <div class="card card-info col-md-8" style="margin: 0 auto;">
                <div class="card-header">
                    <h3 class="card-title">Get Your Product</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('save_user_product') }}" method="POST" id="user-order-form">
                    @csrf
                    <div class="card-body row">
                        <div class="col-md-6">
                            <label for="product_id" class="col-form-label">Select Product</label>
                            <div class="">
                                <select class="form-control" id="product_id" name="product_id" onchange="updateImage()"
                                    required>
                                    @foreach ($userProducts as $product)
                                        <option value="{{ $product->id }}" data-image="{{ $product->product_image }}">
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Image Display -->
                            <div id="image-container">
                                <img id="selected-image" src="" alt="Selected Product"
                                    style="max-width: 100px; display: none;">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Delivery Address <span class="text-danger">(with pin NO)</span></label>
                            <textarea class="form-control" name="delivery_address" required>{{ $user->address }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" value="{{ $user->phone_no }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                        <input type="hidden" name="package_id" value="{{ $packageIds->first() }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info float-right" id="submitBtn">Get Product</button>
                    </div>
                </form>
            </div>
        @else
        @endif
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
            const viewpinLink = $('.nav-link.orderproduct');
            if (viewpinLink.length) {
                viewpinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

        function updateImage() {
            var select = document.getElementById("product_id");
            var selectedOption = select.options[select.selectedIndex];
            var imageUrl = selectedOption.getAttribute("data-image");

            var imgElement = document.getElementById("selected-image");
            if (imageUrl) {
                imgElement.src = imageUrl;
                imgElement.style.display = "block"; // Show image
            } else {
                imgElement.style.display = "none"; // Hide image if no selection
            }
        }

        // Load image on page load (if needed)
        document.addEventListener("DOMContentLoaded", updateImage);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("user-order-form");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                // Disable button to prevent multiple clicks
                submitBtn.disabled = true;
                // Change button text to loading
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            });
        });
    </script>
@endsection
