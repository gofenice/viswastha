<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Vishwastha</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="{{ asset('assets/dist/js/sweetalert.js') }}"></script>
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
            <img class="mlm-logo" src="{{ asset('assets/dist/img/logo.png') }}" alt="logo">
            {{-- <a href="#"><b>MLM</b>Login</a> --}}
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
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
                @if (session()->has('successs'))
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "User registered successfully!",
                            html: "{!! session()->get('successs') !!}",
                        });
                    </script>
                @endif
                <form action="{{ route('loginProcess') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="User ID" name="identifier">
                        <div class="error-message text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
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
                            @error('password')
                                {{ $message }}
                            @enderror
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
                        <a href="{{ route('forgot_password') }}">I forgot my password</a>
                    </p>
                    <p class="mb-1 mt-3 col-md-6">
                        <a href="{{ route('free-registration') }}">Create Your Account</a>
                    </p>
                </div>
                <p class="mb-1 mt-3" style="text-align: center;">
                    <span class="vstore">
                        <a href="https://myvstore.in" target="_blank">My Vstore</a>
                    </span>
                </p>
                {{-- <p class="mb-0">
                    <a href="{{ route('new_register') }}" class="text-center">Register a new membership</a>
                </p> --}}
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

</body>

</html>
