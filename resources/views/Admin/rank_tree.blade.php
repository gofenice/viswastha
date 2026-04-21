@extends('Admin.admin_header')
@section('title', 'vishwastha  | Rank Tree')
@section('content')
    <style>
        .info-box-content {
            color: black !important;
        }

        .info-box.rank-box {
            cursor: pointer !important;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Rank Tree</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Rank Tree</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        @php
            $ranks = [
                '1' => ['icon' => '', 'color' => '#cd7f32', 'name' => 'No Rank'], // No rank
                '2' => ['icon' => '🏅', 'color' => '#ffd700', 'name' => 'Gold'], // Gold
                '3' => ['icon' => '💿', 'color' => '#e5e4e2', 'name' => 'Platinum'], // Platinum
                '4' => ['icon' => '🔵', 'color' => '#0f52ba', 'name' => 'Pearl'], // Pearl
                '5' => ['icon' => '🔴', 'color' => '#e0115f', 'name' => 'Ruby'], // Ruby
                '6' => ['icon' => '⚪', 'color' => '#f5f5f5', 'name' => 'Diamond'], // Diamond
                '7' => ['icon' => '🔷', 'color' => '#1e90ff', 'name' => 'Double Diamond'], // Double Diamond
                '8' => ['icon' => '🟢', 'color' => '#00ff7f', 'name' => 'Emerald'], // Emerald
                '9' => ['icon' => '👑', 'color' => '#daa520', 'name' => 'Crown'], // Crown
                '10' => ['icon' => '💎', 'color' => '#b9f2ff', 'name' => 'Royal Crown'], // Royal Crown
                '11' => ['icon' => '📋', 'color' => '#4682b4', 'name' => 'Manager'], // Manager
                '12' => ['icon' => '🌍', 'color' => '#ff4500', 'name' => 'Ambassador'], // Ambassador
                '13' => ['icon' => '🌟', 'color' => '#ff6347', 'name' => 'Royal Crown Ambassador'], // Royal Crown Ambassador
            ];
            $currentRank = $ranks[$currentUser->rank_id] ?? null;
        @endphp


        <section class="content">
            <div class="container-fluid">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box rank-box">
                        <span class="info-box-icon" style="background-color: {{ $currentRank['color'] }}; color: #fff;">
                            {!! $currentRank['icon'] !!}
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $currentUser->name }} <br>{{ $currentUser->connection }}</span>
                            <span class="info-box-number">{{ $currentRank['name'] ?? 'No Rank' }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach (collect($totalRanks)->reverse() as $rankName => $userCount)
                        @php
                            $rankId = array_search($rankName, array_keys($totalRanks)) + 2; // Convert rank name to rank_id
                            $currentRank = $ranks[$rankId] ?? null;
                            $icon = $currentRank['icon'] ?? '❓';
                            $color = $currentRank['color'] ?? '#ccc';
                        @endphp

                        @if ($userCount > 0)
                            {{-- Show only if count > 0 --}}
                            <div class="col-md-3 col-sm-6 col-12">
                                <a href="{{ route('user_details', ['rank' => $rankId]) }}"
                                    class="showranktreelink">
                                    <div class="info-box rank-box">
                                        <span class="info-box-icon"
                                            style="background-color: {{ $color }}; color: #fff;">
                                            {!! $icon !!}
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $rankName }}</span>
                                            <span class="info-box-number">{{ $userCount }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
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
            const teamLink = $('.nav-link.team');
            const treeviewLink = $('.nav.nav-treeview.team');
            const mainLiLink = $('.nav-item.has-treeview.team');
            const binaryLink = $('.nav-link.rank_tree');
            if (binaryLink.length) {
                binaryLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
