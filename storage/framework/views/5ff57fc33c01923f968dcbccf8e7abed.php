<?php $__env->startSection('title', 'vishwastha | User List'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('adminhome')); ?>">Home</a></li>
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
                            <th width="">PAN Card</th>
                            <th width="17%">Details</th>
                            <th>Packages</th>
                            <th width="12%">Dates</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="">
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($user->name); ?><br> ( <?php echo e($user->connection); ?> )
                                    <br>
                                    <?php if($user->position == 'changed'): ?>
                                        <span class="badge badge-primary">Position changed</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($user->pan_card_no); ?></td>
                                <td>
                                    
                                    <b> Phone no :</b> <?php echo e($user->phone_no); ?><br>
                                    <b> Pincode :</b> <?php echo e($user->pincode); ?>

                                </td>
                                
                                <td>
                                    
                                    <?php if($user->userPackages->isNotEmpty()): ?>
                                        <?php $__currentLoopData = $user->userPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($package->package->name); ?> <br>
                                            (<?php echo e($package->created_at->format('d-m-Y')); ?>)
                                            <br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                    <br>
                                    <?php if($user->is_pair_matched == 1): ?>
                                        <span class="badge badge-primary">The child sponsorship has been changed.</span>
                                    <?php endif; ?>
                                </td>
                                <td>Reg-<?php echo e($user->created_at->format('d-m-Y')); ?></td>
                                <td>
                                    <?php if($user->userPackages->isNotEmpty()): ?>
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lg"
                                            onclick="useredit('<?php echo e($user->id); ?>')">
                                            <i class="fas fa-edit"> User</i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-lg"
                                            onclick="useredit('<?php echo e($user->id); ?>')">
                                            <i class="fas fa-edit"> User</i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser('<?php echo e($user->id); ?>')">
                                            <i class="fas fa-trash"> Delete</i>
                                        </button>
                                    <?php endif; ?>
                                    <br>
                                    <?php if($user->sponsor_id): ?>
                                        <button class="btn btn-primary btn-sm mt-2"
                                            onclick="changesponsor(<?php echo e($user->id); ?>)">
                                            <i class="fas fa-edit">Change Sponsor</i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-secondary btn-sm mt-2" data-toggle="modal"
                                        data-target="#modal-change-password" onclick="changePassword(<?php echo e($user->id); ?>)">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" data-toggle="modal"
                                        data-target="#modal-trashMoney"
                                        onclick="trashMoney(<?php echo e($user->id); ?>,<?php echo e($user->total_income); ?>)">
                                        <i class="fas fa-trash"></i> Trashing
                                    </button>
                                    <button class="btn btn-success btn-sm mt-2"
                                        onclick="window.location='<?php echo e(route('admin.login-as-user', $user->id)); ?>'">
                                        <i class="fas fa-sign-in-alt"></i> Login as User
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="user-form" action="<?php echo e(route('userUpdate')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                                value="<?php echo e(old('name')); ?>" required>
                            <input type="hidden" name="id" id="id" class="form-control"
                                value="<?php echo e(old('id')); ?>" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pan_card_no" class="form-label">PAN Card Number <span class="text-danger">
                                    *</span></label>
                            <input type="text" name="pan_card_no" id="pan_card_no" class="form-control"
                                oninput="this.value = this.value.toUpperCase()" value="<?php echo e(old('pan_card_no')); ?>" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email" class="form-label">Email <span class="text-danger"> *</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="<?php echo e(old('email')); ?>" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no" class="form-label">Phone Number <span class="text-danger">
                                    *</span></label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control"
                                value="<?php echo e(old('phone_no')); ?>" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="pincode" class="form-label">Pin Code <span class="text-danger"> *</span></label>
                            <input type="text" name="pincode" id="pincode" class="form-control"
                                value="<?php echo e(old('pincode')); ?>" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address" class="form-label">Address <span class="text-danger"> *</span></label>
                            <textarea name="address" id="address" class="form-control" required><?php echo e(old('address')); ?></textarea>
                            <span class="error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg-sponsor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="sponsor-form" action="<?php echo e(route('change_sponsor')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                <form id="password-form" method="POST" action="<?php echo e(route('adminchangePassword')); ?>">
                    <?php echo csrf_field(); ?>
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
                <form id="trashMoney-form" method="POST" action="<?php echo e(route('adminctrashMoney')); ?>">
                    <?php echo csrf_field(); ?>
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

<?php $__env->stopSection(); ?>



<?php $__env->startSection('footer'); ?>

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

            // AJAX form submit
            $('#user-form').ajaxForm({
                beforeSubmit: function() {
                    $('#user-form button[type="submit"]').prop('disabled', true);
                },
                success: function(responseText) {
                    const data = JSON.parse(responseText);
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    $('.error-message').text('');

                    if (data.status === "validation") {
                        $.each(data.errors, function(key, val) {
                            $('[name="' + key + '"]').closest('.form-group').find(
                                '.error-message').text(val);
                        });
                    } else if (data.status === "success") {
                        $('#user-form')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    }
                },
                error: function() {
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    console.error("Form submission error");
                }
            });
        });

        // Load user data into edit modal
        function useredit(id) {
            $.ajax({
                url: '<?php echo e(route('getUserDetails', ':id')); ?>'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const user = response.data;
                        $('#id').val(user.id);
                        $('#name').val(user.name);
                        $('#pan_card_no').val(user.pan_card_no);
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

        // Load sponsor data into modal
        function changesponsor(id) {
            $.ajax({
                url: '<?php echo e(route('getUserSponsor', ':id')); ?>'.replace(':id', id),
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
                        url: '<?php echo e(route('deleteUser', ':id')); ?>'.replace(':id', id),
                        method: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
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

    
    <?php if(session()->has('success')): ?>
        <script>
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "<?php echo e(session()->get('success')); ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?php echo e(session()->get('error')); ?>"
            });
        </script>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/user_list.blade.php ENDPATH**/ ?>