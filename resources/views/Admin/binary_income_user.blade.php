@extends('Admin.admin_header')
@section('title', 'Vishwastha | My Binary Income')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>My Binary Income</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Binary Income</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Wallet summary --}}
            @if($wallet)
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Available Balance</span>
                            <span class="info-box-number">₹{{ number_format($wallet->balance, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-handshake"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pair Income</span>
                            <span class="info-box-number">₹{{ number_format($pairIncomeTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-user-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sponsor Income</span>
                            <span class="info-box-number">₹{{ number_format($sponsorIncomeTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Withdrawn</span>
                            <span class="info-box-number">₹{{ number_format($wallet->total_withdrawn, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Notice --}}
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Income is calculated and credited each time the admin runs the income processor. Showing all confirmed runs below.
            </div>

            {{-- Pair match income --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Binary Pair Income</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">Total ₹{{ number_format($pairLogs->sum('income'), 2) }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Package</th>
                                <th>Pairs Matched</th>
                                <th>Income (₹)</th>
                                <th>Carry Fwd L</th>
                                <th>Carry Fwd R</th>
                                <th>Flushed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pairLogs as $i => $log)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->calc_date)->format('d M Y') }}</td>
                                <td><small>{{ $log->package->name ?? $log->package_type }}</small></td>
                                <td>{{ $log->capped_pairs }} / {{ $log->matched_pairs }}</td>
                                <td class="text-success font-weight-bold">₹{{ number_format($log->income, 2) }}</td>
                                <td class="{{ $log->carry_out_left > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_left }}</td>
                                <td class="{{ $log->carry_out_right > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_right }}</td>
                                <td class="text-danger">{{ max($log->flushed_left, $log->flushed_right) ?: '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-muted">No pair income records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Referral income --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Referral Income (All-Time)</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">Total ₹{{ number_format($referralTransactions->sum('amount'), 2) }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($referralTransactions as $i => $tx)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $tx->created_at->format('d M Y') }}</td>
                                <td>{{ $tx->description }}</td>
                                <td class="text-success font-weight-bold">₹{{ number_format($tx->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">No referral income yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

@endsection
