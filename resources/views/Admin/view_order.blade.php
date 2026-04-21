@extends('Admin.admin_header')
@section('title', 'vishwastha | View Order')
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
                        <h1>View Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">View Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @if ($getproducts->count() > 0 || $holidayBookings->count() > 0)
            <div class="card mt-3 recieptList col-md-11 mx-auto">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info">
                                <th>User</th>
                                <th>Package</th>
                                <th>Product</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Invoice</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getproducts as $product)
                                <tr>
                                    <td>{{ $product->user->name }}<br>{{ $product->user->connection }}</td>
                                    <td>{{ $product->package->name }}</td>
                                    <td>{{ $product->product->product_name }}</td>
                                    <td>{{ $product->address }}</td>
                                    <td>{{ $product->phone_no }}</td>
                                    <td>{{ $product->email }}</td>
                                    <td>
                                        @if ($product->invoice_path)
                                            <a href="{{ asset($product->invoice_path) }}" download class="btn btn-primary">
                                                Download Invoice
                                            </a>
                                        @else
                                            <p>Invoice not available</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->status == 0)
                                            Waiting for Confirmation
                                        @elseif($product->status == 1)
                                            Your Order Confirmed
                                        @elseif($product->status == 2)
                                            Your Order has been delivered
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($holidayBookings as $product)
                                <tr>
                                    <td>{{ $product->user->name }}<br>{{ $product->user->connection }}</td>
                                    <td>{{ $product->package->name }}</td>
                                    <td>{{ $product->product->product_name }}</td>
                                    <td>{{ $product->address }}</td>
                                    <td>{{ $product->phone_no }}</td>
                                    <td>{{ $product->email }}</td>
                                    <td>
                                        @if ($product->invoice_path)
                                            <a href="{{ asset($product->invoice_path) }}" download class="btn btn-primary">
                                                Download Invoice
                                            </a>
                                        @else
                                            <p>Invoice not available</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->status == 0)
                                            Waiting for Confirmation
                                        @elseif($product->status == 1)
                                            Your Order Confirmed
                                        @elseif($product->status == 2)
                                            Your Order has been delivered
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div style=" display: flex; justify-content: center; align-items: center; margin-top: 200px; ">No Orders</div>
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
            const viewpinLink = $('.nav-link.viewOrder');
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
@endsection
