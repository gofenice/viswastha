@extends('Admin.admin_header')
@section('title', 'vishwastha   | Redeem Details')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Redeem Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Redeem Details</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Redeem Pin</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('redeem.pin') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="pin_id" class="col-sm-4 col-form-label">Pin ID</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pin_id" name="pin_id" required>
                            @error('pin_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="password" name="password" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Redeem Pin</button>
                </div>
            </form>
        </div>


        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pin From</th>
                            <th>Package Name</th>
                            <th>Unique Id</th>
                            <th>Password</th>
                            <th>Pin Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pins as $index => $pin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pin->user_name->name ?? 'N/A' }}
                                    <br>{{ $pin->user_name->connection ?? 'N/A' }}</br>
                                </td>
                                <td>{{ $pin->package->name ?? 'N/A' }}</td> <!-- Adjust relationship -->
                                <td>{{ $pin->unique_id }}</td>
                                <td>{{ $pin->password }}</td>
                                <td>{{ $pin->pin_amount }}</td>
                                <td>{{ $pin->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
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
         $(document).ready(function() {
            const pinLink = $('.nav-link.pin');
            const treeviewLink = $('.nav.nav-treeview.pin');
            const mainLiLink = $('.nav-item.has-treeview.pin');
            const redeemLink = $('.nav-link.redeem');
            if (redeemLink.length) {
                redeemLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
