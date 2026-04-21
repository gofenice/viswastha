<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Vishwastha</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/fontawesome-free/css/all.min.css')); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/dist/css/adminlte.min.css')); ?>">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="<?php echo e(asset('assets/dist/js/sweetalert.js')); ?>"></script>
</head>
<style>
    img.mlm-logo {
        width: 200px;
    }

    .vstore a:hover {
        color: #e7e9eb !important;
        text-decoration: none;
        background: #007bff;
        border-radius: 10px !important;
    }

    .vstore a {
        padding-left: 5px;
        padding-right: 5px;
    }

    .vstore {
        border: 1px solid #007bff;
        border-radius: 10px;
    }
</style>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <img class="mlm-logo" src="<?php echo e(asset('assets/dist/img/logo.png')); ?>" alt="logo">
            
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
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
                <?php if(session()->has('successs')): ?>
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "User registered successfully!",
                            html: "<?php echo session()->get('successs'); ?>",
                        });
                    </script>
                <?php endif; ?>
                <form action="<?php echo e(route('loginProcess')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="User ID" name="identifier">
                        <div class="error-message text-danger">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="error-message text-danger">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <?php echo e($message); ?>

                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4" style="margin: 0 auto;">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="row">
                    <p class="mb-1 mt-3 col-md-6">
                        <a href="<?php echo e(route('forgot_password')); ?>">I forgot my password</a>
                    </p>
                    <p class="mb-1 mt-3 col-md-6">
                        <a href="<?php echo e(route('free-registration')); ?>">Create Your Account</a>
                    </p>
                </div>
                <p class="mb-1 mt-3" style="text-align: center;">
                    <span class="vstore">
                        <a href="https://myvstore.in" target="_blank">My Vstore</a>
                    </span>
                </p>
                
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?php echo e(asset('assets/plugins/jquery/jquery.min.js')); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo e(asset('assets/dist/js/adminlte.min.js')); ?>"></script>

</body>

</html>
<?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/Admin/login.blade.php ENDPATH**/ ?>