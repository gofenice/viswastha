@extends('Admin.admin_header')
@section('title', 'vishwastha | KYC Verification')
@section('content')
    <style>
        .kyc-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .kyc-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }

        .kyc-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .kyc-header i {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .kyc-header h2 {
            margin: 0 0 10px 0;
            font-weight: 700;
            font-size: 28px;
        }

        .kyc-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .kyc-body {
            padding: 40px 30px;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .info-box h5 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-weight: 600;
        }

        .info-box p {
            margin: 0;
            color: #666;
            line-height: 1.6;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .user-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .user-info-item:last-child {
            border-bottom: none;
        }

        .user-info-label {
            font-weight: 600;
            color: #555;
        }

        .user-info-value {
            color: #333;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 14px 18px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .btn-submit-kyc {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 18px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit-kyc:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-submit-kyc:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            font-size: 0.875rem;
            margin-top: 8px;
            display: block;
            font-weight: 500;
        }

        .required-star {
            color: #dc3545;
            font-weight: bold;
        }

        .pan-format-hint {
            font-size: 0.875rem;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
    </style>

    <div class="content-wrapper">
        <div class="kyc-container">
            <div class="kyc-card">
                <div class="kyc-header">
                    <i class="fas fa-shield-alt"></i>
                    <h2>KYC Verification Required</h2>
                    <p>Complete your verification to access all features</p>
                </div>

                <div class="kyc-body">
                    <div class="info-box">
                        <h5><i class="fas fa-info-circle mr-2"></i>Why KYC?</h5>
                        <p>To ensure security and compliance, we need to verify your PAN card information. This is a one-time process and will unlock full access to your account.</p>
                    </div>

                    <div class="user-info">
                        <h5 class="mb-3"><i class="fas fa-user mr-2"></i>Your Information</h5>
                        <div class="user-info-item">
                            <span class="user-info-label">Name:</span>
                            <span class="user-info-value">{{ $user->name }}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="user-info-label">User ID:</span>
                            <span class="user-info-value">{{ $user->connection }}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="user-info-label">Email:</span>
                            <span class="user-info-value">{{ $user->email }}</span>
                        </div>
                    </div>

                    <form id="kyc-form" method="POST" action="{{ route('update_kyc') }}">
                        @csrf

                        <div class="form-group">
                            <label for="pan_card_no">
                                <i class="fas fa-id-card mr-1"></i>PAN Card Number
                                <span class="required-star">*</span>
                            </label>
                            <input type="text"
                                   name="pan_card_no"
                                   id="pan_card_no"
                                   class="form-control"
                                   placeholder="Enter PAN Card Number (e.g., ABCDE1234F)"
                                   maxlength="10"
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                            <small class="pan-format-hint">Format: 5 letters + 4 digits + 1 letter (e.g., ABCDE1234F)</small>
                            <span class="error-message text-danger"></span>
                        </div>

                        <button type="submit" class="btn btn-submit-kyc" id="submitBtn">
                            <i class="fas fa-check-circle mr-2"></i>Verify & Continue
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#kyc-form').submit(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Confirm PAN Card",
                    text: "Please verify that your PAN card number is correct. This cannot be changed later.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#667eea",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Submit!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitKycForm();
                    }
                });
            });

            function submitKycForm() {
                var form = $('#kyc-form');
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                    },
                    success: function(data) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i>Verify & Continue');
                        $('.error-message').text("");

                        if (data.status === "validation") {
                            $.each(data.errors, function(key, val) {
                                $('[name="' + key + '"]').closest('.form-group')
                                    .find('.error-message').text(val);
                            });
                        } else if (data.status === "error") {
                            Swal.fire({
                                icon: "error",
                                title: "Verification Failed",
                                text: data.message,
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            });
                        } else if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "KYC Verified!",
                                text: data.message,
                                showConfirmButton: true,
                                confirmButtonText: "Continue to Dashboard"
                            }).then(() => {
                                window.location.href = "{{ route('adminhome') }}";
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i>Verify & Continue');
                        console.error(error, xhr, status);
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            showConfirmButton: true,
                            confirmButtonText: "OK"
                        });
                    }
                });
            }

            // Clear error message on input
            $('#pan_card_no').on('input', function() {
                $(this).closest('.form-group').find('.error-message').text('');
            });

            // PAN card format validation
            $('#pan_card_no').on('blur', function() {
                var pan = $(this).val();
                var panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]$/;

                if (pan && !panPattern.test(pan)) {
                    $(this).closest('.form-group').find('.error-message')
                        .text('Invalid PAN format. Use: ABCDE1234F');
                }
            });
        });
    </script>
@endsection
