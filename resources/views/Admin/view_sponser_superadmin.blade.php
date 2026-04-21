@extends('Admin.admin_header')
@section('title', 'vishwastha | Sponsor List')
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL NO</th>
                            <th>Name</th>
                            <th>Sponsor Packages</th>
                            {{-- <th>Email</th> --}}
                            {{-- <th>Phone</th> --}}
                            {{-- <th>Join Date</th> --}}
                            <th>Sponsored Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponsors as $key => $sponsor)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $sponsor->name }}<br>
                                    {{ $sponsor->connection }}<br>
                                    {{ $sponsor->created_at->format('d-m-Y')  }}
                                </td>
                                {{-- Sponsor Packages --}}
                                <td>
                                    @if ($sponsor->userPackages->isNotEmpty())
                                        <ul>
                                            @foreach ($sponsor->userPackages as $userPackage)
                                                <li>
                                                    {{ $userPackage->package->name ?? 'N/A' }}
                                                    (Created: {{ $userPackage->created_at->format('d-m-Y') }})
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>No Packages</span>
                                    @endif
                                </td>
                                {{-- <td>{{ $sponsor->phone_no }}</td> --}}
                                {{-- <td>{{ $sponsor->created_at->format('d-m-Y') }}</td> --}}
                                <td>
                                    <ul>
                                        @foreach ($sponsor->downlines as $downline)
                                            <li>
                                                {{ $downline->name }} ({{ $downline->connection }}) -
                                                {{ $downline->created_at->format('d-m-Y') }}
                                                <br>
                                                <strong>Packages:</strong>
                                                @if ($downline->userPackages->isNotEmpty())
                                                    <ul>
                                                        @foreach ($downline->userPackages as $userPackage)
                                                            <li>
                                                                {{ $userPackage->package->name ?? 'N/A' }}
                                                                (Created: {{ $userPackage->created_at->format('d-m-Y') }})
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span>No Packages</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.company_rank');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
