@extends('Admin.admin_header')
@section('title', 'vishwastha | Purchase Wallets')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Purchase Wallets</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Wallets</li>
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

            {{-- Summary Cards --}}
            @php
                $cards = [
                    'privilege' => ['label' => 'Privilege Member Wallet', 'icon' => 'fas fa-star',      'color' => 'bg-purple'],
                    'board'     => ['label' => 'Board Member Wallet',     'icon' => 'fas fa-users',     'color' => 'bg-warning'],
                    'executive' => ['label' => 'Executive Wallet',        'icon' => 'fas fa-briefcase', 'color' => 'bg-info'],
                    'royalty'   => ['label' => 'Royalty Wallet',          'icon' => 'fas fa-crown',     'color' => 'bg-success'],
                ];
            @endphp
            <div class="row mb-4">
                @foreach($cards as $type => $card)
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $card['color'] }}">
                        <div class="inner">
                            <h4>₹{{ number_format($totals[$type] ?? 0, 2) }}</h4>
                            <p>{{ $card['label'] }}<br>
                                <small>Admin Reserve: ₹{{ number_format($adminBalances[$type] ?? 0, 2) }}</small>
                            </p>
                        </div>
                        <div class="icon"><i class="{{ $card['icon'] }}"></i></div>
                        <a href="#" onclick="showTab('{{ $type }}'); return false;" class="small-box-footer">
                            View Details <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tabs --}}
            <div class="card">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" id="walletTabs">
                        @foreach(['privilege','board','executive','royalty'] as $type)
                        <li class="nav-item">
                            <a class="nav-link {{ $type === 'privilege' ? 'active' : '' }}"
                               data-toggle="tab" href="#tab-{{ $type }}" id="link-{{ $type }}">
                                <i class="{{ $cards[$type]['icon'] }} mr-1"></i>
                                {{ ucfirst($type) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        @foreach(['privilege','board','executive','royalty'] as $type)
                        <div class="tab-pane fade {{ $type === 'privilege' ? 'show active' : '' }}" id="tab-{{ $type }}">

                            {{-- Distribute Action Bar --}}
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded border">
                                <div>
                                    <span class="font-weight-bold">Undistributed Pool:</span>
                                    <span class="text-primary font-weight-bold ml-1">₹{{ number_format($totals[$type] ?? 0, 2) }}</span>
                                    <span class="mx-2 text-muted">+</span>
                                    <span class="font-weight-bold">Admin Reserve:</span>
                                    <span class="text-warning font-weight-bold ml-1">₹{{ number_format($adminBalances[$type] ?? 0, 2) }}</span>
                                    <span class="mx-2 text-muted">=</span>
                                    <span class="font-weight-bold">Total Pool:</span>
                                    <span class="text-success font-weight-bold ml-1">₹{{ number_format(($totals[$type] ?? 0) + ($adminBalances[$type] ?? 0), 2) }}</span>
                                    <span class="text-muted ml-3">Active Members: <strong>{{ ($members[$type] ?? collect())->where('status', 1)->count() }}</strong></span>
                                </div>
                                <form action="{{ route('purchase_wallets.distribute') }}" method="POST" class="d-inline distribute-form">
                                    @csrf
                                    <input type="hidden" name="wallet_type" value="{{ $type }}">
                                    <button type="submit" class="btn btn-success btn-sm distribute-btn"
                                        onclick="return confirm('Distribute {{ ucfirst($type) }} wallet to all active members now?')">
                                        <i class="fas fa-share-alt mr-1"></i> Distribute Now
                                    </button>
                                </form>
                            </div>

                            <div class="row">

                                {{-- LEFT: Members list --}}
                                <div class="col-md-4">
                                    <h6 class="font-weight-bold border-bottom pb-1 mb-2">
                                        <i class="{{ $cards[$type]['icon'] }} mr-1"></i>
                                        {{ $cards[$type]['label'] }} Members
                                        <span class="badge badge-secondary ml-1">{{ ($members[$type] ?? collect())->count() }}</span>
                                    </h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($members[$type] ?? [] as $i => $member)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $member->user->connection ?? '—' }}</td>
                                                <td>{{ $member->user->name ?? '—' }}</td>
                                                <td>
                                                    @if($member->status == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No members.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- RIGHT: Purchase entries + distribution history --}}
                                <div class="col-md-8">

                                    {{-- Undistributed entries --}}
                                    <h6 class="font-weight-bold border-bottom pb-1 mb-2">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Pending Purchase Entries
                                        <span class="badge badge-secondary ml-1">{{ ($entries[$type] ?? collect())->count() }}</span>
                                        <span class="float-right text-primary font-weight-bold">
                                            Pool: ₹{{ number_format($totals[$type] ?? 0, 2) }}
                                        </span>
                                    </h6>
                                    <div style="max-height:260px; overflow-y:auto;" class="mb-4">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="thead-dark" style="position:sticky;top:0;">
                                                <tr>
                                                    <th>#</th>
                                                    <th>User ID</th>
                                                    <th>Name</th>
                                                    <th>Package</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($entries[$type] ?? [] as $i => $entry)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $entry->user->connection ?? '—' }}</td>
                                                    <td>{{ $entry->user->name ?? '—' }}</td>
                                                    <td>{{ $entry->package->name ?? '—' }}</td>
                                                    <td>₹{{ number_format($entry->amount, 2) }}</td>
                                                    <td>{{ $entry->created_at->format('d-m-Y') }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No pending entries.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            @if(($entries[$type] ?? collect())->isNotEmpty())
                                            <tfoot>
                                                <tr class="font-weight-bold bg-light">
                                                    <td colspan="4" class="text-right">Total</td>
                                                    <td>₹{{ number_format($totals[$type] ?? 0, 2) }}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                            @endif
                                        </table>
                                    </div>

                                    {{-- Distribution history --}}
                                    <h6 class="font-weight-bold border-bottom pb-1 mb-2">
                                        <i class="fas fa-history mr-1"></i>
                                        Recent Distributions
                                        <span class="badge badge-info ml-1">Last 5</span>
                                    </h6>
                                    <div style="max-height:200px; overflow-y:auto;">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="thead-light" style="position:sticky;top:0;">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Pool (₹)</th>
                                                    <th>Users</th>
                                                    <th>Per User (₹)</th>
                                                    <th>Distributed (₹)</th>
                                                    <th>Reserve (₹)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($distributions[$type] ?? [] as $dist)
                                                <tr>
                                                    <td>{{ $dist->created_at->format('d-m-Y H:i') }}</td>
                                                    <td>{{ number_format($dist->pool_amount, 2) }}</td>
                                                    <td>{{ $dist->user_count }}</td>
                                                    <td class="text-success font-weight-bold">{{ number_format($dist->per_user_amount, 2) }}</td>
                                                    <td>{{ number_format($dist->total_distributed, 2) }}</td>
                                                    <td class="text-warning">{{ number_format($dist->remainder, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No distributions yet.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
@section('footer')
<script>
function showTab(type) {
    setTimeout(function() { $('#link-' + type).tab('show'); }, 100);
}
</script>
@endsection
