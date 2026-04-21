<?php $__env->startSection('title', 'vishwastha | Add Wallet'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Receipt </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Receipt </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php if(Auth::check() && Auth::user()->role != 'superadmin'): ?>
            <div class="card card-info col-md-8" style="margin: 0 auto;">
                <div class="card-header">
                    <h3 class="card-title">Receipt Upload</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="<?php echo e(route('update_wallet')); ?>"
                    enctype="multipart/form-data" id="user-pin-wallet">
                    <?php echo csrf_field(); ?>
                    <p data-user-url="<?php echo e(route('get_user_name')); ?>" id="wallet_name"></p>
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="userId" class="col-sm-4 col-form-label">User Id</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userId" name="userId"
                                    oninput="this.value = this.value.toUpperCase()" placeholder="Enter the user id"
                                    required>
                                <?php $__errorArgs = ['userId'];
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
                            <label for="userName" class="col-sm-4 col-form-label">Name </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userName" name="userName" placeholder=""
                                    readonly>
                                <?php $__errorArgs = ['userName'];
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
                            <label for="accName" class="col-sm-4 col-form-label">Account Holder Name </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="accName" name="accName"
                                    placeholder="Enter the account holder name" required>
                                <?php $__errorArgs = ['accName'];
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
                            <label for="amount" class="col-sm-4 col-form-label">Amount </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="amount" name="amount"
                                    placeholder="Enter the amount" required>
                                <?php $__errorArgs = ['amount'];
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
                            <label for="dOfSend" class="col-sm-4 col-form-label">Date of Send </label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="dOfSend" name="dOfSend" required>
                                <?php $__errorArgs = ['dOfSend'];
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
                            <label for="transaction_id" class="col-sm-4 col-form-label">Transaction ID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                                    required placeholder="Enter the transaction ID">
                                <?php $__errorArgs = ['transaction_id'];
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
                            <label for="receipt_image" class="col-sm-4 col-form-label">Image of Bank Receipt</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="receipt_image" name="receipt_image"
                                    placeholder="Enter the product name" required>
                                <?php $__errorArgs = ['receipt_image'];
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
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info float-right" id="submitBtn">Send Wallet Amount</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        <?php endif; ?>
        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Account Holder Name &<br> Transaction ID</th>
                            <th>Amount</th>
                            <th>Date of Send</th>
                            <th>Image</th>
                            <th>Status</th>
                            <?php if(Auth::user()->role === 'superadmin'): ?>
                                <th>Edit</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($reciepts['bankTransactionDetails']) && $reciepts['bankTransactionDetails']->isNotEmpty()): ?>
                            <?php $__currentLoopData = $reciepts['bankTransactionDetails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $reciept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($reciept->user->name ?? ''); ?><br><?php echo e($reciept->user->connection ?? ''); ?></td>
                                    <td><?php echo e($reciept->acc_holder_name); ?><br><?php echo e($reciept->transaction_id); ?></td>
                                    <td><?php echo e(number_format($reciept->amount, 2)); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($reciept->date_of_send)->format('d-m-Y')); ?></td>
                                    <td>
                                        <?php if($reciept->image): ?>
                                            <a href="<?php echo e(asset($reciept->image)); ?>" target="_blank">
                                                <img src="<?php echo e(asset($reciept->image)); ?>" alt="Receipt Image"
                                                    style="width: 50px; height: 50px;">
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0)">No Image</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($reciept->status === 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif($reciept->status === 'completed'): ?>
                                            <span class="badge badge-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(Auth::user()->role === 'superadmin'): ?>
                                        <td>
                                            <?php if($reciept->status === 'completed'): ?>
                                                <span class="badge badge-success">Confirmed</span>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#editModal" data-id="<?php echo e($reciept->id); ?>"
                                                    data-status="<?php echo e($reciept->status); ?>"
                                                    data-total-income="<?php echo e($reciepts['totalIncomes'][$reciept->user_id] ?? 'N/A'); ?>"
                                                    data-amount="<?php echo e(number_format($reciept->amount, 2)); ?>">
                                                    Edit
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>


                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?php echo e(route('updateStatus')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="reciept_id" id="recieptId">
                        <div class="form-group">
                            <label for="totalIncome">Old Wallet Amount : </label>
                            <p id="totalIncomeDisplay" class="form-control-plaintext"></p>
                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount (After Approvel) :</label>
                            <p id="totalAmountDisplay" class="form-control-plaintext"></p>
                        </div>
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="completed">Approved</option>
                                <option value="failed">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
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
        $(document).ready(function() {
            $('#userId').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: $('#wallet_name').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token for security
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            userId: userId
                        }),
                        success: function(response) {
                            if (response.name) {
                                $('#userName').val(response.name);
                            }
                        },
                        error: function() {
                            $('#userName').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User Not Found",
                            });
                        }
                    });
                } else {
                    $('#userName').val(''); // Clear the field if userId is empty
                }
            });
        });

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

        document.addEventListener('DOMContentLoaded', function() {
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var recieptId = button.data('id'); // Extract info from data-* attributes
                var status = button.data('status');
                var totalIncome = button.data('total-income');
                var amount = button.data('amount');

                var totalAmount = (parseFloat(totalIncome) || 0) + (parseFloat(amount.replace(/[^0-9.-]+/g,
                    "")) || 0);

                var modal = $(this);
                modal.find('#recieptId').val(recieptId);
                modal.find('#status').val(status);
                modal.find('#totalIncomeDisplay').text(totalIncome !== 'N/A' ?
                    `₹ ${parseFloat(totalIncome).toFixed(2)}` : 'N/A');
                modal.find('#totalAmountDisplay').text(totalAmount ? `₹ ${totalAmount.toFixed(2)}` : 'N/A');
            });
        });

        $(document).ready(function() {
            const teamLink = $('.nav-link.pin');
            const treeviewLink = $('.nav.nav-treeview.pin');
            const mainLiLink = $('.nav-item.has-treeview.pin');
            const walletLink = $('.nav-link.addwallet');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#editModal form").addEventListener("submit", function(e) {
                let submitButton = this.querySelector("button[type='submit']");
                submitButton.disabled = true;
                submitButton.innerHTML = "Processing...";
            });
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("user-pin-wallet");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                // Disable button to prevent multiple clicks
                submitBtn.disabled = true;
                // Change button text to loading
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/add_wallet.blade.php ENDPATH**/ ?>