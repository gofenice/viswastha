@extends('Admin.admin_header')
@section('title', 'vishwastha | User List')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">User List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Account Type</th>
                            <th width="">PAN Card</th>
                            <th width="17%">Details</th>
                            <th>Packages</th>
                            <th width="12%">Dates</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr class="">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}<br> ( {{ $user->connection }} )
                                    <br>
                                    @if ($user->position == 'changed')
                                        <span class="badge badge-primary">Position changed</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$user->pan_card_no || strtoupper($user->pan_card_no) === 'STORE')
                                        <span class="badge badge-warning" style="font-size:12px;">No PAN Card</span>
                                    @elseif($user->mother_id == 1)
                                        <span class="badge badge-success" style="font-size:12px;">Mother ID</span>
                                        <br><small class="text-muted">Cannot change</small>
                                    @elseif($user->mother_id == 2)
                                        <span class="badge badge-primary acct-type-badge" id="badge-{{ $user->id }}" style="font-size:12px;">Privilege 1</span>
                                        <br>
                                        <button class="btn btn-xs btn-outline-warning mt-1"
                                                onclick="changeAcctType({{ $user->id }}, 'Privilege 1')">
                                            <i class="fas fa-exchange-alt"></i> Change
                                        </button>
                                    @elseif($user->mother_id == 3)
                                        <span class="badge badge-primary acct-type-badge" id="badge-{{ $user->id }}" style="font-size:12px;">Privilege 2</span>
                                        <br>
                                        <button class="btn btn-xs btn-outline-warning mt-1"
                                                onclick="changeAcctType({{ $user->id }}, 'Privilege 2')">
                                            <i class="fas fa-exchange-alt"></i> Change
                                        </button>
                                    @else
                                        <span class="badge badge-secondary acct-type-badge" id="badge-{{ $user->id }}" style="font-size:12px;">Child ID</span>
                                        <br><small class="text-danger">No pair income</small>
                                    @endif
                                </td>
                                <td>{{ strtoupper($user->pan_card_no ?? '') === 'STORE' ? '—' : ($user->pan_card_no ?? '—') }}</td>
                                <td>
                                    {{-- <b> Pancard :</b> {{ $user->pan_card_no }}<br> --}}
                                    <b> Phone no :</b> {{ $user->phone_no }}<br>
                                    <b> Pincode :</b> {{ $user->pincode }}
                                </td>
                                {{-- <td>{{ $user->pincode }}</td>
                                <td>{{ $user->phone_no }}</td> --}}
                                <td>
                                    {{-- @if ($user->userPackages->isNotEmpty())
                                        {{ $user->userPackages->pluck('package.name')->join(', ') }}
                                        {{ $user->userPackages->created_at }}
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif --}}
                                    @if ($user->userPackages->isNotEmpty())
                                        @foreach ($user->userPackages as $package)
                                            {{ $package->package->name }} <br>
                                            ({{ $package->created_at->format('d-m-Y') }})
                                            <br>
                                        @endforeach
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                    <br>
                                    @if ($user->is_pair_matched == 1)
                                        <span class="badge badge-primary">The child sponsorship has been changed.</span>
                                    @endif
                                </td>
                                <td>Reg-{{ $user->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if ($user->userPackages->isNotEmpty())
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lg"
                                            onclick="useredit('{{ $user->id }}')">
                                            <i class="fas fa-edit"> User</i>
                                        </button>
                                    @else
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lg"
                                            onclick="useredit('{{ $user->id }}')">
                                            <i class="fas fa-edit"> User</i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser('{{ $user->id }}')">
                                            <i class="fas fa-trash"> Delete</i>
                                        </button>
                                    @endif
                                    <br>
                                    @if ($user->sponsor_id)
                                        <button class="btn btn-primary btn-sm mt-2"
                                            onclick="changesponsor({{ $user->id }})">
                                            <i class="fas fa-edit">Change Sponsor</i>
                                        </button>
                                    @endif
                                    <button class="btn btn-secondary btn-sm mt-2" data-toggle="modal"
                                        data-target="#modal-change-password" onclick="changePassword({{ $user->id }})">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" data-toggle="modal"
                                        data-target="#modal-trashMoney"
                                        onclick="trashMoney({{ $user->id }},{{ $user->total_income }})">
                                        <i class="fas fa-trash"></i> Trashing
                                    </button>
                                    <button class="btn btn-success btn-sm mt-2"
                                        onclick="window.location='{{ route('admin.login-as-user', $user->id) }}'">
                                        <i class="fas fa-sign-in-alt"></i> Login as User
                                    </button>
                                </td>
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

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="user-form" action="{{ route('userUpdate') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Update User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger"> *</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            <input type="hidden" name="id" id="id" class="form-control"
                                value="{{ old('id') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pan_card_no" class="form-label">PAN Card Number <span class="text-danger">
                                    *</span></label>
                            <input type="text" name="pan_card_no" id="pan_card_no" class="form-control"
                                oninput="this.value = this.value.toUpperCase()" value="{{ old('pan_card_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email" class="form-label">Email <span class="text-danger"> *</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no" class="form-label">Phone Number <span class="text-danger">
                                    *</span></label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control"
                                value="{{ old('phone_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="pincode" class="form-label">Pin Code <span class="text-danger"> *</span></label>
                            <input type="text" name="pincode" id="pincode" class="form-control"
                                value="{{ old('pincode') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address" class="form-label">Address <span class="text-danger"> *</span></label>
                            <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                            <span class="error-message text-danger"></span>
                        </div>
                        <input type="hidden" id="_original_name" name="_original_name">
                        <input type="hidden" id="_original_pan" name="_original_pan">
                        <input type="hidden" id="_mother_id" name="_mother_id">
                        <input type="hidden" id="new_mother_id" name="new_mother_id">

                        {{-- Inline Mother ID picker (shown only when needed) --}}
                        <div id="mother-picker-section" class="col-md-12 mt-2" style="display:none;">
                            <div class="alert alert-warning py-2">
                                <p id="mother-picker-info" class="mb-2 small"></p>
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Who becomes the new Mother ID for the old PAN group? <span class="text-danger">*</span></label>
                                    <select id="mother-picker-select" class="form-control mt-1"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-lg-sponsor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="sponsor-form" action="{{ route('change_sponsor') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Change Sponsor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label for="sponsorId" class="form-label">Current Sponsor Id</label>
                            <input type="text" name="sponsorId" id="sponsorId" class="form-control" readonly>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sponsorName" class="form-label">Current Sponsor Name</label>
                            <input type="text" name="sponsorName" id="sponsorName" class="form-control" readonly>
                            <input type="hidden" name="user_id" id="user_id" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sponsor_id" class="form-label">New Sponsor Id <span class="text-danger">
                                    *</span></label>
                            <input type="text" name="sponsor_id" id="sponsor_id"
                                oninput="this.value = this.value.toUpperCase()" placeholder="Enter new sponsor Id"
                                class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sponsor_name" class="form-label">New Sponsor Name</label>
                            <input type="text" name="sponsor_name" id="sponsor_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Change Sponsor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-change-password">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="password-form" method="POST" action="{{ route('adminchangePassword') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Change Password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="change_password_user_id">
                        <div class="form-group">
                            <label for="new_password">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="new_password_confirmation" id="confirm_password"
                                class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-trashMoney">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="trashMoney-form" method="POST" action="{{ route('adminctrashMoney') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Trashing</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="userid">
                        <div class="form-group">
                            <label for="">Current Wallet Balance <span class="text-danger">*</span></label>
                            <input type="text" name="currentamt" id="currentamt" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="trahing_amount">Trashing Amount <span class="text-danger">*</span></label>
                            <input type="number" name="trahing_amount" id="trahing_amount" class="form-control"
                                required>
                            <span class="error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

{{-- @section('footer')
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
        function useredit(id) {
            $.ajax({
                url: '{{ route('getUserDetails', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const user = response.data;
                        $('#id').val(user.id);
                        $('#name').val(user.name);
                        $('#pan_card_no').val(user.pan_card_no && user.pan_card_no.toUpperCase() !== 'STORE' ? user.pan_card_no : '');
                        $('#email').val(user.email);
                        $('#phone_no').val(user.phone_no);
                        $('#pincode').val(user.pincode);
                        $('#address').val(user.address);
                        $('#modal-lg').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while fetching user details.', 'error');
                }
            });
        }

        function changesponsor(id) {
            $.ajax({
                url: '{{ route('getUserSponsor', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const user = response.data;
                        $('#user_id').val(user.user_id);
                        $('#sponsorName').val(user.sponsorName);
                        $('#sponsorId').val(user.sponsorId);
                        $('#modal-lg-sponsor').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while fetching user details.', 'error');
                }
            });
        }

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
            const teamLink = $('.nav-link.userlist');
            if (teamLink.length) {
                teamLink.addClass('active');
            }



            $('#user-form').ajaxForm({
                beforeSubmit: function(formData, jqForm, options) {
                    $('#user-form button[type="submit"]').prop('disabled', true);
                },
                success: function(responseText, statusText, xhr, $form) {
                    const data = JSON.parse(responseText);
                    // console.log(data);
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    $('#user-form .error-message').text("");
                    if (data.status == "validation") {
                        $.each(data.errors, function(key, val) {
                            $('[name="' + key + '"]').closest('.form-group').find(
                                '.error-message').text(val);
                        })
                    } else if (data.status == "success") {
                        $form[0].reset();
                        window.location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    console.error(error, xhr, status);
                }
            });


            $("input[name='sponsor_id']").on("blur", function() {
                let sponsorId = $(this).val();
                if (sponsorId) {
                    $.ajax({
                        url: "/fetch-sponsor-name",
                        type: "POST",
                        data: {
                            sponsor_id: sponsorId,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            if (response.success) {
                                $("input[name='sponsor_name']").val(response.name);
                            } else {
                                $("input[name='sponsor_name']").val("Sponsor not found.");
                            }
                        },
                        error: function() {
                            $("input[name='sponsor_name']").val("Error fetching sponsor.");
                        }
                    });
                } else {
                    $("input[name='sponsor_name']").val("");
                }
            });

        });

        function deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('deleteUser', ':id') }}'.replace(':id', id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // CSRF token for Laravel security
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire("Deleted!", response.message, "success").then(() => {
                                    location.reload(); // Reload the page after deletion
                                });
                            } else {
                                Swal.fire("Error", response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "An error occurred while deleting the user.", "error");
                        }
                    });
                }
            });
        }

        function changePassword(userId) {
            $('#change_password_user_id').val(userId);
            $('#new_password').val('');
            $('#confirm_password').val('');
            $('#modal-change-password').modal('show');
        }
        function trashMoney(userId,currentamt) {
            $('#userid').val(userId);
            $('#currentamt').val(currentamt);
            $('#modal-trashMoney').modal('show');
        }
    </script>
@endsection --}}

@section('footer')

    <!-- Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script>
        // DataTable Initialization with PDF Export
        $(document).ready(function() {
            const now = new Date();

            // Format date
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();

            // Format time to 12-hour with AM/PM
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert to 12-hour format
            const formattedTime = String(hours).padStart(2, '0') + '-' + minutes + ampm;

            const formattedDateTime = `${day}-${month}-${year}_${formattedTime}`;
            const filename = `UserList_${formattedDateTime}`;

            $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'btn btn-sm btn-success mb-2',
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        title: filename,
                        filename: filename,
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },

                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm btn-danger mb-2',
                        text: '<i class="fas fa-file-pdf"></i> Export PDF',
                        title: filename,
                        filename: filename,
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                ]
            });

            // Activate sidebar menu
            $('.nav-link.userlist').addClass('active');

            // Sponsor name autofill on blur
            $("input[name='sponsor_id']").on("blur", function() {
                let sponsorId = $(this).val();
                if (sponsorId) {
                    $.ajax({
                        url: "/fetch-sponsor-name",
                        type: "POST",
                        data: {
                            sponsor_id: sponsorId,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            $("input[name='sponsor_name']").val(response.success ? response
                                .name : "Sponsor not found.");
                        },
                        error: function() {
                            $("input[name='sponsor_name']").val("Error fetching sponsor.");
                        }
                    });
                } else {
                    $("input[name='sponsor_name']").val("");
                }
            });

            // Reset picker section when modal is closed
            $('#modal-lg').on('hidden.bs.modal', function () {
                $('#mother-picker-section').hide();
                $('#mother-picker-select').empty();
                $('#new_mother_id').val('');
                $('#btn-update').text('Update');
            });

            // AJAX form submit with Mother ID change interception
            $('#user-form').on('submit', function(e) {
                e.preventDefault();

                var motherId     = parseInt($('#_mother_id').val()) || 0;
                var originalName = ($('#_original_name').val() || '').trim().toLowerCase();
                var originalPan  = ($('#_original_pan').val() || '').trim().toUpperCase();
                var newName      = $('#name').val().trim().toLowerCase();
                var newPan       = $('#pan_card_no').val().trim().toUpperCase();
                var pickerVisible = $('#mother-picker-section').is(':visible');

                // If picker is visible, validate selection then save
                if (pickerVisible) {
                    if (!$('#mother-picker-select').val()) {
                        Swal.fire('Required', 'Please select a new Mother ID for the old PAN group.', 'warning');
                        return;
                    }
                    $('#new_mother_id').val($('#mother-picker-select').val());
                    submitUserForm();
                    return;
                }

                if (motherId === 1 && (newName !== originalName || newPan !== originalPan)) {
                    $.getJSON('{{ route("admin.user.check_mother_id_change") }}', {
                        user_id:  $('#id').val(),
                        new_name: $('#name').val().trim(),
                        new_pan:  newPan,
                    }, function(res) {
                        if (res.case === 1 || res.case === 'blocked') {
                            Swal.fire('Not Allowed', res.message, 'error');
                        } else if (res.case === 2 || res.case === 3) {
                            if (res.old_pan_children && res.old_pan_children.length > 0) {
                                var info = res.case === 2
                                    ? 'The existing Child ID <strong>' + res.existing_child.connection + ' — ' + res.existing_child.name + '</strong> will stay under the new PAN group. Select who takes over as Mother ID for the old PAN:'
                                    : 'This is a fresh name &amp; PAN combination. Select who takes over as Mother ID for the old PAN group:';
                                $('#mother-picker-info').html(info);

                                // Show only the nearest (lowest ID) member as the single auto-selected option
                                var $sel = $('#mother-picker-select').empty();
                                function acctLabel(m) { return m == 2 ? 'Privilege 1' : m == 3 ? 'Privilege 2' : m == 1 ? 'Mother ID' : 'Child ID'; }
                                var nearest = res.old_pan_children[0];
                                if (nearest) {
                                    var label = nearest.connection + ' — ' + nearest.name + ' (' + acctLabel(nearest.mother_id) + ')';
                                    $sel.append('<option value="' + nearest.id + '">' + label + '</option>');
                                }

                                $('#mother-picker-section').show();
                                $('#btn-update').text('Confirm & Update');
                            } else {
                                submitUserForm();
                            }
                        } else {
                            submitUserForm();
                        }
                    }).fail(function() {
                        Swal.fire('Error', 'Failed to validate the change. Please try again.', 'error');
                    });
                } else {
                    submitUserForm();
                }
            });

            function submitUserForm() {
                $('#btn-update').prop('disabled', true);
                $.post('{{ route("userUpdate") }}', $('#user-form').serialize(), function(responseText) {
                    var data = typeof responseText === 'string' ? JSON.parse(responseText) : responseText;
                    $('#btn-update').prop('disabled', false);
                    $('.error-message').text('');

                    if (data.status === 'validation') {
                        $.each(data.errors, function(key, val) {
                            $('[name="' + key + '"]').closest('.form-group').find('.error-message').text(val);
                        });
                    } else if (data.status === 'success') {
                        $('#user-form')[0].reset();
                        Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 1500, showConfirmButton: false })
                            .then(() => window.location.reload());
                    } else if (data.status === 'error') {
                        Swal.fire('Error', data.message, 'error');
                        $('#mother-picker-section').hide();
                        $('#btn-update').text('Update');
                    }
                }).fail(function() {
                    $('#btn-update').prop('disabled', false);
                    Swal.fire('Error', 'Submission failed. Please try again.', 'error');
                });
            }
        });

        // Load user data into edit modal
        function useredit(id) {
            $.ajax({
                url: '{{ route('getUserDetails', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const user = response.data;
                        var pan = user.pan_card_no && user.pan_card_no.toUpperCase() !== 'STORE' ? user.pan_card_no : '';
                        $('#id').val(user.id);
                        $('#name').val(user.name);
                        $('#pan_card_no').val(pan);
                        $('#email').val(user.email);
                        $('#phone_no').val(user.phone_no);
                        $('#pincode').val(user.pincode);
                        $('#address').val(user.address);
                        // Store originals for Mother ID change detection
                        $('#_original_name').val(user.name);
                        $('#_original_pan').val(pan);
                        $('#_mother_id').val(user.mother_id || 0);
                        $('#new_mother_id').val('');
                        $('#modal-lg').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while fetching user details.', 'error');
                }
            });
        }

        // Load sponsor data into modal
        function changesponsor(id) {
            $.ajax({
                url: '{{ route('getUserSponsor', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const user = response.data;
                        $('#user_id').val(user.user_id);
                        $('#sponsorName').val(user.sponsorName);
                        $('#sponsorId').val(user.sponsorId);
                        $('#modal-lg-sponsor').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while fetching sponsor details.', 'error');
                }
            });
        }

        // Delete user confirmation and action
        function deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('deleteUser', ':id') }}'.replace(':id', id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire("Deleted!", response.message, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error", response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "An error occurred while deleting the user.", "error");
                        }
                    });
                }
            });
        }

        // Show change password modal
        function changePassword(userId) {
            $('#change_password_user_id').val(userId);
            $('#new_password').val('');
            $('#confirm_password').val('');
            $('#modal-change-password').modal('show');
        }

        // Show trash money modal
        function trashMoney(userId, currentamt) {
            $('#userid').val(userId);
            $('#currentamt').val(currentamt);
            $('#modal-trashMoney').modal('show');
        }
    </script>

    <script>
    function changeAcctType(userId, currentLabel) {
        const isPrivilege = currentLabel.includes('Privilege');

        // Privilege → fetch children for swap
        fetch('{{ url("/admin/user") }}/' + userId + '/children-by-pan')
            .then(r => r.json())
            .then(data => {
                // No child IDs available to swap with
                if (!data.can_swap) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cannot Change',
                        html: data.has_others
                            ? `This user's other IDs are all <b>Mother / Privilege</b> accounts.<br>There is no Child ID available to swap with.`
                            : `No other IDs found for this user. There is no Child ID to swap with.`,
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const selectHtml = `<div class="mt-3 text-left">
                    <label class="font-weight-bold">Swap with a Child ID:</label>
                    <select id="swapChildSelect" class="form-control mt-1">
                        <option value="">— Just demote, no swap —</option>
                        ${data.children.map(c => `<option value="${c.id}">${c.connection} — ${c.name}</option>`).join('')}
                    </select>
                </div>`;

                Swal.fire({
                    title: 'Change Privilege to Child?',
                    html: `This will <b>demote ${currentLabel} to Child ID</b>.
                           <br>This affects binary pair income eligibility.
                           ${selectHtml}`,
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#6c757d', confirmButtonText: 'Yes, change', cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const sel = document.getElementById('swapChildSelect');
                        return sel ? sel.value : '';
                    }
                }).then(result => {
                    if (!result.isConfirmed) return;
                    doChangeAcct(userId, result.value || null);
                });
            });
    }

    function doChangeAcct(userId, swapWithId) {
        fetch('{{ route('admin.user.change_account_type') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ user_id: userId, swap_with_id: swapWithId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Done!', text: 'Account type updated.', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1600);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        });
    }
    </script>

    {{-- Session-based Alerts --}}
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
                text: "{{ session()->get('error') }}"
            });
        </script>
    @endif

@endsection
