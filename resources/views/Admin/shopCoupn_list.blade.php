@extends('Admin.admin_header')
@section('title', 'Vishwastha | Offline Purchase List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shop Coupon List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Shop Coupon List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Shop Name</th>
                            <th>Last Recharge Amount</th>
                            <th>Current Balance</th>
                            <th>Total Recharge</th>
                            <th>Last Recharge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shopCoupn_list as $index => $coupon)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $coupon->shop->name ?? 'N/A' }} </td>
                                <td>{{ $coupon->amount }}</td>
                                <td>{{ $coupon->balance }}</td>
                                <td>{{ $coupon->recharge_count }} </td>
                                <td>{{ \Carbon\Carbon::parse($coupon->last_recharged_at)->format('d-M-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session()->get('error') }}"
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
            const teamLink = $('.nav-link.repurchases');
            const treeviewLink = $('.nav.nav-treeview.repurchases');
            const mainLiLink = $('.nav-item.has-treeview.repurchases');
            const walletLink = $('.nav-link.shcolit');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
