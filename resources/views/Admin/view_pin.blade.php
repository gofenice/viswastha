@extends('Admin.admin_header')
@section('title', 'vishwastha | View Pin')
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
                        <h1>View Allotted Pin</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">View Allotted Pin</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="row" style="margin: 0 auto; padding-left: 3rem; padding-right: 3rem; ">
            <!-- Left Side: Search Form -->
            <div class="col-md-6 mx-auto">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Search Pin</h3>
                    </div>
                    <form class="form-horizontal" action="{{ route('search_pin') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="fromDate" class="col-sm-4 col-form-label">From Date</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="fromDate" name="fromDate"
                                        value="{{ old('fromDate', request('fromDate')) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="toDate" class="col-sm-4 col-form-label">To Date</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="toDate" name="toDate"
                                        value="{{ old('toDate', request('toDate')) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="status" name="status">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All
                                        </option>
                                        <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>Unused
                                        </option>
                                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used
                                        </option>
                                        <option value="transferred"
                                            {{ request('status') == 'transferred' ? 'selected' : '' }}>
                                            Transferred</option>
                                        <option value="basic" {{ request('status') == 'basic' ? 'selected' : '' }}>
                                            Basic Pin</option>
                                        <option value="premium" {{ request('status') == 'premium' ? 'selected' : '' }}>
                                            Premium Pin</option>
                                    </select>
                                </div>
                            </div>
                            @if (Auth::check() && Auth::user()->role == 'superadmin')
                                <div class="form-group row">
                                    <label for="userid" class="col-sm-4 col-form-label">User ID</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="userid"
                                            value="{{ old('userid', request('userid')) }}" placeholder="Enter User ID">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info float-right">View Details</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side: Pin Summary (Visible to Super Admins) -->
            @if (Auth::check() && Auth::user()->role == 'superadmin')
                <div class="col-md-4">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Pin Summary</h3>
                        </div>
                        {{-- <div class="card-body"> --}}
                            <table class="table">
                                <tr><th>Basic Pins</th><td>{{ $basicpincount ?? 0 }}</td></tr>
                                <tr><th>Premium Pins</th><td>{{ $prepincount ?? 0 }}</td></tr>
                                <tr class="bg-primary"><th>Total Pins</th><td>{{ $totalPins ?? 0 }}</td></tr>
                                <tr><th>Used Basic Pins</th><td>{{ $basicpincountused ?? 0 }}</td></tr>
                                <tr><th>Used Premium Pins</th><td>{{ $prepincountused ?? 0 }}</td></tr>
                                <tr class="bg-danger"><th>Used Total Pins</th><td> {{ $inactivePins ?? 0 }}</td></tr>
                                <tr class="bg-success"><th>Available Pins</th><td>{{ $activePins ?? 0 }}</td></tr>
                            </table>
                        {{-- </div> --}}
                    </div>
                </div>
            @endif
        </div>
        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <div class="mt-2 mr-4">
                <button type="button" class="btn btn-default float-right bg-primary" data-toggle="modal"
                    data-target="#modal-lg">
                    Bulk Transfer
                </button>
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
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pins as $index => $pin)
                            <tr
                                class="{{ in_array($pin->pindetail->used, ['1', '2']) ? 'bg-danger' : '' }}@if (
                                    ($pin->pindetail->used == 0 && $pin->pindetail->status == 'redeemed') ||
                                        ($pin->pindetail->used == 0 && $pin->pindetail->status == 'transferred')) bg-light-danger @endif">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pin->fromUser->name ?? ($pin->toUser->name ?? '') }}
                                    <br>{{ $pin->fromUser->connection ?? ($pin->toUser->connection ?? '') }}
                                </td>
                                <td>{{ $pin->pindetail->package->name ?? 'N/A' }}
                                    {{-- </br>{{ $pin->pindetail->pin_amount ?? '' }} --}}
                                </td>
                                <!-- Adjust relationship -->
                                <td>{{ $pin->pindetail->unique_id }}</td>
                                <td>
                                    @if (empty($pin->pindetail->password))
                                        <span class="bg-danger p-2">Used</span>
                                    @else
                                        {{ $pin->pindetail->password }}
                                    @endif
                                </td>
                                <td>
                                    @if ($pin->from_user_id)
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
                                    @elseif($pin->from_user_id == null && $pin->status == 'pending')
                                        N/A
                                    @else
                                        Check the Details
                                    @endif
                                </td>

                                <td>
                                    @if ($pin->pindetail->status == 'pending')
                                        Active
                                    @elseif ($pin->pindetail->status == 'transferred')
                                        Transferred
                                    @elseif ($pin->pindetail->status == 'redeemed')
                                        Redeemed
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if ($pin->status != 'pending')
                                        <form id="pinDetailsForm" action="{{ route('pin.details') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $pin->to_user_id }}">
                                            <input type="hidden" name="pin_id" value="{{ $pin->pin_id }}">

                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-arrow-circle-right ml-2"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y') }}
                                    @endif
                                </td>
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

    <div class="modal fade" id="updatepinModal" tabindex="-1" aria-labelledby="updatePinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updatePinForm" method="POST" action="{{ route('redeem_pin_parent') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="updatePinModalLabel">Transfer to User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="pinId" name="id">
                            <label for="unique_id">Pin No</label>
                            <input type="text" class="form-control" id="unique_id" name="name" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="number" class="form-control" id="password" name="password" required readonly>
                        </div>
                        <div class="form-group">
                            <label>User Id</label>
                            <input type="text" class="form-control" id="userid" name="userid"
                                oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" id="name" name="name" required readonly>
                            <input type="hidden" class="form-control" id="user_id" name="user_id" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Remove the Pin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('unassignpin') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="un_pinid" name="un_pinid">
                        <p>Are you sure you want to unassign this pin?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-light">Unassign</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Bulk Transfer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulkTransferForm" action="{{ route('bulk.transfer') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="user_id">User ID</label>
                                <input type="text" class="form-control" id="transferuserid" name="transferuserid"
                                    oninput="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>User Name</label>
                                <input type="text" class="form-control" id="transfername" name="transfername"
                                    required readonly>
                                <input type="hidden" class="form-control" id="transferuser_id" name="transferuser_id"
                                    required>
                            </div>
                        </div>
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info">
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>#</th>
                                    <th>Unique Id</th>
                                    <th>Amount</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($avaliblepins as $index => $avaliblepin)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_pins[]" value="{{ $avaliblepin->id }}"
                                                class="pinCheckbox">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $avaliblepin->unique_id ?? '' }}</td>
                                        <td>{{ $avaliblepin->pin_amount }}</td>
                                        <td>{{ $avaliblepin->password }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="detail-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transfer Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="details" class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info">
                                <th>From User</th>
                                <th>To User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be dynamically injected here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
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

        function updatepin(id, name, password, status) {
            var pinId = $('#updatepinModal').find('#pinId');
            pinId.val(id);
            var pinNameField = $('#updatepinModal').find('#unique_id');
            pinNameField.val(name);
            var pinpasswordField = $('#updatepinModal').find('#password');
            pinpasswordField.val(password);
            $('#updatePinForm .error-message').text("");
            $('#updatepinModal').modal('toggle');
        }

        function unassignpin(id) {
            var pinId = $('#modal-danger').find('#un_pinid');
            pinId.val(id);
        }

        $('#userid').on('change', function() {
            var userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: '{{ route('getUserName') }}', // Update with your route name
                    method: 'GET',
                    data: {
                        userid: userId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#name').val(response.name); // Set the user name
                            $('#user_id').val(response.user_id); // Set the user name
                        } else {
                            $('#name').val(''); // Clear the name field if no user found
                            $('#user_id').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User not found",
                            });
                        }
                    },
                    error: function() {
                        $('#name').val(''); // Clear the name field on error
                        $('#user_id').val('');
                        alert('Failed to fetch user details. Please try again.');
                    }
                });
            } else {
                $('#name').val(''); // Clear the name field if the user ID is empty
            }
        });
        $('#transferuserid').on('change', function() {
            var userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: '{{ route('getUserName') }}', // Update with your route name
                    method: 'GET',
                    data: {
                        userid: userId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#transfername').val(response.name); // Set the user name
                            $('#transferuser_id').val(response.user_id); // Set the user name
                        } else {
                            $('#transfername').val(''); // Clear the name field if no user found
                            $('#transferuser_id').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User not found",
                            });
                        }
                    },
                    error: function() {
                        $('#transfername').val(''); // Clear the name field on error
                        $('#transferuser_id').val('');
                        alert('Failed to fetch user details. Please try again.');
                    }
                });
            } else {
                $('#name').val(''); // Clear the name field if the user ID is empty
            }
        });

        $(document).ready(function() {
            const pinLink = $('.nav-link.pin');
            const treeviewLink = $('.nav.nav-treeview.pin');
            const mainLiLink = $('.nav-item.has-treeview.pin');
            const viewpinLink = $('.nav-link.viewpin');
            if (viewpinLink.length) {
                viewpinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

        document.getElementById('selectAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.pinCheckbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
