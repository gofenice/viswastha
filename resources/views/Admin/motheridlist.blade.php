@extends('Admin.admin_header')
@section('title', 'vishwastha   | Child Id')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        {{-- <h1>ID List</h1> --}}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Child Id List </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="card mt-3">
            <div class="card-body">
          <h2>Mother ID</h2>
                <table class="table text-center">
                    <thead>
                        <tr class="bg-primary">
                            <th>Name</th>
                            <th>Mother ID</th>
                            <th>income</th>
                            <th>Join Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                         @if($user->mother_id == 1)
                            <tr class=""  >
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->connection }}</td>
                                <td>{{ $user->total_income }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($loggedInUser->mother_id == 1)
                                        <form action="{{ route('admin.addincome', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Income to Mother ID</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>




        <div class="card mt-3">
            <div class="card-body">
          <h3>Child ID List</h3>
                <table id="motherid" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>SL NO</th>
                            <th>Name</th>
                            <th>Child ID</th>
                            <th>income</th>
                            <th>Join Date</th>
                            {{-- <th></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                        @if($user->mother_id != 1)
                            <tr >
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->connection }}</td>
                                <td>{{ $user->total_income }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                                {{-- <td>
                                    @if ($user->mother_id != 1 && $user->total_income != 0 )
                                        <form action="{{ route('admin.addincome', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Income to Mother ID</button>
                                        </form>
                                    @endif
                                </td> --}}
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
          @if (session()->has('error'))
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Error!",
                text:  "{{ session()->get('error') }}",
                showConfirmButton: true,
                confirmButtonText: "OK"
            });
        @endif
        @if (session()->has('success'))
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
        $(function() {
            $("#motherid").DataTable();
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
            const motherLink = $('.nav-link.mother');
            const motherviewLink = $('.nav.nav-treeview.mother');
            const mainLink = $('.nav-item.has-treeview.mother');
            const motherIdLink = $('.nav-link.mother_id');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
