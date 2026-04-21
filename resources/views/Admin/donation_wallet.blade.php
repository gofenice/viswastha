@extends('Admin.admin_header')
@section('title', 'vishwastha  | Donation Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Donation Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Donation</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6 mx-auto">
                    <div class="small-box bg-primary" style=" color: black;">
                        <div class="inner">
                            <h4>Donation Wallet</h4>
                            <p class="mb-0"> Total : {{ $donationwallet }}</p>
                            <p class="mb-0"> Redeemed : {{ $donationRedeemedwallet }}</p>
                            <p class="mb-0"> Active : {{ $donationCurrentwallet }}</p>

                        </div>
                        <div class="icon">
                            {{-- <i>{{ $rank['icon'] }}</i> --}}
                        </div>
                        <div class="flex justify-around mt-2 pb-2">

                            <!-- Redeemed to wallet Button -->
                            <a href="#" class="px-1 py-2 "
                                style="color: black;justify-content: center;display: flex;align-items: center;"
                                data-toggle="modal" data-target="#donationTransferModal">
                                Transfer to Wallet
                                <i class="fas fa-arrow-circle-right mr-2 ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Donation List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Donated Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donationlist as $index => $list)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $list->user->name ?? 'N/A' }}<br>{{ $list->user->connection ?? '' }}
                                </td>
                                <td>{{ $list->amount }}</td>
                                <td>{{ $list->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No donations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donationTransferModal" tabindex="-1" aria-labelledby="donationTransferModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('donationTransfer') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="donationTransferModal">Confirm Donation Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to transfer the amount to Wallet ?</p>
                        <input type="hidden" name="userId" id="user_id" value="{{ auth()->id() }}">
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
            const teamLink = $('.nav-link.donationwall');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
