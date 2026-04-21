@extends('Admin.admin_header')
@section('title', 'VISHWASTHA | Repurchase Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Repurchase Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Wallet</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6 mx-auto">
                    <div class="small-box bg-info" style=" color: black;">
                        <div class="inner">
                            <h4>Repurchase Income Wallet</h4>
                            <p class="mb-0">
                                Total : {{ $repurchaseWalletTotal }}
                            </p>
                            <p class="mb-0">
                                <a href="#" class="small-box-footer" style="color: black;">
                                    Redeemed : {{ $repurchaseWalletInactive }}
                                    {{-- <i class="fas fa-arrow-circle-right"></i> --}}
                                </a>
                            </p>
                            <p>
                                Active : {{ $repurchaseWalletActive }}
                            </p>

                        </div>
                        <div class="flex justify-around mt-2 pb-2">

                            <!-- Redeemed to Company Button -->
                            <a href="#" class="px-1 py-2 "
                                style="color: black;justify-content: center;display: flex;align-items: center;"
                                data-toggle="modal" data-target="#repurchaseapproveModal">
                                Redeem
                                <i class="fas fa-arrow-circle-right mr-2 ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Amount List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Purchased User</th>
                            <th>User</th>
                            <th>Order Id</th>
                            <th>Amount</th>
                            <th>Amount Type</th>
                            <th>Created</th>
                            <th>Product Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repurchaseData as $index => $wallet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $wallet->productOrderedUser->name ?? 'N/A' }} <br>
                                    <small>{{ $wallet->productOrderedUser->connection ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    {{ $wallet->user->name ?? 'N/A' }} <br>
                                    <small>{{ $wallet->user->connection ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $wallet->order_id }}</td>
                                <td>{{ number_format($wallet->amount, 2) }}</td>
                                <td>{{ $wallet->amount_type }}</td>
                                <td>{{ $wallet->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $wallet->commission_amount ?? '' }}</td>
                                <td>
                                    @if ($wallet->is_redeemed == 0)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No repurchase users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="repurchaseapproveModal" tabindex="-1" aria-labelledby="repurchaseapproveModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('redeemRepurchase') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="repurchaseapproveModal">Confirm Repurchase Income</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to redeem?</p>
                        <input type="hidden" name="rankC_id" id="rankC_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
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
            const teamLink = $('.nav-link.repurchase');
            const treeviewLink = $('.nav.nav-treeview.repurchase');
            const mainLiLink = $('.nav-item.has-treeview.repurchase');
            const walletLink = $('.nav-link.repurchasewallet');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
