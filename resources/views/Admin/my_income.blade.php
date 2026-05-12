@extends('Admin.admin_header')
@section('title', 'My Income')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>My Income</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">My Income</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
            @endif

            {{-- ===== MY WALLET ===== --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body text-center py-4">
                            <div class="mb-2"><i class="fas fa-wallet fa-2x text-primary"></i></div>
                            <p class="text-muted mb-1 font-weight-bold text-uppercase" style="font-size:12px; letter-spacing:1px;">My Wallet</p>
                            <h2 class="font-weight-bold text-primary mb-0">₹{{ number_format($myWallet->balance, 2) }}</h2>
                            <small class="text-muted">Available balance</small>
                            <hr class="my-2">
                            <small class="text-muted">Lifetime Earned: <strong class="text-dark">₹{{ number_format($myWallet->total_earned, 2) }}</strong></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 d-flex align-items-center">
                    <div class="alert alert-info mb-0 w-100">
                        <i class="fas fa-info-circle mr-2"></i>
                        Transfer income from any wallet below into <strong>My Wallet</strong>. All 6 income types require a manual transfer.
                    </div>
                </div>
            </div>

            {{-- ===== BINARY & SPONSOR INCOME ===== --}}
            <h5 class="font-weight-bold mb-3 border-bottom pb-2"><i class="fas fa-sitemap mr-2 text-info"></i>Binary & Sponsor Income</h5>
            <div class="row mb-4">

                {{-- Binary Income --}}
                <div class="col-md-6 mb-3">
                    <div class="card card-info card-outline h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="fas fa-code-branch mr-2"></i>Binary Income</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-2">
                                <div class="col-6 border-right">
                                    <p class="text-muted mb-1" style="font-size:11px;">LIFETIME EARNED</p>
                                    <h4 class="font-weight-bold text-info">₹{{ number_format($binaryPairLifetime, 2) }}</h4>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1" style="font-size:11px;">AVAILABLE</p>
                                    <h4 class="font-weight-bold {{ (float)$binaryWallet->balance > 0 ? 'text-success' : 'text-muted' }}">
                                        ₹{{ number_format($binaryWallet->balance, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            @if((float)$binaryWallet->balance > 0)
                            <form action="{{ route('my_income.transfer') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="binary">
                                <button type="submit" class="btn btn-info btn-block btn-sm"
                                    onclick="return confirm('Move ₹{{ number_format($binaryWallet->balance, 2) }} to My Wallet?')">
                                    <i class="fas fa-arrow-up mr-1"></i> Move ₹{{ number_format($binaryWallet->balance, 2) }} to My Wallet
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-block btn-sm" disabled>
                                <i class="fas fa-minus-circle mr-1"></i> No Balance Available
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sponsor Income --}}
                <div class="col-md-6 mb-3">
                    <div class="card card-primary card-outline h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="fas fa-user-friends mr-2"></i>Sponsor Income</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-2">
                                <div class="col-6 border-right">
                                    <p class="text-muted mb-1" style="font-size:11px;">LIFETIME EARNED</p>
                                    <h4 class="font-weight-bold text-primary">₹{{ number_format($sponsorLifetime, 2) }}</h4>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1" style="font-size:11px;">AVAILABLE</p>
                                    <h4 class="font-weight-bold {{ (float)$binaryWallet->balance > 0 ? 'text-success' : 'text-muted' }}">
                                        ₹{{ number_format($binaryWallet->balance, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            @if((float)$binaryWallet->balance > 0)
                            <form action="{{ route('my_income.transfer') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="binary">
                                <button type="submit" class="btn btn-primary btn-block btn-sm"
                                    onclick="return confirm('Move ₹{{ number_format($binaryWallet->balance, 2) }} to My Wallet?')">
                                    <i class="fas fa-arrow-up mr-1"></i> Move ₹{{ number_format($binaryWallet->balance, 2) }} to My Wallet
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-block btn-sm" disabled>
                                <i class="fas fa-minus-circle mr-1"></i> No Balance Available
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== 4 CATEGORY WALLETS ===== --}}
            <h5 class="font-weight-bold mb-3 border-bottom pb-2"><i class="fas fa-layer-group mr-2 text-success"></i>Category Wallets</h5>
            @php
                $categoryCards = [
                    'privilege' => ['label' => 'Privilege Wallet',  'icon' => 'fas fa-star',      'color' => 'purple', 'btn' => 'btn-secondary'],
                    'board'     => ['label' => 'Board Wallet',      'icon' => 'fas fa-users',     'color' => 'warning','btn' => 'btn-warning'],
                    'executive' => ['label' => 'Executive Wallet',  'icon' => 'fas fa-briefcase', 'color' => 'info',   'btn' => 'btn-info'],
                    'royalty'   => ['label' => 'Royalty Wallet',    'icon' => 'fas fa-crown',     'color' => 'success','btn' => 'btn-success'],
                ];
            @endphp
            <div class="row mb-4">
                @foreach($categoryCards as $type => $card)
                @php
                    $active    = $isMember[$type];
                    $available = $newIncomes[$type]['available'];
                    $lifetime  = $newIncomes[$type]['lifetime'];
                    $history   = $newIncomes[$type]['history'];
                @endphp
                <div class="col-md-6 mb-3">
                    <div class="card card-{{ $card['color'] }} card-outline h-100" style="{{ !$active ? 'opacity:0.6;' : '' }}">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="{{ $card['icon'] }} mr-2"></i>{{ $card['label'] }}
                            </h6>
                            <div class="card-tools">
                                @if($active)
                                    <span class="badge badge-success">Active Member</span>
                                @else
                                    <span class="badge badge-secondary">Not a Member</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-4 border-right">
                                    <p class="text-muted mb-1" style="font-size:11px;">AVAILABLE</p>
                                    <h4 class="font-weight-bold {{ $active && $available > 0 ? 'text-success' : 'text-muted' }}">
                                        ₹{{ number_format($available, 2) }}
                                    </h4>
                                </div>
                                <div class="col-4 border-right">
                                    <p class="text-muted mb-1" style="font-size:11px;">LIFETIME EARNED</p>
                                    <h4 class="font-weight-bold text-dark">₹{{ number_format($lifetime, 2) }}</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-1" style="font-size:11px;">DISTRIBUTIONS</p>
                                    <h4 class="font-weight-bold text-dark">{{ $history->count() }}</h4>
                                </div>
                            </div>

                            @if($history->isNotEmpty())
                            <div style="max-height:160px; overflow-y:auto;">
                                <table class="table table-xs table-bordered table-striped mb-0" style="font-size:12px;">
                                    <thead class="thead-light">
                                        <tr><th>Date</th><th>Amount</th><th>Status</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history as $credit)
                                        <tr>
                                            <td>{{ $credit->created_at->format('d-m-Y') }}</td>
                                            <td class="font-weight-bold">₹{{ number_format($credit->amount, 2) }}</td>
                                            <td>
                                                @if($credit->transferred_at)
                                                    <span class="badge badge-success" style="font-size:10px;">In My Wallet</span>
                                                @else
                                                    <span class="badge badge-warning" style="font-size:10px;">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <p class="text-center text-muted mb-0" style="font-size:13px;">
                                    @if($active)
                                        No distributions received yet.
                                    @else
                                        Become an active {{ ucfirst($type) }} member to receive this income.
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-white">
                            @if($active && $available > 0)
                            <form action="{{ route('my_income.transfer') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="btn {{ $card['btn'] }} btn-block btn-sm"
                                    onclick="return confirm('Move ₹{{ number_format($available, 2) }} from {{ ucfirst($type) }} wallet to My Wallet?')">
                                    <i class="fas fa-arrow-up mr-1"></i> Move ₹{{ number_format($available, 2) }} to My Wallet
                                </button>
                            </form>
                            @elseif($active)
                            <button class="btn btn-secondary btn-block btn-sm" disabled>
                                <i class="fas fa-minus-circle mr-1"></i> No Balance Available
                            </button>
                            @else
                            <button class="btn btn-secondary btn-block btn-sm" disabled>
                                <i class="fas fa-lock mr-1"></i> Not Eligible
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
</div>
@endsection
