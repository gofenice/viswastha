@extends('Admin.admin_header')
@section('content')
    <style>
        a.small-box-footer.align-right {
            float: right;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Company Rank Income</h1>
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
            <div class="row">
                @php
                    $ranks = [
                        '1' => ['icon' => '', 'color' => '#cd7f32'], // No rank
                        '2' => ['icon' => '🏅', 'color' => '#ffd700'], // Gold
                        '3' => ['icon' => '💿', 'color' => '#e5e4e2'], // Platinum
                        '4' => ['icon' => '🔵', 'color' => '#6194e2'], // Pearl
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
                @endphp
                @foreach ($rankIncomes as $income)
                    @php
                        $rank = $ranks[$income->rank_id] ?? ['icon' => '', 'color' => '#d3d3d3']; // Default color
                    @endphp
                    <div class="col-lg-4 col-6">
                        <div class="small-box" style="background-color: {{ $rank['color'] }}; color: black;">
                            <div class="inner">
                                <h4>{{ $income->rank->rank_name }}</h4>
                                <p class="mb-0">
                                    <a href="{{ route('rank.total', $income->rank_id) }}" class="small-box-footer "
                                        style="color: black;">

                                        Total: {{ number_format($income->total_amount, 2) }}

                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </p>
                                <p class="mb-0">
                                    <a href="{{ route('rank.redeemed', $income->rank_id) }}" class="small-box-footer"
                                        style="color: black;">
                                        Redeemed: {{ number_format($income->redeemed_amount, 2) }}
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </p>
                                <p>
                                    <a href="{{ route('rank.pending', $income->rank_id) }}" class="small-box-footer "
                                        style="color: black;">
                                        Active: {{ number_format($income->pending_amount, 2) }}
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </p>

                            </div>
                            <div class="icon">
                                {{-- <i>{{ $rank['icon'] }}</i> --}}
                            </div>
                            <div class="flex justify-around mt-2 pb-2">

                                <!-- Redeemed to Company Button -->
                                <a href="#" class="px-1 py-2 " style="color: black;" data-toggle="modal"
                                    data-target="#companyapproveModal" onclick="redeemToCompany('{{ $income->rank_id }}')">
                                    <i class="fas fa-arrow-circle-left mr-2"></i> Redeem to Company
                                </a> |

                                <!-- Redeemed to User Button -->
                                <a href="#" class="px-1 py-2 " data-toggle="modal" data-target="#approveModal"
                                    onclick="redeemToUser('{{ $income->rank_id }}')" style="color: black;">
                                    Redeem to User <i class="fas fa-arrow-circle-right ml-2"></i>
                                </a>


                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('redeemToUser') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Confirm to User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to redeem the amount to User ?</p>
                        <input type="hidden" name="rank_id" id="rank_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="companyapproveModal" tabindex="-1" aria-labelledby="companyapproveModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('redeemToCompany') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyapproveModal">Confirm to Company</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to redeem the amount to Company ?</p>
                        <input type="hidden" name="rankC_id" id="rankC_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
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
        function redeemToUser(rank_id) {
            var rankId = $('#approveModal').find('#rank_id');
            rankId.val(rank_id);
        }

        function redeemToCompany(rank_id) {
            var rankCId = $('#companyapproveModal').find('#rankC_id');
            rankCId.val(rank_id);
        }
    </script>
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.preRank');
            const treeviewLink = $('.nav.nav-treeview.preRank');
            const mainLiLink = $('.nav-item.has-treeview.preRank');
            const directLink = $('.nav-link.company_rank');
            if (directLink.length) {
                directLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
