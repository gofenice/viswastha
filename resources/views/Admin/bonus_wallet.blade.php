@extends('Admin.admin_header')
@section('title', 'vishwastha  | Special Incentive Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Special Incentive Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Special Incentive</li>
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
                            <h4>Special Incentive Wallet</h4>
                            <p class="mb-0">
                                Total : {{ $bonusWalletTotal }}
                            </p>
                            <p class="mb-0">
                                    Redeemed : {{ $bonusWalletInactive }}
                            </p>
                            <p>
                                Active : {{ $bonusWalletActive }}
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
                                Redeem to Premium Users
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
                            <th>Amount</th>
                            <th>Created</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonusWallets as $index => $bonusWallet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $bonusWallet->user->name ?? 'N/A' }}<br>{{ $bonusWallet->user->connection ?? '' }}
                                </td>
                                <td>{{ $bonusWallet->amount }}</td>
                                <td>{{ $bonusWallet->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if($bonusWallet->type == 0)
                                    <span class="badge badge-success">Credit</span>
                                    @else
                                    <span class="badge badge-danger">Transfer</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bonusWallet->is_redeemed == 0)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No amount found.</td>
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
                <form method="POST" action="{{ route('redeemBonusUsers') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyapproveModal">Confirm Premium income</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to redeem the amount to Premium users ?</p>
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
            const teamLink = $('.nav-link.bonus');
            const treeviewLink = $('.nav.nav-treeview.bonus');
            const mainLiLink = $('.nav-item.has-treeview.bonus');
            const walletLink = $('.nav-link.bonuswallet');
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
