@extends('Admin.admin_header')
@section('title', 'vishwastha   | Sponser')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sponsor List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Sponsor List </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL NO</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Join Date</th>
                            <th>Sponsored Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponsors as $key => $sponsor)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $sponsor->name }}<br>{{ $sponsor->connection }}</td>
                                <td>{{ $sponsor->phone_no }}</td>
                                <td>{{ $sponsor->created_at->format('d-m-Y') }}</td>
                                <td>{{ $sponsor->downlines->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        @foreach ($sponsors as $sponsor)
            @if ($sponsor->downlines->isNotEmpty())
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>{{ $sponsor->name }}'s Team</h3>
                        <table class="table table-bordered nested-table"  id="example1">
                            <thead class="bg-info">
                                <tr>
                                    <th>SL NO</th>
                                    <th>Name</th>
                                    <th>User ID</th>
                                    <th>Package Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sponsor->downlines as $index => $downline)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $downline->name }}</td>
                                        <td>{{ $downline->connection }}</td>
                                        <td>
                                            @if ($downline->userPackages->isNotEmpty())
                                                {{ $downline->userPackages->pluck('package.name')->join(', ') }}
                                            @else
                                                <span style="color: red;">No package available for this user</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach

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
            const sponsorLink = $('.nav-link.sponsor');
            const teamLink = $('.nav-link.team');
            const treeviewLink = $('.nav.nav-treeview.team');
            const mainLiLink = $('.nav-item.has-treeview.team');
            if (sponsorLink.length) {
                sponsorLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
