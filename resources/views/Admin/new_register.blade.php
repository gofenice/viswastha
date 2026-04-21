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

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <img class="mlm-logo" src="{{ asset('assets/dist/img/logo.png') }}" alt="logo">
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                <form id="user-reg" action="{{ route('user_register') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="input-group mb-3 col-md-6">
                            <input type="text" class="form-control @error('sponsor_id') input-error @enderror"
                            name="sponsor_id" oninput="this.value = this.value.toUpperCase()"
                            placeholder="@error('sponsor_id') {{ $message }} @else Sponsor ID @enderror"
                            value="{{ request()->get('sponsor_id') ?? ( $errors->has('sponsor_id') ? '' : old('sponsor_id')) }}"  readonly>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="text" class="form-control" name="sponsor_name" placeholder="Sponsor Name"
                            readonly value="{{ request()->get('sponsor_name') ?? old('sponsor_name') }}">                        
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="text" class="form-control @error('name') input-error @enderror"
                                name="name"
                                placeholder="@error('name') {{ $message }} @else Full name @enderror "
                                value="{{ $errors->has('name') ? '' : old('name') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="email" class="form-control @error('email') input-error @enderror"
                                name="email"
                                placeholder="@error('email') {{ $message }} @else Email @enderror "
                                value="{{ $errors->has('email') ? '' : old('email') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-6">
                            <input type="number" class="form-control @error('phone_no') input-error @enderror"
                                name="phone_no"
                                placeholder="@error('phone_no') {{ $message }} @else Phone No @enderror"
                                value="{{ $errors->has('phone_no') ? '' : old('phone_no') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-6">
                            <input type="text" class="form-control @error('pan_card_no') input-error @enderror"
                                name="pan_card_no" oninput="this.value = this.value.toUpperCase()"
                                placeholder="@error('pan_card_no') {{ $message }} @else Pan Card No @enderror"
                                value="{{ $errors->has('pan_card_no') ? '' : old('pan_card_no') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-6">
                            <input type="password" class="form-control @error('password') input-error @enderror"
                                name="password" style="border-right:1px solid #cfd5db"
                                placeholder="@error('password') {{ $message }} @else Password @enderror "
                                value="{{ old('password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fa fa-key"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-6">
                            <input type="password"
                                class="form-control @error('password_confirmation') input-error @enderror"
                                name="password_confirmation" style="border-right:1px solid #cfd5db"
                                placeholder="@error('password_confirmation') {{ $message }} @else Confirm Password @enderror "
                                value="{{ old('password_confirmation') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fa fa-key"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-12">
                            <textarea class="form-control " name="address" style="border-right:1px solid #cfd5db" placeholder="Address">{{ old('address') }}</textarea>
                        </div>
                        <div class="input-group mb-3 col-md-12">
                            <input type="number" class="form-control @error('pincode') input-error @enderror"
                                name="pincode"
                                placeholder="@error('pincode') {{ $message }} @else Pin code number @enderror"
                                value="{{ $errors->has('pincode') ? '' : old('pincode') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fa fa-address-book"></span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="input-group mb-3 col-md-6">
                            <label class="form-control">Package (Optional)</label>
                            <select class="form-control" name="package_id" style="border-right:1px solid #cfd5db"
                                placeholder="Package" id="packageSelect">
                                <option value="">--Choose package--</option>
                                    @foreach ($packages as $package)
                                         <option value="{{ $package->id }}">{{ $package->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div id="extraFields" style="display:none;" class="col-md-12 ">
                            <div class="row">
                                <div class="input-group mb-3 col-md-6">
                                    <input type="text" class="form-control" name="pin_id"
                                        style="border-right:1px solid #cfd5db" placeholder="Pin ID"
                                        value="{{ old('pin_id') }}">
                                </div>
                                <div class="input-group mb-3 col-md-6">
                                    <input type="text" class="form-control" name="pin_password"
                                        style="border-right:1px solid #cfd5db" placeholder="Pin Password"
                                        value="{{ old('pin_password') }}">
                                </div>
                            </div>
                        </div> --}}
                        <div class="input-group mb-3 col-md-12">
                            <input name="psgdpr" type="checkbox" value="1" required>
                            <label class="pb-0 pt-2 pl-2">I agree to the <a
                                    href="{{ route('terms_loginpage') }}">Terms and Conditions </a>and the Privacy
                                Policy</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">

                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
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
        document.getElementById('packageSelect').addEventListener('change', function() {
            var selectedPackage = this.value;
            var extraFields = document.getElementById('extraFields');
            var additionalField = document.getElementById('additionalField');

            // Show extra fields for both "Basic" and "Premium"
            if (selectedPackage === '1' || selectedPackage === '2') {
                extraFields.style.display = 'block';
            } else {
                extraFields.style.display = 'none';
            }
        });

        // Check if fields are filled before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            var additionalField = document.getElementById('additionalField');

            // If extra fields are shown, check if they are empty
            if (additionalField && additionalField.value.trim() === '') {
                alert("Please fill in the additional field before submitting.");
                e.preventDefault(); // Prevent form submission if the field is empty
            }
        });

        $(document).ready(function() {
            // Fetch Sponsor Name
            $("input[name='sponsor_id']").on("blur", function() {
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
                                $("input[name='sponsor_name']").val(response.name);
                            } else {
                                $("input[name='sponsor_name']").val("Sponsor not found.");
                            }
                        },
                        error: function() {
                            $("input[name='sponsor_name']").val("Error fetching sponsor.");
                        }
                    });
                } else {
                    $("input[name='sponsor_name']").val("");
                }
            });

            // Fetch Parent Name and Position Availability
            $("input[name='parent_id']").on("input", function() {
                let parentId = $(this).val();
                if (parentId) {
                    $.ajax({
                        url: "/fetch-parent-info",
                        type: "POST",
                        data: {
                            parent_id: parentId,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            if (response.success) {
                                $("input[name='parent_name']").val(response.name);

                                let positions = response.positions; // ['left', 'right']
                                let options = positions
                                    .map(pos => `<option value="${pos}">${pos}</option>`)
                                    .join("");
                                $("#positionSelect").html(options).show();
                                $("#positionContainer").show();
                                $('#user-reg button[type="submit"]').prop('disabled', false);
                            } else {
                                $("input[name='parent_name']").val(response.message ||
                                    "Parent not found.");
                                $("#positionSelect").html("").hide();
                                $("#positionContainer").hide();
                                $('#user-reg button[type="submit"]').prop('disabled', true);
                            }
                        },
                        error: function() {
                            $("input[name='parent_name']").val("Error fetching parent.");
                            $("#positionSelect").html("").hide();
                            $("#positionContainer").hide();
                        }
                    });
                } else {
                    $("input[name='parent_name']").val("");
                    $("#positionSelect").html("").hide();
                    $("#positionContainer").hide();
                }
            });
        });
        
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("user-reg").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default submission

                Swal.fire({
                    title: "Are you sure?",
                    text: "Please confirm if your details are correct.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, submit!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    </script>
</body>

</html>
