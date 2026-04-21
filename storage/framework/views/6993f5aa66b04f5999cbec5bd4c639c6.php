<?php $__env->startSection('title', 'vishwastha | Admin Wallet'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('adminhome')); ?>">Home</a></li>
                            <li class="breadcrumb-item active">Wallet</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="card card-info col-md-4 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Transactions</h3>
                    </div>
                    <form id="walletForm" action="<?php echo e(route('withdraw.request')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="transaction_type">Select Transaction Type</label>
                            <select name="transaction_type" id="transaction_type" class="form-control" required>
                                <option value="">-- Choose Type --</option>
                                <option value="withdrawal" data-action="<?php echo e(route('withdraw.request')); ?>">Withdrawal</option>
                                <option value="royalty" data-action="<?php echo e(route('adminToRoyalty')); ?>">Transfer to Royalty
                                </option>
                                <option value="bonus" data-action="<?php echo e(route('adminToBonus')); ?>">Transfer to Special
                                    Incentive</option>
                                <option value="rank" data-action="<?php echo e(route('adminToRank')); ?>">Transfer to Premium Rank
                                </option>
                                <option value="basicrank" data-action="<?php echo e(route('adminToBasicRank')); ?>">Transfer to Basic
                                    Rank
                                </option>
                                <option value="privilege" data-action="<?php echo e(route('adminToPrivilege')); ?>">Transfer to
                                    Privilege</option>
                                <option value="board" data-action="<?php echo e(route('adminToBoard')); ?>">Transfer to Board</option>
                                <option value="executive" data-action="<?php echo e(route('adminToExecutive')); ?>">Transfer to
                                    Executive
                                </option>
                                <option value="executive" data-action="<?php echo e(route('adminToIncentive')); ?>">Transfer to
                                    Incentive
                                </option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="rankSelectGroup">
                            <label for="rank_id">Choose Rank</label>
                            <select name="rank_id" id="rank_id" class="form-control">
                                <option value="">-- Select Rank --</option>
                                <option value="2">Gold</option>
                                <option value="3">Platinum</option>
                                <option value="4">Pearl</option>
                                <option value="5">Ruby</option>
                                <option value="6">Diamond</option>
                                <option value="7">Double Diamond</option>
                                <option value="8">Emerald</option>
                                <option value="9">Crown</option>
                                <option value="10">Royal Crown</option>
                                <option value="11">Manager</option>
                                <option value="12">Ambassador</option>
                                <option value="13">Royal Crown Ambassador</option>
                            </select>
                        </div>

                        
                        <div class="form-group d-none" id="basicRankSelectGroup">
                            <label for="basic_rank_id">Choose Basic Rank</label>
                            <select name="basic_rank_id" id="basic_rank_id" class="form-control">
                                <option value="2">1 star</option>
                                <option value="3">2 star</option>
                                <option value="4">3 star</option>
                                <option value="5">4 star</option>
                                <option value="6">5 star</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>

                        <div class="form-group">
                            <label for="total_income">Current Balance</label>
                            <input type="text" name="total_income" id="total_income" class="form-control"
                                value="<?php echo e($adminWallet->total_income ?? 0); ?>" readonly>
                            <input type="hidden" name="id" id="id" value="<?php echo e($adminWallet->id); ?>">
                        </div>

                        

                        <button type="submit" class="btn btn-primary float-right mb-2">Submit Transaction</button>
                    </form>

                </div>

                <div class="card card-info col-md-3 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Add income to Admin Wallet</h3>
                    </div>
                    <form id="adminform" action="<?php echo e(route('addManuallyadmin')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <p class="mt-4"> This option allows adding a specific amount directly to the admin wallet for
                            bonus distribution purposes.</p>
                        <div class="form-group mt-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>
                        <input type="hidden" name="admin_id" value="<?php echo e($adminWallet->id); ?>">

                        <button type="submit" class="btn btn-primary float-right mb-2">Add</button>
                    </form>

                </div>

                <div class="card card-info col-md-4 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Add income to user Wallet</h3>
                    </div>
                    <form id="getUserId" action="<?php echo e(route('admininctoUser')); ?>" method="POST"
                        data-user-url="<?php echo e(route('get_user_name')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="userId">User ID</label>
                            <input type="text" class="form-control" id="userId" required name="userId"
                                placeholder="Enter User ID">
                        </div>
                        <div class="form-group">
                            <label for="userName">User Name</label>
                            <input type="text" class="form-control" id="userName" name="userName"
                                placeholder="Name" readonly>
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

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>


                        <button type="submit" class="btn btn-primary float-right mb-2">Transfer</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Amount List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>From / To</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Created</th>
                            <th>Running Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $runningTotal = 0; // Initialize running total
                            $transactions = $adminAmountList->reverse(); // Reverse the list to process in chronological order
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $adminAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                if (in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21])) {
                                    $runningTotal -= $adminAmount->amount; // Deduct withdrawal amount for type 4,6 and 8
                                } else {
                                    $runningTotal += $adminAmount->amount; // Add income amount for other types
                                }
                            ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($adminAmount->fromUser->name ?? 'Rank'); ?><br><?php echo e($adminAmount->fromUser->connection ?? ''); ?>

                                </td>

                                
                                <td
                                    style="color: <?php echo e(in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? 'red' : 'green'); ?>;">
                                    <?php echo e(in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? '-' : '+'); ?>

                                    <?php echo e($adminAmount->amount); ?>

                                </td>
                                
                                <td>
                                    <?php if($adminAmount->type == 1): ?>
                                        Rank Income
                                    <?php elseif($adminAmount->type == 2): ?>
                                        Admin Fee
                                    <?php elseif($adminAmount->type == 3): ?>
                                        TDS Fee
                                    <?php elseif($adminAmount->type == 4): ?>
                                        <span class="text-danger">Transfer to Royalty</span>
                                    <?php elseif($adminAmount->type == 5): ?>
                                        Unpaid Rank Amount
                                    <?php elseif($adminAmount->type == 7): ?>
                                        Donation Amount
                                    <?php elseif($adminAmount->type == 8): ?>
                                        <span class="text-danger">Transfer to Special Incentive</span>
                                    <?php elseif($adminAmount->type == 9): ?>
                                        <span class="text-danger">Transfer to User</span>
                                    <?php elseif($adminAmount->type == 10): ?>
                                        <span class="text-danger">Transfer to Rank income</span>
                                    <?php elseif($adminAmount->type == 11): ?>
                                        <span class="text-success">Manually add to wallet</span>
                                    <?php elseif($adminAmount->type == 12): ?>
                                        <span class="text-danger">Transfer to Privilege income</span>
                                    <?php elseif($adminAmount->type == 13): ?>
                                        <span class="text-danger">Transfer to Board income</span>
                                    <?php elseif($adminAmount->type == 14): ?>
                                        <span class="text-danger">Transfer to Executive income</span>
                                    <?php elseif($adminAmount->type == 15): ?>
                                        <span class="text-danger">Transfer to Incentive income</span>
                                    <?php elseif($adminAmount->type == 16): ?>
                                        <span class="text-success">Board Incentive</span>
                                    <?php elseif($adminAmount->type == 17): ?>
                                        <span class="text-success">Executive Incentive</span>
                                    <?php elseif($adminAmount->type == 18): ?>
                                        <span class="text-success">Privilege Incentive</span>
                                    <?php elseif($adminAmount->type == 19): ?>
                                        <span class="text-success">Basic Rank Income</span>
                                    <?php elseif($adminAmount->type == 20): ?>
                                        <span class="text-success">Unpaid Basic Rank Income</span>
                                    <?php elseif($adminAmount->type == 21): ?>
                                        <span class="text-danger">Transfer Basic Rank Income</span>
                                    <?php elseif($adminAmount->type == 22): ?>
                                        <span class="text-success">Vstore Incentive</span>
                                    <?php elseif($adminAmount->type == 23): ?>
                                        <span class="text-success">TCS Income - My Vstore</span>
                                    <?php elseif($adminAmount->type == 24): ?>
                                        <span class="text-success">GST Income - My Vstore</span>
                                    <?php else: ?>
                                        <span class="text-danger">Withdrawal</span>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo e($adminAmount->created_at->format('d-m-Y')); ?></td>

                                
                                <td><?php echo e($runningTotal); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6">No details found.</td>
                            </tr>
                        <?php endif; ?>


                    </tbody>
                </table>

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
            const teamLink = $('.nav-link.adminWallet');
            const treeviewLink = $('.nav.nav-treeview.adminWallet');
            const mainLiLink = $('.nav-item.has-treeview.adminWallet');
            const walletLink = $('.nav-link.adminWallet');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        $('.toastsDefaultWarning').click(function() {
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Withdrawal Charges',
                subtitle: 'Important Notice',
                body: '5% TDS will be deducted from the withdrawal amount.'
            })
        });
    </script>
    <script>
        document.getElementById('transaction_type').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const newAction = selectedOption.getAttribute('data-action');
            if (newAction) {
                document.getElementById('walletForm').setAttribute('action', newAction);
            }
        });

        $(document).ready(function() {
            $('#userId').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: $('#getUserId').data("user-url"),
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
    </script>
    <script>
        document.getElementById('transaction_type').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedText = selectedOption.text.trim();
            const newAction = selectedOption.getAttribute('data-action');

            const rankGroup = document.getElementById('rankSelectGroup');
            const basicRankGroup = document.getElementById('basicRankSelectGroup');

            // Update the form action dynamically
            if (newAction) {
                document.getElementById('walletForm').setAttribute('action', newAction);
            }

            // Handle visibility logic
            if (selectedText === 'Transfer to Premium Rank') {
                rankGroup.classList.remove('d-none');
                basicRankGroup.classList.add('d-none');
                document.getElementById('rank_id').setAttribute('required', 'required');
                document.getElementById('basic_rank_id').removeAttribute('required');
            } else if (selectedText === 'Transfer to Basic Rank') {
                basicRankGroup.classList.remove('d-none');
                rankGroup.classList.add('d-none');
                document.getElementById('basic_rank_id').setAttribute('required', 'required');
                document.getElementById('rank_id').removeAttribute('required');
            } else {
                rankGroup.classList.add('d-none');
                basicRankGroup.classList.add('d-none');
                document.getElementById('rank_id').removeAttribute('required');
                document.getElementById('basic_rank_id').removeAttribute('required');
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/adminWallet.blade.php ENDPATH**/ ?>