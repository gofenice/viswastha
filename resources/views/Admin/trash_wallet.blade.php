@extends('Admin.admin_header')
@section('title', 'vishwastha | Trash Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Trash Wallet</h1>
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
                            <h4>Trash Income Wallet</h4>
                            <p class="mb-0">
                                Total : {{ $trashWalletTotal }}
                            </p>
                        </div>
                        <div class="flex justify-around mt-2 pb-2">
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashWallets as $index => $trashWallet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $trashWallet->user->name ?? 'N/A' }}<br>{{ $trashWallet->user->connection ?? '' }}</td>
                                <td>{{ $trashWallet->amount }}</td>
                                <td>{{ $trashWallet->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Trash found.</td>
                            </tr>
                        @endforelse
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
            const teamLink = $('.nav-link.trash_wallet');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
