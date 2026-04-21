<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    .login-box,
    .register-box {
        width: 860px !important;
    }

    .input-error::placeholder {
        color: red;
        /* Make the placeholder text red */
    }
</style>
<style>
    .form-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 40px;
        margin-top: 20px;
    }

    .form-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px 15px 0 0;
        margin: -40px -40px 30px -40px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .form-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 26px;
        display: flex;
        align-items: center;
    }

    .form-card-header h4 i {
        margin-right: 12px;
        font-size: 28px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px 16px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    .form-control:read-only {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 14px 50px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
        color: white;
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .password-toggle-btn {
        border: 2px solid #e0e0e0;
        border-left: none;
        border-radius: 0 10px 10px 0;
        background: #f8f9fa;
        color: #667eea;
        padding: 0 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .password-toggle-btn:hover {
        background: #667eea;
        color: #fff;
        border-color: #667eea;
    }

    .input-group .form-control:not(:last-child) {
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    .btn-reset {
        background: #6c757d;
        border: none;
        color: white;
        padding: 14px 50px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    .form-footer {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #e0e0e0;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .error-message {
        font-size: 0.875rem;
        margin-top: 5px;
        display: block;
        font-weight: 500;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .content-header h1 {
        font-weight: 700;
        color: #333;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }

    .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 15px;
    }

    .checkbox-group input[type="checkbox"] {
        margin-top: 4px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin-bottom: 0;
        cursor: pointer;
        font-weight: 500;
        color: #555;
    }

    .checkbox-group label a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .checkbox-group label a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 25px;
        }

        .form-card-header {
            margin: -25px -25px 25px -25px;
            padding: 20px;
        }

        .form-card-header h4 {
            font-size: 22px;
        }

        .form-footer {
            flex-direction: column;
        }

        .btn-submit,
        .btn-reset {
            width: 100%;
        }
    }
</style>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <img class="mlm-logo" src="{{ asset('assets/dist/img/logo.png') }}" alt="logo">
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Free Registration</p>

                <form id="user-form" action="{{ route('register.store.wpan_rr') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sponsor_id">
                                    <i class="fas fa-user-tie mr-1"></i>Sponsor ID
                                </label>
                                <input type="text" name="sponsor_id" id="sponsor_id" class="form-control"
                                    value="{{ old('sponsorId') }}" placeholder="Enter Sponsor ID">
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sponsor_name">
                                    <i class="fas fa-user mr-1"></i>Sponsor Name
                                </label>
                                <input type="text" name="sponsor_name" id="sponsor_name" class="form-control"
                                    value="{{ old('sponsor_name') }}" placeholder="Auto-filled" readonly>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-signature mr-1"></i>Full Name
                                    <span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" placeholder="Enter Full Name" required>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope mr-1"></i>Email Address
                                    <span class="required-star">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email') }}" placeholder="example@email.com" required>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_no">
                                    <i class="fas fa-phone mr-1"></i>Phone Number
                                    <span class="required-star">*</span>
                                </label>
                                <input type="text" name="phone_no" id="phone_no" class="form-control"
                                    value="{{ old('phone_no') }}" placeholder="10-digit mobile number" maxlength="10"
                                    required>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pincode">
                                    <i class="fas fa-map-pin mr-1"></i>Pin Code
                                    <span class="required-star">*</span>
                                </label>
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    value="{{ old('pincode') }}" placeholder="6-digit PIN code" maxlength="6"
                                    required>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock mr-1"></i>Password
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter strong password" required>
                                    <button type="button" class="password-toggle-btn" id="togglePassword"
                                        title="Show/Hide Password">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock mr-1"></i>Confirm Password
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="Re-enter password" required>
                                    <button type="button" class="password-toggle-btn" id="toggleConfirmPassword"
                                        title="Show/Hide Confirm Password">
                                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                    </button>
                                </div>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Address
                                    <span class="required-star">*</span>
                                </label>
                                <textarea name="address" id="address" class="form-control" placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                                <span class="error-message text-danger"></span>
                            </div>
                        </div>

                        <!-- <div class="col-md-12">
                                        <div class="checkbox-group">
                                            <input name="psgdpr1" type="checkbox" value="1" id="psgdpr1" required>
                                            <label for="psgdpr1">There is no refund for activation products</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="checkbox-group">
                                            <input name="psgdpr2" type="checkbox" value="1" id="psgdpr2" required>
                                            <label for="psgdpr2">I agree to the <a href="{{ route('terms') }}" target="_blank">Terms and Conditions</a> and the Privacy Policy</label>
                                        </div>
                                    </div> -->
                    </div>

                    <div class="form-footer">
                        <button type="reset" class="btn btn-reset">
                            <i class="fas fa-redo mr-2"></i>Reset Form
                        </button>
                        <button type="submit" class="btn btn-submit" id="submitBtn">
                            <i class="fas fa-user-check mr-2"></i>Register User
                        </button>
                    </div>
                </form>
            </div>
            <a href="{{ route('/') }}" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
    </div>
    <!-- /.register-box -->

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    @if (session()->has('error'))
        <script>
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "{{ session()->get('error') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Fetch Sponsor Name when Sponsor ID is entered
            $("#sponsor_id").on("blur", function() {
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
                            if (response.success) {
                                $("#sponsor_name").val(response.name);
                                $("#sponsor_id").closest('.form-group').find('.error-message')
                                    .text('');
                            } else {
                                $("#sponsor_name").val("Sponsor not found.");
                                $("#sponsor_id").closest('.form-group').find('.error-message')
                                    .text('Sponsor not found');
                            }
                        },
                        error: function() {
                            $("#sponsor_name").val("Error fetching sponsor.");
                            $("#sponsor_id").closest('.form-group').find('.error-message').text(
                                'Error fetching sponsor');
                        }
                    });
                } else {
                    $("#sponsor_name").val("");
                }
            });
        });
        // document.addEventListener("DOMContentLoaded", function() {
        //     document.getElementById("user-reg").addEventListener("submit", function(event) {
        //         event.preventDefault(); // Prevent default submission
        //
        //         Swal.fire({
        //             title: "Are you sure?",
        //             text: "Please confirm if your details are correct.",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonText: "Yes, submit!",
        //             cancelButtonText: "Cancel",
        //             reverseButtons: true
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 event.target.submit(); // Submit the form if confirmed
        //             }
        //         });
        //     });
        // });
        // Password toggle buttons
        document.getElementById('togglePassword').addEventListener('click', function() {
            var input = document.getElementById('password');
            var icon = document.getElementById('togglePasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            var input = document.getElementById('password_confirmation');
            var icon = document.getElementById('toggleConfirmPasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        $(document).ready(function() {
            // Prevent auto submission by ajaxForm
            $('#user-form').submit(function(e) {
                e.preventDefault(); // Stop default submission

                Swal.fire({
                    title: "Are you sure?",
                    text: "Please confirm if your details are correct.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, submit!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitUserForm();
                    }
                });
            });


            function submitUserForm() {
                var form = $('#user-form');
                $.ajax({
                    url: form.attr('action'), // Get form action URL
                    type: form.attr('method'), // Get form method (POST)
                    data: form.serialize(), // Serialize form data
                    dataType: 'json', // Expect JSON response
                    beforeSend: function() {
                        $('#user-form button[type="submit"]').prop('disabled', true);
                    },
                    success: function(data) {
                        $('#user-form button[type="submit"]').prop('disabled', false);
                        $('#user-form .error-message').text("");

                        if (data.status === "validation") {
                            $.each(data.errors, function(key, val) {
                                $('[name="' + key + '"]').closest('.form-group')
                                    .find('.error-message').text(val);
                            });
                        } else if (data.status === "error") {
                            Swal.fire({
                                position: "top-center",
                                icon: "error",
                                title: "Error!",
                                text: data.message,
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            });
                        } else if (data.status === "success") {
                            form[0].reset();
                            Swal.fire({
                                position: "top-center",
                                icon: "success",
                                title: "User added successfully!",
                                html: `<p>Username: <strong>${data.connection}</strong></p>
                               <p>Password: <strong>${data.password}</strong></p>`,
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "https://myvstore.in";
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#user-form button[type="submit"]').prop('disabled', false);
                        console.error(error, xhr, status);
                        Swal.fire({
                            position: "top-center",
                            icon: "error",
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            showConfirmButton: true,
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>
