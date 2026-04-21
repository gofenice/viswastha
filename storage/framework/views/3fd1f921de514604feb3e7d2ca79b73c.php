<?php $__env->startSection('title', 'vishwastha | Package'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .radio {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .packagelist {
            margin: 0 auto;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Package</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Package</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Add Package</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="<?php echo e(route('add_package')); ?>">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="packageName" class="col-sm-4 col-form-label">Name of Package</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="packageName" name="packageName"
                                placeholder="Name of Package" required>
                            <?php $__errorArgs = ['packageName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageAmount" class="col-sm-4 col-form-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="packageAmount" name="packageAmount"
                                placeholder="Amount of Package" required>
                            <?php $__errorArgs = ['packageAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageCategory" class="col-sm-4 col-form-label">Category</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="packageCategory" name="packageCategory" required>
                                <option value="basic_package">Basic</option>
                                <option value="premium_package">Premium</option>
                            </select>
                            <?php $__errorArgs = ['packageCategory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageCat" class="col-sm-4 col-form-label">Type</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="packageCat" name="packageCat" required>
                                <option value="0">Basic</option>
                                <option value="1">Premium</option>
                            </select>
                            <?php $__errorArgs = ['packageCat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8 radio">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check ml-2">
                                <input class="form-check-input" type="radio" name="status" value="0">
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Add Package</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
        <div class="card mt-3 packagelist col-md-11">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Package Name</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Active/Incative</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($packages) && $packages->isNotEmpty()): ?>
                            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($package->name); ?></td>
                                    <td><?php echo e($package->amount); ?></td>
                                    <td>
                                        <?php if($package->package_code == 'basic_package'): ?>
                                            Basic Package
                                        <?php else: ?>
                                            Premium package
                                        <?php endif; ?>

                                    </td>
                                    <td><?php echo e($package->status ? 'Active' : 'Inactive'); ?></td>
                                    <td>
                                        <?php if($package->package_cat == '0'): ?>
                                            Basic
                                        <?php else: ?>
                                            Premium
                                        <?php endif; ?>

                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#editPackageModal"
                                            onclick="editPackage('<?php echo e($package->id); ?>', '<?php echo e($package->name); ?>', '<?php echo e($package->amount); ?>', '<?php echo e($package->status); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>

        
        <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editPackageForm" method="POST" action="<?php echo e(route('edit_package')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="packageId" name="id">
                                <label for="editName">Package Name</label>
                                <input type="text" class="form-control" id="editName" name="name" required
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="editAmount">Amount</label>
                                <input type="number" class="form-control" id="editAmount" name="amount" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label><br>
                                <input type="radio" name="status" id="status_active" value="1"> Active
                                <input type="radio" name="status" id="status_inactive" value="0"> Inactive
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="deletePackageModal" tabindex="-1" role="dialog"
            aria-labelledby="deletePackageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePackageModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteMessage">
                            Are you sure you want to delete this package?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form id="deletePackageForm" method="POST" action="<?php echo e(route('delete_package')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" class="form-control" id="packageId" name="id">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
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
    <script>
        function editPackage(id, name, amount, status) {
            var packageId = $('#editPackageModal').find('#packageId');
            packageId.val(id);
            var packageNameIdField = $('#editPackageModal').find('#editName');
            packageNameIdField.val(name);
            var packageAmountIdField = $('#editPackageModal').find('#editAmount');
            packageAmountIdField.val(amount);
            var packageStatusIdField = $('#editPackageModal').find('#status');
            packageStatusIdField.prop('checked', false);
            if (status == 1) {
                $('#editPackageModal').find('#status_active').prop('checked', true);
            } else {
                $('#editPackageModal').find('#status_inactive').prop('checked', true);
            }
            $('#editPackageForm .error-message').text("");
            $('#editPackageModal').modal('toggle');
        }

        function confirmDelete(id, name) {
            var packageId = $('#deletePackageModal').find('#packageId');
            packageId.val(id);
            $('#deletePackageModal').modal('toggle');
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/packages.blade.php ENDPATH**/ ?>