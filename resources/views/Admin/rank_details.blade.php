@extends('Admin.admin_header')
@section('title', 'vishwastha   | Ranks')
@section('content')
<style>
    img.img-circle.profile-user-img {
        width: 83px !important; 
        height: 71px !important; 
        object-fit: cover;
    }
    .rank-box {
        border: 2px solid transparent; /* Initially set the border to transparent */
        transition: border-color 0.3s ease; /* Smooth transition */
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
                    <h1>Ranks</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('adminhome')}}">Home</a></li>
                        <li class="breadcrumb-item active">Ranks</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @php
    $ranks = [
        'Silver' => ['icon' => '🥈', 'color' => '#c0c0c0'],
        'Gold' => ['icon' => '🏅', 'color' => '#ffd700'],
        'Platinum' => ['icon' => '💿', 'color' => '#e5e4e2'],
        'Sapphire' => ['icon' => '🔷', 'color' => '#0f52ba'],
        'Pearl' => ['icon' => '⚪', 'color' => '#f5f5f5'],
        'Ruby' => ['icon' => '🔴', 'color' => '#e0115f'],
        'Diamond' => ['icon' => '💎', 'color' => '#b9f2ff'],
        'Emerald' => ['icon' => '🟢', 'color' => '#a2d6b4'],
        'Crown' => ['icon' => '🟠', 'color' => '#ff7f50'],
        'Royal Crown' => ['icon' => '👑', 'color' => '#8b0000'],
        'Manager' => ['icon' => '📋', 'color' => '#4682b4'],
        'Ambassador' => ['icon' => '🌍', 'color' => '#ff4500'],
        'Royal Crown Ambassador' => ['icon' => '🌟', 'color' => '#ff6347'],
    ];
    @endphp
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($rankData as $rank)
                    @php
                        $currentRank = $ranks[$rank['rank_name']] ?? null;
                        $icon = $currentRank['icon'] ?? '❓';
                        $color = $currentRank['color'] ?? '#ccc';
                    @endphp
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="info-box rank-box" data-rank="{{ $rank['id'] }}">
                            <span class="info-box-icon" style="background-color: {{ $color }}; color: #fff;">
                                {!! $icon !!}
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ $rank['rank_name'] }}</span>
                                <span class="info-box-number">{{ $rank['user_count'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
            <div class="row" id="user-details-row">
            </div>
        </div>
    </section>
</div>
@endsection
@section('footer')
<script>
    $(document).ready(function () {
        $('.rank-box').on('click', function () {
            var rank = $(this).data('rank');
            var rankName = $(this).find('.info-box-text').text();
            if (rank) {
                window.location.href = '/admin/rank-details/' + rank + '/users';
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
            const binaryLink = $('.nav-link.rank_details');
            if (binaryLink.length) {
                binaryLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection