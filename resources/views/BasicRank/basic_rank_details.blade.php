@extends('Admin.admin_header')
@section('title', 'vishwastha | Ranks')
@section('content')
    <style>
        img.img-circle.profile-user-img {
            width: 83px !important;
            height: 71px !important;
            object-fit: cover;
        }

        .rank-box {
            border: 2px solid transparent;
            /* Initially set the border to transparent */
            transition: border-color 0.3s ease;
            /* Smooth transition */
        }

        .profile-user-img {
            padding: 0 !important;
        }

        .info-box.rank-box {
            cursor: pointer !important;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Basic Ranks</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Basic Ranks</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        @php
            $ranks = [
                '1 Star' => ['icon' => '⭐', 'color' => '#ffd700'], // Gold - 1 Star
                '2 Star' => ['icon' => '⭐⭐', 'color' => '#e5e4e2'], // Silver - 2 Star
                '3 Star' => ['icon' => '⭐⭐⭐', 'color' => '#6194e2'], // Blue - 3 Star
                '4 Star' => ['icon' => '⭐⭐⭐⭐', 'color' => '#e0115f'], // Ruby - 4 Star
                '5 Star' => ['icon' => '⭐⭐⭐⭐⭐', 'color' => '#b9f2ff'], // Diamond - 5 Star
            ];
        @endphp
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @foreach ($rankData as $rank)
                        @php
                            $currentRank = $ranks[$rank['name']] ?? null;
                            $icon = $currentRank['icon'] ?? '❓';
                            $color = $currentRank['color'] ?? '#ccc';
                        @endphp
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box rank-box text-center" data-rank="{{ $rank['id'] }}"
                                style="flex-direction: column; align-items: center; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background: #fff;">

                                <!-- Icon Section -->
                                <div class="rank-icon mb-2"
                                    style="background-color: {{ $color }}; 
                    display: inline-flex; 
                    justify-content: center; 
                    align-items: center; 
                    height: 70px; 
                    border-radius: 10px; 
                    font-size: 2em;">
                                    {!! $icon !!}
                                </div>

                                <!-- Text Section -->
                                <div class="rank-details">
                                    <div class="rank-count text-muted" style="font-weight: bold;font-size: 0.95em;">
                                        {{ $rank['user_count'] }} Users
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row" id="user-details-row">
                </div>
            </div>
        </section>
    </div>
@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('.rank-box').on('click', function() {
                var rank = $(this).data('rank');
                var rankName = $(this).find('.info-box-text').text();
                if (rank) {
                    window.location.href = '/admin/basic-rank-details/' + rank + '/users';
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Invalid rank selected.',
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.team');
            const treeviewLink = $('.nav.nav-treeview.team');
            const mainLiLink = $('.nav-item.has-treeview.team');
            const binaryLink = $('.nav-link.basicrank_details');
            if (binaryLink.length) {
                binaryLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
