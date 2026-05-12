@extends('Admin.admin_header')
@section('title', 'vishwastha | Admin Wallet')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Admin Wallet</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Admin Wallet</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Balance Cards --}}
            @php
                $cards = [
                    'privilege' => ['label' => 'Privilege Reserve', 'icon' => 'fas fa-star',      'color' => 'bg-purple'],
                    'board'     => ['label' => 'Board Reserve',     'icon' => 'fas fa-users',     'color' => 'bg-warning'],
                    'executive' => ['label' => 'Executive Reserve', 'icon' => 'fas fa-briefcase', 'color' => 'bg-info'],
                    'royalty'   => ['label' => 'Royalty Reserve',   'icon' => 'fas fa-crown',     'color' => 'bg-success'],
                ];
            @endphp
            <div class="row mb-4">
                @foreach($cards as $type => $card)
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $card['color'] }}">
                        <div class="inner">
                            <h4>₹{{ number_format($balances[$type] ?? 0, 2) }}</h4>
                            <p>{{ $card['label'] }}</p>
                        </div>
                        <div class="icon"><i class="{{ $card['icon'] }}"></i></div>
                        <a href="#{{ $type }}-ledger" class="small-box-footer">
                            View Credits <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-dark">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Admin Reserve</span>
                            <span class="info-box-number">₹{{ number_format($totalBalance, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Ledger --}}
            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class="card-title text-white"><i class="fas fa-list mr-2"></i>Admin Wallet Ledger</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered table-striped mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Wallet Type</th>
                                <th>Distribution Pool (₹)</th>
                                <th>Users Paid</th>
                                <th>Per User (₹)</th>
                                <th>Amount Credited to Admin (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledger as $i => $entry)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $entry->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    @php
                                        $badgeMap = ['privilege'=>'purple','board'=>'warning','executive'=>'info','royalty'=>'success'];
                                        $badge = $badgeMap[$entry->wallet_type] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $badge }}">{{ ucfirst($entry->wallet_type) }}</span>
                                </td>
                                <td>{{ number_format($entry->distribution->pool_amount ?? 0, 2) }}</td>
                                <td>{{ $entry->distribution->user_count ?? '—' }}</td>
                                <td>{{ number_format($entry->distribution->per_user_amount ?? 0, 2) }}</td>
                                <td class="text-warning font-weight-bold">₹{{ number_format($entry->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No credits yet. Admin wallet receives remainder amounts after each distribution.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($ledger->isNotEmpty())
                        <tfoot>
                            <tr class="font-weight-bold bg-secondary text-white">
                                <td colspan="6" class="text-right">Total Admin Reserve</td>
                                <td>₹{{ number_format($totalBalance, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
