<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shop Registration</title>
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
                <p class="login-box-msg">Register a new Shop</p>

                <form id="user-reg" action="{{ route('shopuserstore') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

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
                                placeholder="@error('email') {{ $message }} @else Email - Don’t enter a number here @enderror "
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
                            <p class="login-box-msg mx-auto">Shop Details</p>
                        </div>


                        <div class="input-group mb-3 col-md-12">
                            <input type="text" name="shop_name"
                                class="form-control @error('shop_name') input-error @enderror" placeholder="Shop Name"
                                value="{{ old('shop_name') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-store"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="file" name="shop_img"
                                class="form-control @error('shop_img') input-error @enderror" placeholder="Shop Image"
                                value="{{ old('shop_img') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-image"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="email" name="shop_email"
                                class="form-control @error('shop_email') input-error @enderror"
                                placeholder="Shop Email" value="{{ old('shop_email') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="number" name="shop_phone_no"
                                class="form-control @error('shop_phone_no') input-error @enderror"
                                placeholder="Shop Phone No" value="{{ old('shop_phone_no') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <input type="text" name="shop_gst"
                                class="form-control @error('shop_gst') input-error @enderror"
                                placeholder="GST Number" value="{{ old('shop_gst') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fa fa-address-book"></span>
                                </div>
                            </div>
                        </div>


                        <!-- District Dropdown -->
                        <div class="input-group mb-3 col-md-6">
                            <select id="district" name="district"
                                class="form-control @error('district_id') input-error @enderror">
                                <option value="">Select District</option>
                                @foreach ($districts as $dist)
                                    <option value="{{ $dist->district_id }}"> {{ $dist->district_name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <select id="localbodytype" name="localbodytype"
                                class="form-control @error('state_id') input-error @enderror">
                                <option value="">Select Local body type</option>
                                @foreach ($localbodytypes as $localbodytype)
                                    <option value="{{ $localbodytype->id }}">{{ $localbodytype->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3 col-md-6">
                            <select class="form-control" name="localbody" id="localbody">
                                <option>-- Choose option --</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-6">
                            <select name="status" class="form-control @error('status') input-error @enderror">
                                <option value="1">Local Shop</option>
                                <option value="2">International Shop</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-flag"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3 col-md-12">
                            <textarea class="form-control" name="shop_address" placeholder="Shop Address">{{ old('shop_address') }}</textarea>
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
        // Check if fields are filled before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            var additionalField = document.getElementById('additionalField');

            // If extra fields are shown, check if they are empty
            if (additionalField && additionalField.value.trim() === '') {
                alert("Please fill in the additional field before submitting.");
                e.preventDefault(); // Prevent form submission if the field is empty
            }
        });

        function fetchLocalBodies() {

            let usedLocalBodies = @json($usedLocalBodies ?? []);
            let districtId = $('#district').val();
            let typeId = $('#localbodytype').val();

            if (districtId && typeId) {
                $.ajax({
                    url: '{{ route('get_localbodies') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        district_id: districtId,
                        type_id: typeId
                    },
                    success: function(response) {
                        $('#localbody').empty().append('<option value="">-- Choose Local Body --</option>');
                        $.each(response, function(key, localbody) {
                            if (!usedLocalBodies.includes(localbody.id)) {
                                $('#localbody').append(
                                    `<option value="${localbody.id}">${localbody.name}</option>`);
                            }
                        });
                    }
                });
            }
        }

        $('#district, #localbodytype').change(fetchLocalBodies);

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
