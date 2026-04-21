<?php $__env->startSection('title', 'vishwastha  | Donation Wallet'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Donation Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('adminhome')); ?>">Home</a></li>
                            <li class="breadcrumb-item active">Donation</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6 mx-auto">
                    <div class="small-box bg-primary" style=" color: black;">
                        <div class="inner">
                            <h4>Donation Wallet</h4>
                            <p class="mb-0"> Total : <?php echo e($donationwallet); ?></p>
                            <p class="mb-0"> Redeemed : <?php echo e($donationRedeemedwallet); ?></p>
                            <p class="mb-0"> Active : <?php echo e($donationCurrentwallet); ?></p>

                        </div>
                        <div class="icon">
                            
                        </div>
                        <div class="flex justify-around mt-2 pb-2">

                            <!-- Redeemed to wallet Button -->
                            <a href="#" class="px-1 py-2 "
                                style="color: black;justify-content: center;display: flex;align-items: center;"
                                data-toggle="modal" data-target="#donationTransferModal">
                                Transfer to Wallet
                                <i class="fas fa-arrow-circle-right mr-2 ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Donation List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Donated Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $donationlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($list->user->name ?? 'N/A'); ?><br><?php echo e($list->user->connection ?? ''); ?>

                                </td>
                                <td><?php echo e($list->amount); ?></td>
                                <td><?php echo e($list->created_at->format('d-m-Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4">No donations found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="donationTransferModal" tabindex="-1" aria-labelledby="donationTransferModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?php echo e(route('donationTransfer')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="donationTransferModal">Confirm Donation Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to transfer the amount to Wallet ?</p>
                        <input type="hidden" name="userId" id="user_id" value="<?php echo e(auth()->id()); ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
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
            const teamLink = $('.nav-link.donationwall');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/donation_wallet.blade.php ENDPATH**/ ?>