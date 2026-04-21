@extends('Admin.admin_header')
@section('title', 'vishwastha  | Royalty Users')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Royalty Users Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('royalty_wallet') }}">Royalty wallet</a></li>
                            <li class="breadcrumb-item active">Wallet</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        
        <div class="card recieptList col-md-11 mx-auto">
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
                        @forelse($royaltyUsersAmtList as $index => $royaltyUsersAmt)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $royaltyUsersAmt->user->name ?? 'N/A' }}<br>{{ $royaltyUsersAmt->user->connection ?? '' }}
                                </td>
                                <td>{{ $royaltyUsersAmt->amount }}</td>
                                <td>{{ $royaltyUsersAmt->created_at->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No royalty users found.</td>
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
            const teamLink = $('.nav-link.royalty');
            const treeviewLink = $('.nav.nav-treeview.royalty');
            const mainLiLink = $('.nav-item.has-treeview.royalty');
            const walletLink = $('.nav-link.royaltyUsersAmtList');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

     
    </script>
@endsection
