@extends('Admin.admin_header')
@section('title', 'vishwastha   | Ranks')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $rank->rank_name }} Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('adminhome')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('rank_details')}}">Ranks</a></li>
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
                                    @if ($users->isNotEmpty())
                                        @foreach ($users as $key => $user)
                                            @php
                                                $profileImage = $user->user_image 
                                                    ? asset($user->user_image)
                                                    : '/assets/dist/img/images.jpg';
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><a data-toggle="modal" data-target="#listModal{{ $user->user_id }}">{{ $user->name }}</a></td>
                                                <td>{{ $user->connection }}</td>
                                                <td>
                                                    <img src="{{ $profileImage }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;" class="img-circle">
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="listModal{{ $user->user_id }}" tabindex="-1" aria-labelledby="listModal{{ $user->user_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="listModal{{ $user->user_id }}">Sponsored Users of {{ $user->name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($users->isNotEmpty())
                                                                <ul>
                                                                    @foreach ($users as $sponsored_user)
                                                                    <li>{{ $sponsored_user->name }} (id {{ $sponsored_user->id }}) </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>No sponsored users found.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No users found for the rank <b>{{ $rank->rank_name }}</b>.</td>
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
        $(document).ready(function() {
            const teamLink = $('.nav-link.team');
            const treeviewLink = $('.nav.nav-treeview.team');
            const mainLiLink = $('.nav-item.has-treeview.team');
            const binaryLink = $('.nav-link.rank_details');
            if (binaryLink.length) {
                binaryLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
</script>
@endsection
