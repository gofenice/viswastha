@extends('Admin.admin_header')
@section('title', 'vishwastha  | View Pin')
@section('content')
    <style>
        .bg-light-danger {
            background-color: #f8d7da !important;
            /* Light red background */
        }

        button.btn.btn-default {
            background: transparent;
            border: 0;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pin Transfer Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('view_pin') }}">View Pin</a></li>
                            <li class="breadcrumb-item active">Transfer Details</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
    
        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <div class="mt-2 mr-4">
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>From User</th>
                            <th>Package Name</th>
                            <th>Unique Id</th>
                            <th>Password</th>
                            <th>To User</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pins as $index => $pin)
                            <tr
                                class="{{ in_array($pin->used, ['1', '2']) ? 'bg-danger' : '' }}@if (($pin->used == 0 && $pin->status == 'redeemed') || ($pin->used == 0 && $pin->status == 'transferred')) bg-light-danger @endif">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pin->fromUser->name ?? $pin->toUser->name }}
                                    <br>{{ $pin->fromUser->connection ?? $pin->toUser->connection }}
                                </td>
                                <td>{{ $pin->pindetail->package->name ?? 'N/A' }}
                                    {{-- </br>{{ $pin->pindetail->pin_amount ?? '' }} --}}
                                </td>
                                <!-- Adjust relationship -->
                                <td>{{ $pin->pindetail->unique_id }}</td>
                                <td>{{ $pin->pindetail->password }}</td>
                                <td>
                                    @if ($pin->to_user_id)
                                        {{ $pin->toUser->name ?? 'N/A' }}
                                        ({{ $pin->toUser->connection ?? 'N/A' }})
                                        <br>

                                        @foreach ($pin->userPackages as $userPackage)
                                            @if ($pin->toUser->name !== ($userPackage->addedByUser->name ?? ''))
                                                {{ $userPackage->addedByUser->name ?? 'N/A' }}
                                                ({{ $userPackage->addedByUser->connection ?? '' }})
                                            @endif

                                            add pin to {{ $userPackage->user->name ?? 'N/A' }}
                                            ({{ $userPackage->user->connection ?? '' }})
                                        @endforeach

                                        {{-- @elseif ($pin->userPackages && $pin->userPackages->addedByUser)
                                    {{ $pin->userPackages->addedByUser->name ?? 'N/A' }}({{ $pin->userPackages->addedByUser->connection ?? 'N/A' }}) added the pin directly to
                                    {{ $pin->userPackages->user->name ?? 'N/A' }} ({{ $pin->userPackages->user->connection ?? '' }}) --}}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>
                                    @if ($pin->status == 'pending')
                                        Active
                                    @elseif ($pin->status == 'transferred')
                                        Transferred
                                    @elseif ($pin->status == 'redeemed')
                                        Redeemed
                                    @else
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No records found.</td>
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
                showConfirmButton: true,
                confirmButtonText: "OK"
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
            $('#details').DataTable({
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
