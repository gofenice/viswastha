<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register - Vishwastha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="{{ asset('assets/dist/js/sweetalert.js') }}"></script>
</head>

<style>
    body.register-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .register-box {
        width: 720px !important;
        margin: 30px auto;
    }

    @media (max-width: 768px) {
        .register-box {
            width: 95% !important;
            margin: 15px auto;
        }
    }

    .logo-wrap {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo-wrap img {
        width: 180px;
    }

    .reg-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .reg-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 24px 32px;
        text-align: center;
    }

    .reg-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
        letter-spacing: 0.3px;
    }

    .reg-card-header p {
        margin: 6px 0 0;
        font-size: 13px;
        opacity: 0.85;
    }

    .reg-card-body {
        padding: 28px 32px 24px;
    }

    @media (max-width: 576px) {
        .reg-card-body {
            padding: 20px 16px 18px;
        }
        .reg-card-header {
            padding: 18px 16px;
        }
    }

    .form-group label {
        font-weight: 600;
        color: #444;
        font-size: 13px;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color 0.25s, box-shadow 0.25s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.2);
        outline: none;
    }

    .form-control[readonly] {
        background: #f8f9fa;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }

    /* Sponsor radio group */
    .sponsor-radio-group {
        display: flex;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .sponsor-radio-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        color: #444;
        padding: 10px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.2s;
        user-select: none;
    }

    .sponsor-radio-label:hover {
        border-color: #667eea;
        color: #667eea;
    }

    .sponsor-radio-label input[type="radio"] {
        accent-color: #667eea;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .sponsor-radio-label.selected {
        border-color: #667eea;
        background: #f0f2ff;
        color: #667eea;
    }

    /* Sponsor fields section */
    #sponsorFields {
        display: none;
        background: #f8f9ff;
        border: 1px solid #d0d5f7;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
    }

    /* Sponsor validation states */
    .sponsor-valid .form-control {
        border-color: #28a745;
    }

    .sponsor-invalid .form-control {
        border-color: #dc3545;
    }

    .sponsor-status {
        font-size: 12px;
        margin-top: 4px;
        font-weight: 500;
    }

    /* Password toggle */
    .input-group .form-control:not(:last-child) {
        border-right: none;
        border-radius: 8px 0 0 8px;
    }

    .password-toggle-btn {
        border: 2px solid #e0e0e0;
        border-left: none;
        border-radius: 0 8px 8px 0;
        background: #f8f9fa;
        color: #667eea;
        padding: 0 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }

    .password-toggle-btn:hover {
        background: #667eea;
        color: #fff;
        border-color: #667eea;
    }

    /* Submit / Reset buttons */
    .form-footer {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        padding: 12px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.25s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(102, 126, 234, 0.55);
        color: #fff;
    }

    .btn-submit:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .btn-reset {
        background: #6c757d;
        border: none;
        color: #fff;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.25s;
    }

    .btn-reset:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: #fff;
    }

    @media (max-width: 480px) {
        .btn-submit, .btn-reset {
            width: 100%;
        }
    }

    .error-message {
        font-size: 12px;
        margin-top: 4px;
        display: block;
        font-weight: 500;
    }

    .login-link {
        display: block;
        text-align: center;
        padding: 14px;
        color: #667eea;
        font-size: 14px;
        font-weight: 500;
        border-top: 1px solid #f0f0f0;
        text-decoration: none;
    }

    .login-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .section-divider {
        font-size: 12px;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 18px 0 12px;
    }
</style>

<body class="hold-transition register-page">
    <div class="register-box">

        <div class="logo-wrap">
            <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Vishwastha Logo">
        </div>

        <div class="reg-card">
            <div class="reg-card-header">
                <h4><i class="fas fa-user-plus mr-2"></i>Create Your Account</h4>
                <p>Join Vishwastha — it's free and only takes a minute</p>
            </div>

            <div class="reg-card-body">

                @if (session()->has('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({ icon: 'error', title: 'Error', text: '{{ session()->get('error') }}' });
                        });
                    </script>
                @endif

                <form id="public-reg-form" action="{{ route('register.store.wpan_rr') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_source" value="public_free">

                    <!-- Sponsor Radio -->
                    <div class="form-group">
                        <label>Do you have a Sponsor? <span class="required-star">*</span></label>
                        <div class="sponsor-radio-group" id="sponsor-toggle-group">
                            <label class="sponsor-radio-label selected" id="lbl-no">
                                <input type="radio" name="has_sponsor" value="no" id="radio-no" checked>
                                <i class="fas fa-user-slash"></i> I don't have a Sponsor
                            </label>
                            <label class="sponsor-radio-label" id="lbl-yes">
                                <input type="radio" name="has_sponsor" value="yes" id="radio-yes">
                                <i class="fas fa-user-check"></i> I have a Sponsor
                            </label>
                        </div>
                    </div>

                    <!-- Sponsor Fields (shown when "I have a sponsor" selected) -->
                    <div id="sponsorFields">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="sponsor-id-group">
                                    <label for="sponsor_id">
                                        <i class="fas fa-id-badge mr-1"></i>Sponsor ID <span class="required-star">*</span>
                                    </label>
                                    <input type="text" name="sponsor_id" id="sponsor_id"
                                        class="form-control"
                                        placeholder="Enter Sponsor ID"
                                        oninput="this.value = this.value.toUpperCase()"
                                        autocomplete="off">
                                    <span class="sponsor-status" id="sponsor-status-msg"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sponsor_name">
                                        <i class="fas fa-user mr-1"></i>Sponsor Name
                                    </label>
                                    <input type="text" name="sponsor_name" id="sponsor_name"
                                        class="form-control"
                                        placeholder="Auto-filled after validation"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="section-divider"><i class="fas fa-info-circle mr-1"></i>Personal Details</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-signature mr-1"></i>Full Name <span class="required-star">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" placeholder="Enter full name" required>
                                <span class="error-message text-danger" id="err-name"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope mr-1"></i>Email Address <span class="required-star">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email') }}" placeholder="example@email.com" required>
                                <span class="error-message text-danger" id="err-email"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_no">
                                    <i class="fas fa-phone mr-1"></i>Phone Number <span class="required-star">*</span>
                                </label>
                                <input type="text" name="phone_no" id="phone_no" class="form-control"
                                    value="{{ old('phone_no') }}" placeholder="10-digit mobile number"
                                    maxlength="10" required>
                                <span class="error-message text-danger" id="err-phone_no"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pincode">
                                    <i class="fas fa-map-pin mr-1"></i>Pin Code <span class="required-star">*</span>
                                </label>
                                <input type="text" name="pincode" id="pincode" class="form-control"
                                    value="{{ old('pincode') }}" placeholder="6-digit PIN code"
                                    maxlength="6" required>
                                <span class="error-message text-danger" id="err-pincode"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Address <span class="required-star">*</span>
                                </label>
                                <textarea name="address" id="address" class="form-control"
                                    placeholder="Enter your complete address" required>{{ old('address') }}</textarea>
                                <span class="error-message text-danger" id="err-address"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="section-divider"><i class="fas fa-lock mr-1"></i>Set Password</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">
                                    Password <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Min 8 chars, upper + lower + special" required>
                                    <button type="button" class="password-toggle-btn" id="togglePassword">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <span class="error-message text-danger" id="err-password"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">
                                    Confirm Password <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Re-enter password" required>
                                    <button type="button" class="password-toggle-btn" id="toggleConfirmPassword">
                                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                    </button>
                                </div>
                                <span class="error-message text-danger" id="err-password_confirmation"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="reset" class="btn btn-reset" id="resetBtn">
                            <i class="fas fa-redo mr-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-submit" id="submitBtn">
                            <i class="fas fa-user-check mr-2"></i>Create Account
                        </button>
                    </div>
                </form>
            </div>

            <a href="{{ route('/') }}" class="login-link">
                <i class="fas fa-sign-in-alt mr-1"></i>Already have an account? Sign in
            </a>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            // --- Sponsor Radio Toggle ---
            var sponsorValidated = false;

            function updateRadioStyles() {
                var hasYes = $('#radio-yes').is(':checked');
                $('#lbl-no').toggleClass('selected', !hasYes);
                $('#lbl-yes').toggleClass('selected', hasYes);

                if (hasYes) {
                    $('#sponsorFields').slideDown(200);
                } else {
                    $('#sponsorFields').slideUp(200);
                    clearSponsor();
                }
                updateSubmitState();
            }

            $('input[name="has_sponsor"]').on('change', updateRadioStyles);
            updateRadioStyles();

            // --- Sponsor ID Validation ---
            function clearSponsor() {
                sponsorValidated = false;
                $('#sponsor_id').val('');
                $('#sponsor_name').val('');
                $('#sponsor-id-group').removeClass('sponsor-valid sponsor-invalid');
                $('#sponsor-status-msg').text('').removeClass('text-success text-danger');
            }

            $('#resetBtn').on('click', function () {
                setTimeout(function () {
                    clearSponsor();
                    // reset radio to "no"
                    $('#radio-no').prop('checked', true);
                    updateRadioStyles();
                    $('.error-message').text('');
                }, 50);
            });

            var sponsorAjax = null;

            $('#sponsor_id').on('blur', function () {
                var sponsorId = $(this).val().trim();

                if (sponsorAjax) {
                    sponsorAjax.abort();
                }

                if (!sponsorId) {
                    clearSponsor();
                    updateSubmitState();
                    return;
                }

                $('#sponsor-status-msg')
                    .text('Validating...')
                    .removeClass('text-success text-danger')
                    .css('color', '#888');

                sponsorAjax = $.ajax({
                    url: '/fetch-sponsor-name',
                    type: 'POST',
                    data: {
                        sponsor_id: sponsorId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            sponsorValidated = true;
                            $('#sponsor_name').val(response.name);
                            $('#sponsor-id-group').removeClass('sponsor-invalid').addClass('sponsor-valid');
                            $('#sponsor-status-msg')
                                .text('✓ Valid sponsor')
                                .removeClass('text-danger')
                                .addClass('text-success');
                        } else {
                            sponsorValidated = false;
                            $('#sponsor_name').val('');
                            $('#sponsor-id-group').removeClass('sponsor-valid').addClass('sponsor-invalid');
                            $('#sponsor-status-msg')
                                .text('✗ Sponsor ID not found')
                                .removeClass('text-success')
                                .addClass('text-danger');
                        }
                        updateSubmitState();
                    },
                    error: function (xhr) {
                        if (xhr.statusText === 'abort') return;
                        sponsorValidated = false;
                        $('#sponsor-status-msg')
                            .text('Error validating sponsor. Try again.')
                            .removeClass('text-success')
                            .addClass('text-danger');
                        updateSubmitState();
                    }
                });
            });

            // Re-validate if sponsor ID is changed after a previous validation
            $('#sponsor_id').on('input', function () {
                if (sponsorValidated) {
                    sponsorValidated = false;
                    $('#sponsor_name').val('');
                    $('#sponsor-id-group').removeClass('sponsor-valid sponsor-invalid');
                    $('#sponsor-status-msg').text('').removeClass('text-success text-danger');
                    updateSubmitState();
                }
            });

            function updateSubmitState() {
                var hasSponsor = $('#radio-yes').is(':checked');
                if (hasSponsor && !sponsorValidated) {
                    $('#submitBtn').prop('disabled', true);
                } else {
                    $('#submitBtn').prop('disabled', false);
                }
            }

            // --- Password Toggle ---
            $('#togglePassword').on('click', function () {
                var inp = $('#password');
                var icon = $('#togglePasswordIcon');
                if (inp.attr('type') === 'password') {
                    inp.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    inp.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $('#toggleConfirmPassword').on('click', function () {
                var inp = $('#password_confirmation');
                var icon = $('#toggleConfirmPasswordIcon');
                if (inp.attr('type') === 'password') {
                    inp.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    inp.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // --- Form Submit ---
            $('#public-reg-form').on('submit', function (e) {
                e.preventDefault();

                var hasSponsor = $('#radio-yes').is(':checked');
                if (hasSponsor && !sponsorValidated) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Sponsor',
                        text: 'Please enter a valid Sponsor ID before submitting.',
                    });
                    return;
                }

                // If no sponsor, clear sponsor_id so backend ignores it
                if (!hasSponsor) {
                    $('#sponsor_id').val('');
                }

                Swal.fire({
                    title: 'Confirm Registration',
                    text: 'Please confirm your details are correct before submitting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Register!',
                    cancelButtonText: 'Review Again',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            });

            function submitForm() {
                var form = $('#public-reg-form');
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Registering...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-user-check mr-2"></i>Create Account');
                        form.find('.error-message').text('');

                        if (data.status === 'validation') {
                            $.each(data.errors, function (key, msg) {
                                $('#err-' + key).text(msg);
                            });
                            Swal.fire({
                                icon: 'warning',
                                title: 'Please fix the errors',
                                text: 'Some fields have validation errors. Please review and correct them.',
                            });
                        } else if (data.status === 'error') {
                            Swal.fire({ icon: 'error', title: 'Error!', text: data.message });
                        } else if (data.status === 'success') {
                            form[0].reset();
                            clearSponsor();
                            updateRadioStyles();
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                html: '<p>Your account has been created.</p>' +
                                      '<p>Username: <strong>' + data.connection + '</strong></p>' +
                                      '<p>Password: <strong>' + data.password + '</strong></p>' +
                                      '<p class="text-muted" style="font-size:12px">Please save these credentials.</p>',
                                confirmButtonText: 'Go to Login',
                            }).then(function () {
                                window.location.href = '{{ route('/') }}';
                            });
                        }
                    },
                    error: function () {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-user-check mr-2"></i>Create Account');
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: 'Please try again. If the problem persists, contact support.',
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>
