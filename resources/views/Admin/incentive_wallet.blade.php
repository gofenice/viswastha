@extends('Admin.admin_header')
@section('title', 'vishwastha | Incentive Users')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Incentive Wallet</h1>
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
                            <h4>Incentive Income Wallet</h4>
                            <p class="mb-0">
                                Total : {{ $incentiveWalletTotal }}
                            </p>
                            <p class="mb-0">
                                <a href="{{ route('incentiveUsersAmtList') }}" class="small-box-footer"
                                    style="color: black;">
                                    Redeemed : {{ $incentiveWalletInactive }}
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </p>
                            <p>
                                Active : {{ $incentiveWalletActive }}
                            </p>

                        </div>
                        <div class="icon">
                            {{-- <i>{{ $rank['icon'] }}</i> --}}
                        </div>
                        <div class="flex justify-around mt-2 pb-2">

                            <!-- Redeemed to Company Button -->
                            <a href="#" class="px-1 py-2 "
                                style="color: black;justify-content: center;display: flex;align-items: center;"
                                data-toggle="modal" data-target="#companyapproveModal">
                                Redeem to Incentive Users
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
                            <th>User</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incentiveWallets as $index => $incentiveWallet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $incentiveWallet->user->name ?? 'N/A' }}<br>{{ $incentiveWallet->user->connection ?? '' }}
                                </td>
                                <td>{{ $incentiveWallet->package->name ?? 'By Admin' }}</td>
                                <td>{{ $incentiveWallet->amount }}</td>
                                <td>{{ $incentiveWallet->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if ($incentiveWallet->is_redeemed == 0)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No Incentive users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="companyapproveModal" tabindex="-1" aria-labelledby="companyapproveModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('redeemIncentiveUsers') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyapproveModal">Confirm Incentive income</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to redeem the amount to Incentive users ?</p>
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
            const teamLink = $('.nav-link.incentive');
            const treeviewLink = $('.nav.nav-treeview.incentive');
            const mainLiLink = $('.nav-item.has-treeview.incentive');
            const walletLink = $('.nav-link.incentivewallet');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

        $(document).ready(function() {
            $('#userId').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: $('#getUserId').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token for security
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            userId: userId
                        }),
                        success: function(response) {
                            if (response.name) {
                                $('#userName').val(response.name);
                            }
                        },
                        error: function() {
                            $('#userName').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User Not Found",
                            });
                        }
                    });
                } else {
                    $('#userName').val(''); // Clear the field if userId is empty
                }
            });
        });
    </script>
@endsection
