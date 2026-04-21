@extends('Admin.admin_header')
@section('title', 'vishwastha  | Special Incentive Users')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Special Incentive users</h1>
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

        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Premium User List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Package</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonususers as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->user->name ?? 'N/A' }}<br>{{ $user->user->connection ?? '' }}</td>
                                <td>{{ $user->package->name }}</td>
                                <td>{{ $user->created_at->format('d-m-Y') }}</td>
                        
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
            const teamLink = $('.nav-link.bonus');
            const treeviewLink = $('.nav.nav-treeview.bonus');
            const mainLiLink = $('.nav-item.has-treeview.bonus');
            const walletLink = $('.nav-link.bonususer');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

    </script>
@endsection
