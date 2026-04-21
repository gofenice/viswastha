
<?php $__env->startSection('title', 'vishwastha  | Bank Details'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Bank Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Update Bank Details</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Update Bank Details</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="<?php echo e(route('bank_details_update')); ?>"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <!-- <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label">User Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="inputEmail3" value="<?php echo e(auth()->user()->name); ?>" disabled>
                                        </div>
                                    </div> -->
                    <div class="form-group row">
                        <label for="ifs_code" class="col-sm-4 col-form-label">IFS Code</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="ifs_code"
                                oninput="this.value = this.value.toUpperCase()"
                                value="<?php echo e(old('ifs_code', $userBankDetails->ifs_code ?? '')); ?>"
                                placeholder="Enter IFSC Code" required>
                            <?php $__errorArgs = ['ifs_code'];
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
                        <label for="bank_name" class="col-sm-4 col-form-label">Bank Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="bank_name"
                                value="<?php echo e(old('bank_name', $userBankDetails->bank_name ?? '')); ?>" required>
                            <?php $__errorArgs = ['bank_name'];
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
                        <label for="branch_name" class="col-sm-4 col-form-label">Branch Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="branch_name"
                                value="<?php echo e(old('branch_name', $userBankDetails->branch_name ?? '')); ?>" required>
                            <?php $__errorArgs = ['branch_name'];
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
                        <label for="account_number" class="col-sm-4 col-form-label">Account Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="account_number"
                                value="<?php echo e(old('account_number', $userBankDetails->account_number ?? '')); ?>" required>
                            <?php $__errorArgs = ['account_number'];
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
                        <label for="account_holder_name" class="col-sm-4 col-form-label">Account Holder Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="account_holder_name"
                                value="<?php echo e(old('account_holder_name', $userBankDetails->account_holder_name ?? '')); ?>"
                                required>
                            <?php $__errorArgs = ['account_holder_name'];
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
                    
                    <?php if($userBankDetails): ?>
                        <?php if($userBankDetails->status == 2): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="passbook">Passbook</label><br>
                                    <img src="<?php echo e(asset($userBankDetails->bank_passbook_image)); ?>" width="300"
                                        height="200">
                                </div>
                                <div class="col-md-6">
                                    <label for="passbook">Pan card</label><br>
                                    <img src="<?php echo e(asset($userBankDetails->pancard_image)); ?>" width="300" height="200">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-group row">
                                <label for="passbook_img" class="col-sm-4 col-form-label">Image of Passbook</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" id="passbook_img" name="passbook_img"
                                        placeholder="Enter the product name" required>
                                    <?php $__errorArgs = ['passbook_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <img id="imagePreviewPass" alt="Selected Image">
                                    <?php if($userBankDetails->bank_passbook_image): ?>
                                        <img src="<?php echo e(asset($userBankDetails->bank_passbook_image)); ?>" width="250"
                                            height="150">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pancard_img" class="col-sm-4 col-form-label">Image of Pan card</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" id="pancard_img" name="pancard_img"
                                        placeholder="Enter the product name" required>
                                    <?php $__errorArgs = ['pancard_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <img id="imagePreviewPan" alt="Selected Image">
                                    <?php if($userBankDetails->pancard_image): ?>
                                        <img src="<?php echo e(asset($userBankDetails->pancard_image)); ?>" width="250"
                                            height="150">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="form-group row">
                            <label for="passbook_img" class="col-sm-4 col-form-label">Image of Passbook</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="passbook_img" name="passbook_img"
                                    placeholder="Enter the product name" required>
                                <?php $__errorArgs = ['passbook_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <img id="imagePreviewPass" alt="Selected Image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pancard_img" class="col-sm-4 col-form-label">Image of Pan card</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="pancard_img" name="pancard_img"
                                    placeholder="Enter the product name" required>
                                <?php $__errorArgs = ['pancard_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <img id="imagePreviewPan" alt="Selected Image">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($userBankDetails): ?>
                        <?php if($userBankDetails->status == 2): ?>
                            
                        <?php elseif($userBankDetails->status == 0): ?>
                            <span class="text-danger"><?php echo e($userBankDetails->note); ?></span>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info float-right">Update Details</button>
                            </div>
                        <?php else: ?>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info float-right">Update Details</button>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info float-right">Add Bank Details</button>
                        </div>
                    <?php endif; ?>

                </div>
            </form>

        </div>
        <!-- /.card -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
    <script>
        $(document).ready(function() {
            const motherLink = $('.nav-link.wallet');
            const motherviewLink = $('.nav.nav-treeview.wallet');
            const mainLink = $('.nav-item.has-treeview.wallet');
            const motherIdLink = $('.nav-link.bank_details');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
        $(document).ready(function() {
            $('input[name="ifs_code"]').on('blur', function() {
                let ifsc = $(this).val().trim();
                if (ifsc.length === 11) {
                    $.ajax({
                        url: `https://ifsc.razorpay.com/${ifsc}`,
                        type: 'GET',
                        success: function(data) {
                            $('input[name="bank_name"]').val(data.BANK);
                            $('input[name="branch_name"]').val(data.BRANCH);
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Invalid IFSC Code. Please enter a valid one.",
                            });
                            $('input[name="bank_name"]').val('');
                            $('input[name="branch_name"]').val('');
                        }
                    });
                }
            });
        });
        document.getElementById('pancard_img').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreviewPan');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
        document.getElementById('passbook_img').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreviewPass');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/edit_bank_details.blade.php ENDPATH**/ ?>