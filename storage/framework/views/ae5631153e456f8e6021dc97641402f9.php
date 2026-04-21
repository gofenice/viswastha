<?php $__env->startSection('title', 'vishwastha  | Withdrawal'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Withdrawal List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('adminhome')); ?>">Home</a></li>
                            <li class="breadcrumb-item active">Withdrawal List </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <?php if(Auth::check() && Auth::user()->role !== 'superadmin'): ?>
            <div class="card card-info col-md-4" style="margin: 0 auto;">
                <div class="card-header">
                    <h3 class="card-title">Withdrawal</h3>
                </div>
                <form action="<?php echo e(route('withdraw.request')); ?>" method="POST" id="withdrawalModal">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="total_income">Current Balance</label>
                        <input type="text" name="total_income" id="total_income" class="form-control"
                            value="<?php echo e($userdata->total_income ?? 0); ?>" placeholder="Enter amount" readonly>
                        <input type="hidden" name="id" id="id" class="form-control" value="<?php echo e($userdata->id); ?>"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="amount">Withdrawal Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount"
                            required>
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
                    <div class="form-group mb-0">
                        <input type="checkbox" checked name="donate" value="1">
                        <label class="toastsDefaultSuccess"> I agree to donate Rs 50 for charity</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" required checked>
                        <label>I agree to the <a href="#!" class="toastsDefaultWarning">Terms and Conditions
                            </a></label>
                    </div>
                    <div class="card-footer">
                        <?php if($userBankDetails): ?>
                            <?php if($userBankDetails->status == 2): ?>
                                <?php if(!$lastWithdrawal || $lastWithdrawal->status != 'pending'): ?>
                                    <button type="submit" id="submitButton" class="btn btn-primary float-right"
                                        <?php if(!$canWithdraw): ?> disabled <?php endif; ?>>
                                        Submit Withdrawal Request
                                    </button>
                                <?php endif; ?>
                            <?php elseif($userBankDetails->status == 1): ?>
                                <p class="text-danger">Waiting for admin approval</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-danger">Please Update Your Bank Details</p>
                        <?php endif; ?>

                    </div>
                    <?php if($lastWithdrawal): ?>
                        <?php if($lastWithdrawal->status == 'pending'): ?>
                            <p class="text-danger mt-2 text-center">Waiting for your last withdrawal approval. The next
                                withdrawal request can be made 7 days after the last approval date. </p>
                        <?php elseif(!$canWithdraw && $nextWithdrawalDate): ?>
                            <p class="text-danger mt-2 text-center">Next withdrawal available in: <span
                                    id="countdown"></span>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>


        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="widthdrawl" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <?php if(Auth::check() && Auth::user()->role === 'superadmin'): ?>
                                    <th style="width: 150px">Current Wallet<br>Amount</th>
                                <?php endif; ?>
                                <?php if(Auth::check() && Auth::user()->role === 'superadmin'): ?>
                                    <th style="width: 150px">Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($request->user->name); ?> <br> <?php echo e($request->user->connection); ?></td>
                                    <td>
                                        Total :<?php echo e($request->amount); ?><br>
                                        Deduction :<?php echo e($request->deduction_amount); ?><br>
                                        Balance :<?php echo e($request->balance_amount); ?><br>

                                    </td>
                                    <td><?php echo e($request->created_at->format('d-m-Y')); ?></td>
                                    <td>
                                        <?php if($request->status === 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif($request->status === 'approved'): ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(Auth::check() && Auth::user()->role === 'superadmin'): ?>
                                        <td>
                                            <?php echo e($request->user->total_income); ?>

                                        </td>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && Auth::user()->role === 'superadmin'): ?>
                                        <td>

                                            <?php if($request->status == 'pending'): ?>
                                                <div class="d-flex" style="justify-content: space-around;">
                                                    <button class="btn btn-sm btn-success" data-toggle="modal"
                                                        data-target="#approveModal<?php echo e($request->id); ?>">
                                                        Approve
                                                    </button>

                                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#rejectModal<?php echo e($request->id); ?>">
                                                        Reject
                                                    </button>
                                                </div>

                                                <div class="modal fade" id="approveModal<?php echo e($request->id); ?>" tabindex="-1"
                                                    aria-labelledby="approveModalLabel<?php echo e($request->id); ?>"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="<?php echo e(route('admin.withdraw.approve', $request->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="approveModalLabel<?php echo e($request->id); ?>">Confirm
                                                                        Approval</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to approve this withdrawal
                                                                        request?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-success">Confirm</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1"
                                                    aria-labelledby="rejectModalLabel<?php echo e($request->id); ?>"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="<?php echo e(route('admin.withdraw.reject', $request->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="rejectModalLabel<?php echo e($request->id); ?>">Confirm
                                                                        Rejection</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to reject this withdrawal
                                                                        request?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Confirm</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif($request->status == 'approved'): ?>
                                                <span class="badge badge-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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
        $(function() {
            $("#widthdrawl").DataTable();
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
            const motherLink = $('.nav-link.wallet');
            const motherviewLink = $('.nav.nav-treeview.wallet');
            const mainLink = $('.nav-item.has-treeview.wallet');
            const motherIdLink = $('.nav-link.withdrawal');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
        $('.toastsDefaultWarning').click(function() {
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Withdrawal Charges',
                subtitle: 'Important Notice',
                body: '5% admin charge and 5% TDS will be deducted from the withdrawal amount.'
            })
        });
        // Set the countdown date from server-side data
        var countdownDate = new Date("<?php echo e($nextWithdrawalDate); ?>").getTime();

        // Update the countdown every 1 second
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countdownDate - now;

            if (distance > 0) {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML =
                    (days > 0 ? days + "d " : "") +
                    hours + "h " + minutes + "m " + seconds + "s ";
            } else {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "Now!";
                location.reload(); // Reload the page to enable the button
            }
        }, 1000);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("#withdrawalModal");
            if (form) {
                form.addEventListener("submit", function(e) {
                    const submitButton = form.querySelector("button[type='submit']");
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = "Processing...";
                    }
                });
            }
        });
    </script>
    <script>
        function disableSubmitButton() {
            document.getElementById('submitButton').disabled = true;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/withdrawal_view.blade.php ENDPATH**/ ?>