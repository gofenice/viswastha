@extends('Admin.admin_header')
@section('title', 'vishwastha | My Team')
@section('content')
    <style>
        .bg-custom {
            background-color: #007bff !important;
        }

        .inner {
            color: #fff !important;
        }

        .back-parent {
            display: flex;
        }

        .parent_button {
            margin: 0 auto;
        }

        .rank-bg-no-rank {
            background-color: #ccc;
            color: black;
        }

        .rank-bg-gold {
            background-color: #FFD700;
            color: black;
        }

        .rank-bg-platinum {
            background-color: #E5E4E2;
            color: black;
        }

        .rank-bg-pearl {
            background-color: #F5F5DC;
            color: black;
        }

        .rank-bg-ruby {
            background-color: #9B111E;
            color: white;
        }

        .rank-bg-diamond {
            background-color: #B9F2FF;
            color: black;
        }

        .rank-bg-double-diamond {
            background-color: #8FD3F4;
            color: black;
        }

        .rank-bg-emerald {
            background-color: #50C878;
            color: white;
        }

        .rank-bg-crown {
            background-color: #800080;
            color: white;
        }

        .rank-bg-royal-crown {
            background-color: #4B0082;
            color: white;
        }

        .rank-bg-manager {
            background-color: #FF8C00;
            color: white;
        }

        .rank-bg-ambassador {
            background-color: #FF4500;
            color: white;
        }

        .rank-bg-royal-crown-ambassador {
            background-color: #DC143C;
            color: white;
        }
    </style>
    @php
        $basic_package_ids = array_column($available_packages['basic'], 'id');
        $premium_package_ids = array_column($available_packages['premium'], 'id');
    @endphp
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Team</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Team List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            @if ($user->pan_card_no === $loggedUser->pan_card_no || (Auth::check() && Auth::user()->role === 'superadmin'))
                <div class="row" style="justify-content: center">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h4><b>{{ $user->name }}</b></h4>

                                <p>{{ $user->connection }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>₹{{ $bonus }}</h3>

                                {{-- <p>Bonus</p> --}}
                                <p>Special Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                                <h3>₹{{ $referrelbasic ?? 0 }}</h3>

                                <p>Basic Referral Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>₹{{ $levelbasic ?? 0 }}</h3>

                                <p>⁠Basic Level Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-custom">
                            <div class="inner">
                                <h3>₹{{ $referrelpremium }}</h3>

                                <p> ⁠Premium Referral Incentive </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-custom">
                            <div class="inner">
                                <h3>₹{{ $levelpremium ?? 0 }}</h3>

                                <p>⁠Premium Level Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>₹{{ $rankincome ?? 0 }}</h3>
                                <p>Premium Rank Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>₹{{ $basicRankincome }}</h3>

                                <p>Basic Rank Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-indigo">
                            <div class="inner">
                                <h3>₹{{ $royaltyIncome }}</h3>

                                <p>Royalty Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-fuchsia">
                            <div class="inner">
                                <h3>{{ $PrivilegeIncome }}</h3>

                                <p>Privilege Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-navy">
                            <div class="inner">
                                <h3>₹{{ $BoardIncome }}</h3>

                                <p>Board Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-maroon">
                            <div class="inner">
                                <h3>₹{{ $ExecutiveIncome }}</h3>

                                <p>Executive Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 col-6">
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3>₹{{ $IncentiveIncome }}</h3>

                                <p>Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>₹{{ $repurchaseTotal }}</h3>

                                <p>Repurchase Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>₹{{ $selfpurchaseTotal }}</h3>

                                <p>Self Purchase Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>₹{{ $franchiseeTotal }}</h3>

                                <p>Franchisee Incentive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <!-- <h2>Binary Tree Structure</h2> -->
            <div class="row d-flex">
                <div class="col-md-4" style="margin: 0 auto;">
                    <form action="{{ route('sunflower') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" name="user_code" class="form-control" placeholder="Enter User ID"
                                    required>
                                <button type="submit" class="btn btn-primary ml-3">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if ($sponsor && $user->id !== auth()->id())
                <div class="back-parent">
                    <a href="{{ route('sunflower', ['id' => $sponsor->id]) }}"
                        class="btn btn-primary mb-3 parent_button">Back
                        to Sponsor</a>
                </div>
            @endif
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h3><b>{{ $user->name }}'s</b> Team <button class="btn btn-primary no-user clickable"
                        data-parent-id="2" data-position-index="left"
                        {{ empty($user->userPackages->pluck('package_id')->toArray()) ? 'disabled' : '' }}><i
                            class="fas fa-plus"></i> New User</button>
                    <button class="btn btn-info"><a style="color: white;"
                            href="{{ route('user_registration_form') }}"><i class="fas fa-plus"></i> New User Without Pan
                            Card</a></button>
                </h3>
                <div class="mb-3">
                    <p class="mb-0"><b>Rank : </b> <span
                            class="badge rank-bg-{{ Str::slug($user->rank->rank_name, '-') }}">
                            {{ $user->rank->rank_name }}</span></p>
                    <b>Package :</b>

                    @php
                        $user_packages = $user->userPackages->pluck('package_id')->toArray();
                        if (
                            !empty(array_intersect($user_packages, $premium_package_ids)) &&
                            !empty(array_intersect($user_packages, $basic_package_ids))
                        ) {
                            echo '<span class="badge badge-info">' .
                                $user->userPackages->pluck('package.name')->join(', ') .
                                '</span>';
                        } else {
                            echo '<button class="btn btn-primary btn-sm disable-new-user btn-add-package' .
                                ($downline->border_class ?? '') .
                                '" data-user="' .
                                $user->id .
                                '" data-packages="' .
                                json_encode($user_packages) .
                                '" data-premium="' .
                                json_encode($premium_package_ids) .
                                '" data-basic="' .
                                json_encode($basic_package_ids) .
                                '">Activate your Package</button>';
                        }
                    @endphp
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered nested-table text-center" id="example1">
                        <thead class="bg-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Package</th>
                                <th>Package Activation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($downlines as $index => $downline)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('sunflower', $downline->id) }}">
                                            {{ $downline->name }} <br> {{ $downline->connection }}
                                        </a>
                                    </td>
                                    <td> <span
                                            class="badge rank-bg-{{ Str::slug($downline->rank->rank_name, '-') }}">{{ $downline->rank->rank_name }}</span>
                                    </td>
                                    <td>
                                        @if ($downline->userPackages->isNotEmpty())
                                            {{ $downline->userPackages->pluck('package.name')->join(', ') }}
                                        @else
                                            <span style="color: red;">No package available for this user</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $user_packages = $downline->userPackages->pluck('package_id')->toArray();
                                            if (
                                                !empty(array_intersect($user_packages, $premium_package_ids)) &&
                                                !empty(array_intersect($user_packages, $basic_package_ids))
                                            ) {
                                                echo '<span class="badge badge-success">Done</span>';
                                            } else {
                                                echo '<button class="btn btn-primary btn-sm btn-add-package ' .
                                                    ($downline->border_class ?? '') .
                                                    '" data-user="' .
                                                    $downline->id .
                                                    '" data-packages="' .
                                                    json_encode($user_packages) .
                                                    '" data-premium="' .
                                                    json_encode($premium_package_ids) .
                                                    '" data-basic="' .
                                                    json_encode($basic_package_ids) .
                                                    '"><i class="fas fa-plus"></i></button>';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Teams Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="user-form" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">User Registration</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label for="ancestors_select" class="form-label">Sponsor ID</label>
                            <input type="text" name="sponsor_id" id="sponsor_id" class="form-control"
                                value="{{ old('sponsorId') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ancestors_select" class="form-label">Sponsor Name</label>
                            <input type="text" name="sponsor_name" id="sponsor_name" class="form-control"
                                value="{{ old('sponsor_name') }}" readonly>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pan_card_no" class="form-label">PAN Card Number</label>
                            <input type="text" name="pan_card_no" id="pan_card_no" class="form-control"
                                oninput="this.value = this.value.toUpperCase()" value="{{ old('pan_card_no') }}"
                                required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no" class="form-label">Phone Number</label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control"
                                value="{{ old('phone_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="pincode" class="form-label">Pin Code</label>
                            <input type="text" name="pincode" id="pincode" class="form-control"
                                value="{{ old('pincode') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" required></textarea>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12 pb-0 mb-0">
                            <input name="psgdpr" type="checkbox" value="1" required checked>
                            <label>There is no refund for activation products</label>
                        </div>
                        <div class="form-group col-md-12">
                            <input name="psgdpr" type="checkbox" value="1" required checked>
                            <label>I agree to the <a href="{{ route('terms') }}">Terms and Conditions </a>and the Privacy
                                Policy</label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updatePinForm" method="POST" action="{{ route('update_pin') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="packageModalLabel">Package Activation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="package_id">Select Package</label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">--Choose Option---</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pinId">Available Pins</label>
                            <select class="form-control" id="pinId" name="pin_id" required>
                                <option value="">--Choose Pin---</option>
                            </select>
                        </div>
                        <input type="hidden" class="form-control" id="userid" name="userid">
                        <div class="form-group">
                            <label for="product_id">Select Product</label>
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">--Choose Product---</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Activate</button>
                    </div>
                </form>

            </div>
        </div>
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
                            }).then(() => {
                                window.location.reload();
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


        $(document).ready(function() {
            $('.no-user.clickable').click(function(e) {
                e.preventDefault();

                $('#modal-lg').modal('show');

            });

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

            // $(document).on('click', '.btn-add-package', function(e) {
            //     e.preventDefault();

            //     const user_id = $(this).data('user');
            //     const packages = $(this).data('packages');

            //     $('#packageModal').find('#userid').val(user_id);
            //     $("#package_id option").each(function() {
            //         if (packages.includes(parseInt($(this).val()))) {
            //             $(this).hide();
            //         } else {
            //             $(this).show();
            //         }
            //     });

            //     $('#updatePinForm .error-message').text("");
            //     $('#packageModal').modal('toggle');
            // });

            $(document).on('click', '.btn-add-package', function(e) {
                e.preventDefault();

                const user_id = $(this).data('user');
                let user_packages = $(this).data('packages');
                let premium_ids = $(this).data('premium');
                let basic_ids = $(this).data('basic');

                // Convert everything to arrays of integers
                user_packages = Array.isArray(user_packages) ? user_packages.map(Number) : JSON.parse(
                    user_packages).map(Number);
                premium_ids = Array.isArray(premium_ids) ? premium_ids.map(Number) : JSON.parse(premium_ids)
                    .map(Number);
                basic_ids = Array.isArray(basic_ids) ? basic_ids.map(Number) : JSON.parse(basic_ids).map(
                    Number);

                // Check if the user already has a basic or premium package
                const hasPremium = user_packages.some(id => premium_ids.includes(id));
                const hasBasic = user_packages.some(id => basic_ids.includes(id));

                $('#packageModal').find('#userid').val(user_id);

                $("#package_id option").each(function() {
                    const optionVal = parseInt($(this).val());

                    if (user_packages.includes(optionVal)) {
                        $(this).hide(); // Already owned package
                    } else if (hasPremium && premium_ids.includes(optionVal)) {
                        $(this).hide(); // User has a premium, hide all premium
                    } else if (hasBasic && basic_ids.includes(optionVal)) {
                        $(this).hide(); // User has a basic, hide all basic
                    } else {
                        $(this).show(); // Allowed package
                    }
                });

                $('#updatePinForm .error-message').text("");
                $('#packageModal').modal('toggle');
            });


            $('#package_id').on('change', function() {
                const packageId = $(this).val();
                if (packageId) {
                    $.ajax({
                        url: '/get-available-pins', // Update this with the correct route
                        method: 'GET',
                        data: {
                            package_id: packageId
                        },
                        success: function(response) {
                            $('#pinId').empty().append(
                                '<option value="">--Choose Pin---</option>');
                            if (response.pins && response.pins.length > 0) {
                                response.pins.forEach(pin => {
                                    $('#pinId').append(
                                        `<option value="${pin.id}">${pin.unique_id}</option>`
                                    );
                                });
                            } else {
                                $('#pinId').append(
                                    '<option value="">No Pins Available</option>');
                            }
                            // Populate products
                            $('#product_id').empty().append(
                                '<option value="">--Choose Product---</option>');
                            if (response.products && response.products.length > 0) {
                                response.products.forEach(product => {
                                    $('#product_id').append(
                                        `<option value="${product.id}">${product.product_name}</option>`
                                    );
                                });
                            } else {
                                $('#product_id').append(
                                    '<option value="">No Products Available</option>');
                            }
                        },
                        error: function() {
                            alert('Failed to fetch available pins. Please try again.');
                        }
                    });
                } else {
                    $('#pinId').empty().append('<option value="">--Choose Pin---</option>');
                    $('#product_id').empty().append('<option value="">--Choose Product---</option>');
                }
            });

        });
        $(document).ready(function() {
            const teamLink = $('.nav-link.team');
            const treeviewLink = $('.nav.nav-treeview.team');
            const mainLiLink = $('.nav-item.has-treeview.team');
            const binaryLink = $('.nav-link.binary');
            if (binaryLink.length) {
                binaryLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("user-form");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                // Disable button to prevent multiple clicks
                submitBtn.disabled = true;
                // Change button text to loading
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            });
        });
    </script>
    {{-- <script src="{{ asset('assets/dist/js/viewpage/binary_tree.js?v=0.16') }}"></script> --}}
@endsection
