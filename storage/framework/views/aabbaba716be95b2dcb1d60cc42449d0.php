
<?php $__env->startSection('title', 'vishwastha   | Generate Pin'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Generate Pin(s)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Generate Pin(s)</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Generate Pin(s)</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="<?php echo e(route('add_pin')); ?>">
                <?php echo csrf_field(); ?>
                <p data-user-url="<?php echo e(route('get_package')); ?>" id="package_amount"></p>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="package" class="col-sm-4 col-form-label">Package</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="package_id" required>
                                <option value="">--Choose Option---</option>
                                
                                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['package_id'];
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
                        <label for="package_value" class="col-sm-4 col-form-label">Pin Value</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="package_value" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numOfPins" class="col-sm-4 col-form-label">No. Of Pins</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="numOfPins" name="numOfPins"
                                placeholder="No. Of Pins" required>
                            <?php $__errorArgs = ['numOfPins'];
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
                        <label for="totalCost" class="col-sm-4 col-form-label">Total Pin Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="totalCost" name="totalCost"
                                placeholder="Total Pin Cost" readonly>
                            <?php $__errorArgs = ['totalCost'];
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
                        <label for="walletBalance" class="col-sm-4 col-form-label">Wallet Balance</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="walletBalance"
                                value="<?php echo e(isset($walletBalance) ? $walletBalance : ''); ?>" disabled>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Generate</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
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
    <?php if(session()->has('error')): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?php echo e(session()->get('error')); ?>",
            });
        </script>
    <?php endif; ?>
    <script>
        $(document).ready(function() {

            function updateTotalCost() {
                let numOfPins = parseFloat($('#numOfPins').val()) || 0;
                let packageValue = parseFloat($('#package_value').val()) || 0;
                let totalCost = numOfPins * packageValue;
                $('#totalCost').val(totalCost.toFixed(2));
            }

            $('#numOfPins').on('input', updateTotalCost);

            $('select[name="package_id"]').on('change', function() {
                var packageId = $(this).val();
                if (packageId) {
                    $.ajax({
                        url: $('#package_amount').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            packageId: packageId
                        }),
                        success: function(data) {
                            if (data.success) {
                                $('#package_value').val(data.amount);
                                updateTotalCost();
                            } else {
                                $('#package_value').val('');
                                $('#totalCost').val('');
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Package Amount Not Found",
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error fetching package details.",
                            });
                        }
                    });
                } else {
                    $('#totalCost').val('');
                    $('#package_value').val('');
                }
            });
        });

        $(document).ready(function() {
            const pinLink = $('.nav-link.pin');
            const treeviewLink = $('.nav.nav-treeview.pin');
            const mainLiLink = $('.nav-item.has-treeview.pin');
            const generatepinLink = $('.nav-link.generatepin');
            if (generatepinLink.length) {
                generatepinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/generate_pin.blade.php ENDPATH**/ ?>