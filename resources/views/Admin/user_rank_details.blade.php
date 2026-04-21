@extends('Admin.admin_header')
@section('title', 'vishwastha  | Ranks')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Rank Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('rank_tree') }}">Ranks</a></li>
                            <li class="breadcrumb-item active">{{ $rank->rank_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Users in {{ $rank->rank_name }} Rank</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>ID</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($rankUsers) > 0)
                                            @foreach ($rankUsers as $key => $user)
                                                @php
                                                    // Check if 'image' key exists and is not null
                                                    $profileImage =
                                                        isset($user['user_image']) && $user['user_image']
                                                            ? asset($user['user_image'])
                                                            : asset('/assets/dist/img/images.jpg');
                                                @endphp
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $user['name'] }}</td>
                                                    <td>{{ $user['connection'] }}</td>
                                                    <td>
                                                        <img src="{{ $profileImage }}" alt="User Image"
                                                            style="width: 50px; height: 50px; object-fit: cover;"
                                                            class="img-circle">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No users found for the rank
                                                    <b>{{ $rank->rank_name }}</b>.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('footer')
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
    </script>
@endsection
