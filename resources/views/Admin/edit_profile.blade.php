@extends('Admin.admin_header')
@section('title', 'vishwastha | Edit Profile')
@section('content')
    <style>
        .profile-user-img {
            width: 128px;
            height: 128px;
            object-fit: cover;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Profile</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="updateProfileForm" method="POST" action="{{ route('edit_profile') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ $user->user_image ? asset($user->user_image) : asset('assets/dist/img/images.jpg') }}"
                                            alt="User profile picture">
                                    </div>

                                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                                    @php
                                        $ranks = [
                                            '1' => ['icon' => '', 'color' => '#cd7f32'], // No rank
                                            '2' => ['icon' => '🏅', 'color' => '#ffd700'], // Gold
                                            '3' => ['icon' => '💿', 'color' => '#e5e4e2'], // Platinum
                                            '4' => ['icon' => '🔵', 'color' => '#0f52ba'], // Pearl
                                            '5' => ['icon' => '🔴', 'color' => '#e0115f'], // Ruby
                                            '6' => ['icon' => '⚪', 'color' => '#f5f5f5'], // Diamond
                                            '7' => ['icon' => '🔷', 'color' => '#1e90ff'], // Double Diamond
                                            '8' => ['icon' => '🟢', 'color' => '#00ff7f'], // Emerald
                                            '9' => ['icon' => '👑', 'color' => '#daa520'], // Crown
                                            '10' => ['icon' => '💎', 'color' => '#b9f2ff'], // Royal Crown
                                            '11' => ['icon' => '📋', 'color' => '#4682b4'], // Manager
                                            '12' => ['icon' => '🌍', 'color' => '#ff4500'], // Ambassador
                                            '13' => ['icon' => '🌟', 'color' => '#ff6347'], // Royal Crown Ambassador
                                        ];

                                        $userRank = Auth::user()->role === 'superadmin' ? '13' : $user->rank_id;
                                        $rankData = $ranks[$userRank] ?? ['icon' => '⭐', 'color' => '#d3d3d3'];
                                    @endphp

                                    <div class="rank-display text-center"
                                        style="background-color: {{ $rankData['color'] }}; padding: 10px; border-radius: 10px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); margin-top: 10px;">
                                        <span class="rank-icon"
                                            style="font-size: 1.5em; margin-right: 10px;">{!! $rankData['icon'] !!}</span>
                                        <span class="rank-text" style="font-size: 1.2em; font-weight: bold; color: #333;">
                                            {{ Auth::user()->role === 'superadmin' ? 'Ambassador' : (Auth::user()->rank_id == 1 ? 'No rank' : $rankName) }}
                                        </span>

                                    </div>

                                    {{-- Basic Rank --}}

                                    @php
                                        // Basic Star Ranks (1–5 stars)
                                        $basicRanks = [
                                            '1' => ['icon' => '', 'color' => '#cd7f32'], // No rank
                                            '2' => ['icon' => '⭐', 'color' => '#007bff'], // 1 Star
                                            '3' => ['icon' => '⭐⭐', 'color' => '#007bff'], // 2 Star
                                            '4' => ['icon' => '⭐⭐⭐', 'color' => '#007bff'], // 3 Star
                                            '5' => ['icon' => '⭐⭐⭐⭐', 'color' => '#007bff'], // 4 Star
                                            '6' => ['icon' => '⭐⭐⭐⭐⭐', 'color' => '#007bff'], // 5 Star
                                        ];
                                    @endphp

                                    @if ($basicRank->isNotEmpty())
                                        @foreach ($basicRank as $rank)
                                            @php
                                                $rankData = $basicRanks[$rank->basic_rank_id] ?? [
                                                    'icon' => '⭐',
                                                    'color' => '#d3d3d3',
                                                ];
                                            @endphp

                                            <div class="rank-display text-center"
                                                style="background-color: {{ $rankData['color'] }}; 
                   padding: 5px; 
                   border-radius: 10px; 
                   box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); 
                   margin-top: 10px;">
                                                <div class="rank-icon" style="font-size: 1.5em; margin-right: 10px;">
                                                    {!! $rankData['icon'] !!}
                                                </div>
                                                {{-- <span class="rank-text"
                                                style="font-size: 1.2em; font-weight: bold; color: #333;">
                                                Basic Rank {{ $rank->basic_rank_id - 1 }} Star
                                            </span> --}}
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- <div class="text-center text-muted" style="margin-top: 10px;">
                                        No Basic Rank Achieved Yet
                                    </div> --}}
                                    @endif

                                    {{-- End Basic Rank --}}

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Change Password</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="card-body">
                                        <div class="form-group ">
                                            <label for="inputPassword3" class="col-form-label">Old Password</label>
                                            <input type="password" class="form-control" id="inputPassword3"
                                                name="old_password" placeholder="Old Password">
                                            @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword3" class="col-form-label">New Password</label>
                                            <input type="password" class="form-control" id="inputPassword3" name="password"
                                                placeholder="New Password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword3" class="col-form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="inputPassword3"
                                                name="password_confirmation" placeholder="Confirm New Password">

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <!-- /.card-footer -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-md-9" style="margin: 0 auto;">
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <!-- /.card-header -->
                                <!-- form start -->
                                @php
                                    $packageDetails = $userPackages->isNotEmpty()
                                        ? $userPackages
                                            ->map(function ($userPackage) {
                                                if ($userPackage->package) {
                                                    return $userPackage->package->name;
                                                }
                                                return 'Unknown Package - Unknown Amount';
                                            })
                                            ->implode(', ')
                                        : 'N/A';
                                @endphp
                                <div class="card-header">
                                    <h3 class="card-title">User Details</h3>
                                </div>

                                <div class="card-body">

                                    <div class="form-group row">
                                        <label for="User_id" class="col-sm-4 col-form-label">User ID</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <input type="text" class="form-control" id="User_id"
                                                value="{{ $user->connection ?? 'VM-1' }}" disabled>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row">
                                        <label for="exampleInputEmail1" class="col-sm-4 col-form-label">Your Position</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $user->position ?? 'Main' }}" disabled>
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-4 col-form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="name" name="user_name"
                                                value="{{ $user->name }}" required readonly>
                                            @error('user_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="mobile" class="col-sm-4 col-form-label">Tel/Mobile <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                value="{{ $user->phone_no }}" required readonly>
                                            @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-sm-4 col-form-label">Address <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" id="address" name="address" required>{{ $user->address }}</textarea>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pincode" class="col-sm-4 col-form-label">Pin Code <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="pincode" name="pincode"
                                                value="{{ $user->pincode }}" required readonly>
                                            @error('pincode')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label">E-mail <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $user->email }}" required readonly>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="panno" class="col-sm-4 col-form-label">Pan Card No.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="panno"
                                                value="{{ $user->pan_card_no }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="panno" class="col-sm-4 col-form-label">Package</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="panno"
                                                value="{{ $packageDetails }}" disabled>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="profile_image" class="col-sm-4 col-form-label">Upload Profile
                                            Image (Max: 2MB)</label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control" id="profile_image"
                                                name="profile_image">
                                            @error('profile_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">
                                            Sponsor Fill Direction
                                            <small class="d-block text-muted" style="font-weight:normal;font-size:11px;">
                                                New free members you sponsor will auto-place on this side of your tree
                                            </small>
                                        </label>
                                        <div class="col-sm-8 d-flex align-items-center" style="gap:20px;flex-wrap:wrap;">
                                            <div class="icheck-primary">
                                                <input type="radio" name="fill_preference" id="pref_left" value="left"
                                                    {{ ($user->fill_preference ?? 'left') === 'left' ? 'checked' : '' }}>
                                                <label for="pref_left">
                                                    <i class="fas fa-arrow-left mr-1"></i> Fill Left First
                                                </label>
                                            </div>
                                            <div class="icheck-primary">
                                                <input type="radio" name="fill_preference" id="pref_right" value="right"
                                                    {{ ($user->fill_preference ?? 'left') === 'right' ? 'checked' : '' }}>
                                                <label for="pref_right">
                                                    <i class="fas fa-arrow-right mr-1"></i> Fill Right First
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info float-right">Update User Details</button>
                                </div>

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('footer')
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
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.profile');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
