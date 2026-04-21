@extends('Admin.admin_header')
@section('title', 'vishwastha | User Registration')
@section('content')
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

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Registration</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">User Registration</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="form-card">
                            <div class="form-card-header">
                                <h4><i class="fas fa-user-plus"></i>New User Registration Form</h4>
                            </div>

                            <form id="user-form" action="{{ route('register.store.simple') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sponsor_id">
                                                <i class="fas fa-user-tie mr-1"></i>Sponsor ID
                                                <span class="required-star">*</span>
                                            </label>
                                            <input type="text" name="sponsor_id" id="sponsor_id" class="form-control"
                                                value="{{ old('sponsorId') }}" placeholder="Enter Sponsor ID" required>
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
                                                value="{{ old('phone_no') }}"
                                                placeholder="10-digit mobile number"
                                                maxlength="10" required>
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
                                                value="{{ old('pincode') }}"
                                                placeholder="6-digit PIN code"
                                                maxlength="6" required>
                                            <span class="error-message text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">
                                                <i class="fas fa-lock mr-1"></i>Password
                                                <span class="required-star">*</span>
                                            </label>
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="Enter strong password" required>
                                            <span class="error-message text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">
                                                <i class="fas fa-lock mr-1"></i>Confirm Password
                                                <span class="required-star">*</span>
                                            </label>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="password_confirmation" placeholder="Re-enter password" required>
                                            <span class="error-message text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">
                                                <i class="fas fa-map-marker-alt mr-1"></i>Address
                                                <span class="required-star">*</span>
                                            </label>
                                            <textarea name="address" id="address" class="form-control"
                                                placeholder="Enter complete address" required>{{ old('address') }}</textarea>
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
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('footer')
    <script>
        @if (session()->has('error'))
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session()->get('error') }}",
            });
        @endif

        @if (session()->has('success'))
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif

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
                                $("#sponsor_id").closest('.form-group').find('.error-message').text('');
                            } else {
                                $("#sponsor_name").val("Sponsor not found.");
                                $("#sponsor_id").closest('.form-group').find('.error-message').text('Sponsor not found');
                            }
                        },
                        error: function() {
                            $("#sponsor_name").val("Error fetching sponsor.");
                            $("#sponsor_id").closest('.form-group').find('.error-message').text('Error fetching sponsor');
                        }
                    });
                } else {
                    $("#sponsor_name").val("");
                }
            });

            // Handle form submission with confirmation
            $('#user-form').submit(function(e) {
                e.preventDefault(); // Stop default submission

                Swal.fire({
                    title: "Are you sure?",
                    text: "Please confirm if your details are correct.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#667eea",
                    cancelButtonColor: "#d33",
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
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                    },
                    success: function(data) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-user-check mr-2"></i>Register User');
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
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-user-check mr-2"></i>Register User');
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

            // Clear error messages on input change
            $('input, textarea').on('input change', function() {
                $(this).closest('.form-group').find('.error-message').text('');
            });

            // Phone number validation (only numbers)
            $('#phone_no').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Pincode validation (only numbers)
            $('#pincode').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endsection
