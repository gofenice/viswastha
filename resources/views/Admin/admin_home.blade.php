@extends('Admin.admin_header')
@section('content')
    <style>
        .profiles {
            text-align: center;
            border: 2px solid #cccccc75;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-mg {
            margin: 0 auto;
            height: auto;
            display: inline-block;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-details {
            margin-top: 10px;
        }

        .details-container {
            font-family: 'Arial', sans-serif;
            border-radius: 5px;
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
            background-color: #f9f9f9;
        }

        .details-container h3 {
            text-align: left;
            color: #2c2c54;
            margin-bottom: 20px;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .details-row label {
            flex: 1;
            font-weight: bold;
            color: #2c2c54;
            font-size: 14px;
        }

        .detail-value {
            flex: 2;
            background-color: #e9ecef;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: left;
            color: #2c2c54;
            font-size: 14px;
        }

        .button-container {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .button {
            display: inline-block;
            background-color: #176c76;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #145960;
        }

        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            /* box-shadow: 0 4px 6px #002366; */
            border: 1px solid #002366;
            margin: 10px;
            text-align: center;
        }

        .card h2 {
            font-size: 18px;
            color: #343a40;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 14px;
            color: #6c757d;
            margin: 10px 0;
        }

        .profile-user-img {
            width: 128px;
            height: 128px;
            object-fit: cover;
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
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
                                    <div class="rank-icon" style="font-size: 2.5em; margin-right: 10px;">
                                        {!! $rankData['icon'] !!}</div>
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
                        </div>

                        @if (Auth::check() && Auth::user()->role !== 'superadmin')
                            <div class="card">
                                <h2>Current Rank Income <br> <b>Premium | Basic</b></h2>
                                <H3><b>₹ {{ $individualShare }} | ₹ {{ $basicIndividualShare }}</b></h3>
                            </div>
                        @endif

                        {{-- <div class="card">
                            <h2>Announcement</h2> --}}
                        {{-- <p>Our New Office</p> --}}
                        {{-- <p>
                                OFFICE NO - 1246, 12TH FLOOR, GALAXY DIAMOND PLAZA, GREATER NOIDA WEST, GAUTAM BUDDHA NAGAR,  UP - 201009.
                            </p> --}}
                        {{-- <img src="{{ asset('assets/dist/img/vstrcm.jpg') }}" style="border-radius: 10px;"
                                width="225px">
                        </div> --}}
                        <div class="card shadow-sm border-1 mb-4"
                            style="border-radius: 12px; overflow: hidden; max-width: 300px; padding:0px !important;">
                            <div class="card-body text-center p-3 bg-light">
                                <h4 class="card-title mb-3" style="font-weight: 600; color: #2c3e50;">📢 Announcement</h4>
                                @if ($announcement && $announcement->image)
                                    <a href="{{ asset('storage/' . $announcement->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $announcement->image) }}" alt="Announcement Image"
                                            class="img-fluid" style="border-radius: 10px; transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                @endif

                                @if (Auth::check() && Auth::user()->role == 'superadmin')
                                    {{-- Show only to admin --}}
                                    <form action="{{ route('announcement.update') }}" method="POST"
                                        enctype="multipart/form-data" style="margin-top: 10px;">
                                        @csrf
                                        <input type="file" name="image" class="form-control mb-2" required>
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                @endif
                            </div>
                        </div>


                        @if (Auth::check() && Auth::user()->role !== 'superadmin')
                            <div class="card">
                                <h2>Invitation Link</h2>
                                <button id="copyLinkButton" class="btn btn-primary"
                                    data-link="{{ url('/register_wpan?sponsor_id=' . $user->connection . '&sponsor_name=' . urlencode($user->name)) }}">
                                    Copy Registration Link
                                </button>
                            </div>
                        @endif
                    </div>
                    @if (Auth::check() && Auth::user()->role !== 'superadmin')
                        <div class="col-md-9" style="margin: 0 auto;">
                            <div class="card card-primary user-details">
                                <div class="card-header">
                                    <h3 class="card-title">User Details</h3>
                                </div>
                                <div class="card-body">
                                    <div class="details-container mt-2">
                                        <div class="details-row">
                                            <label>
                                                @if ($user->mother_id == 1)
                                                    MOTHER ID
                                                @elseif($user->mother_id == 2)
                                                    PRIVILEGE ID
                                                @else
                                                    USER ID
                                                @endif
                                            </label>
                                            <div
                                                class="detail-value @if ($user->mother_id == 1) bg-success @elseif($user->mother_id == 2)bg-info @elseif($user->mother_id == 3) bg-primary @endif">
                                                {{ $user->connection }}</div>
                                        </div>
                                        <div class="details-row">
                                            <label>MOBILE NO</label>
                                            <div class="detail-value">{{ $user->phone_no }}</div>
                                        </div>
                                        <div class="details-row">
                                            <label>JOINING DATE</label>
                                            <div class="detail-value">{{ $user->created_at->format('d-m-Y') }}</div>
                                        </div>
                                        <div class="details-row">
                                            <label>SPONSOR NAME</label>
                                            <div class="detail-value">{{ $sponsor->name ?? 'No sponsor' }}</div>
                                        </div>
                                        <div class="details-row">
                                            <label>SPONSOR ID</label>
                                            <div class="detail-value">{{ $sponsor->connection ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
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
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.dashboard');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
    <script>
        document.getElementById('copyLinkButton').addEventListener('click', function() {
            const link = this.getAttribute('data-link');

            // Copy to clipboard
            navigator.clipboard.writeText(link).then(function() {
                alert('Registration link copied to clipboard!');
            }, function(err) {
                alert('Failed to copy link: ' + err);
            });
        });
    </script>
@endsection
