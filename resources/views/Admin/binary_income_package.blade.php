@extends('Admin.admin_header')
@section('title', 'Vishwastha | ' . $packageLabel . ' Binary Income')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>{{ $packageLabel }} Binary Income</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $packageLabel }} Binary Income</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Summary cards --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="small-box {{ $packageLabel === 'Basic' ? 'bg-info' : 'bg-success' }}">
                        <div class="inner">
                            <h4>₹{{ number_format($pairIncomeTotal, 2) }}</h4>
                            <p>Pair Income</p>
                        </div>
                        <div class="icon"><i class="fas fa-handshake"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h4>₹{{ number_format($sponsorIncomeTotal, 2) }}</h4>
                            <p>Sponsor Income</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-plus"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h4>{{ $pairLogs->sum('capped_pairs') }}</h4>
                            <p>Total Pairs Matched</p>
                        </div>
                        <div class="icon"><i class="fas fa-exchange-alt"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h4>{{ $pairLogs->sum('flushed_left') + $pairLogs->sum('flushed_right') }}</h4>
                            <p>Total Flushed</p>
                        </div>
                        <div class="icon"><i class="fas fa-ban"></i></div>
                    </div>
                </div>
            </div>

            {{-- Wallet & Carry Forward Status --}}
            @if($walletSummary)
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-wallet mr-1"></i> My Wallet & Carry Forward</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Wallet Balance</th>
                                <th class="text-center">Carry Forward Left</th>
                                <th class="text-center">Carry Forward Right</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center font-weight-bold text-success">₹{{ number_format($walletSummary->balance ?? 0, 2) }}</td>
                                <td class="text-center {{ ($walletSummary->carry_forward_left ?? 0) > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $walletSummary->carry_forward_left ?? 0 }}
                                </td>
                                <td class="text-center {{ ($walletSummary->carry_forward_right ?? 0) > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $walletSummary->carry_forward_right ?? 0 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Package Carry-Forward Status --}}
            @if($packageStatus->count())
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-forward mr-1"></i> Package Carry-Forward Status (after last run)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Package</th>
                                <th class="text-center">Carry Fwd Left</th>
                                <th class="text-center">Carry Fwd Right</th>
                                <th class="text-center">Last Run Income</th>
                                <th class="text-center">Last Run At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packageStatus as $ps)
                            <tr>
                                <td><small>{{ $ps->package_name }}</small></td>
                                <td class="text-center {{ $ps->carry_out_left > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $ps->carry_out_left > 0 ? $ps->carry_out_left . ' units' : '—' }}
                                </td>
                                <td class="text-center {{ $ps->carry_out_right > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $ps->carry_out_right > 0 ? $ps->carry_out_right . ' units' : '—' }}
                                </td>
                                <td class="text-center text-success font-weight-bold">₹{{ number_format($ps->last_income, 2) }}</td>
                                <td class="text-center text-muted" style="font-size:11px;">
                                    {{ \Carbon\Carbon::parse($ps->last_run)->format('d M Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Income is calculated and credited each time the admin runs the income processor. Showing all confirmed runs below.
            </div>

            {{-- Full Pair Log Table --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $packageLabel }} Pair Log</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">Total ₹{{ number_format($pairIncomeTotal, 2) }}</span>
                    </div>
                </div>
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-bordered table-sm mb-0" style="font-size:13px;">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" class="align-middle">#</th>
                                <th rowspan="2" class="align-middle">Date</th>
                                <th rowspan="2" class="align-middle">Package</th>
                                <th colspan="2" class="text-center bg-info text-white">New</th>
                                <th colspan="2" class="text-center bg-secondary text-white">Carry In</th>
                                <th colspan="2" class="text-center bg-primary text-white">Total</th>
                                <th rowspan="2" class="align-middle text-center">Matched</th>
                                <th rowspan="2" class="align-middle text-center">Capped</th>
                                <th rowspan="2" class="align-middle text-center text-success">Income ₹</th>
                                <th colspan="2" class="text-center bg-warning">Carry Out</th>
                                <th colspan="2" class="text-center bg-danger text-white">Flushed</th>
                                <th rowspan="2" class="align-middle">Detail</th>
                            </tr>
                            <tr>
                                <th class="text-center">L</th><th class="text-center">R</th>
                                <th class="text-center">L</th><th class="text-center">R</th>
                                <th class="text-center">L</th><th class="text-center">R</th>
                                <th class="text-center">L</th><th class="text-center">R</th>
                                <th class="text-center">L</th><th class="text-center">R</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pairLogs as $i => $log)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->calc_date)->format('d M Y') }}</td>
                                <td><small>{{ $log->package->name ?? $log->package_type }}</small></td>
                                <td class="text-center text-info font-weight-bold">{{ $log->new_left }}</td>
                                <td class="text-center text-info font-weight-bold">{{ $log->new_right }}</td>
                                <td class="text-center text-secondary">{{ $log->carry_in_left }}</td>
                                <td class="text-center text-secondary">{{ $log->carry_in_right }}</td>
                                <td class="text-center font-weight-bold">{{ $log->total_left }}</td>
                                <td class="text-center font-weight-bold">{{ $log->total_right }}</td>
                                <td class="text-center">{{ $log->matched_pairs }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ $log->capped_pairs }}</td>
                                <td class="text-center font-weight-bold text-success">₹{{ number_format($log->income, 2) }}</td>
                                <td class="text-center {{ $log->carry_out_left > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_left }}</td>
                                <td class="text-center {{ $log->carry_out_right > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_right }}</td>
                                <td class="text-center {{ $log->flushed_left > 0 ? 'text-danger' : '' }}">{{ $log->flushed_left }}</td>
                                <td class="text-center {{ $log->flushed_right > 0 ? 'text-danger' : '' }}">{{ $log->flushed_right }}</td>
                                <td class="text-nowrap">
                                    <button class="btn btn-xs btn-info btn-popup"
                                            data-log="{{ $log->id }}"
                                            data-user="{{ $log->user_id }}"
                                            data-date="{{ \Carbon\Carbon::parse($log->calc_date)->toDateString() }}"
                                            data-package-id="{{ $log->package_id }}"
                                            data-package="{{ $log->package->name ?? $log->package_type }}">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if($log->capped_pairs > 0)
                                    <button class="btn btn-xs btn-success ml-1 btn-pairs" data-log="{{ $log->id }}">
                                        <i class="fas fa-users"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="18" class="text-center text-muted py-3">No income records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sponsor Income Table --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $packageLabel }} Sponsor Income</h3>
                    <div class="card-tools">
                        <span class="badge badge-light">Total ₹{{ number_format($sponsorIncomeTotal, 2) }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>From (Activated By)</th>
                                <th>Package</th>
                                <th>Type</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($referralTransactions as $i => $tx)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $tx->created_at->format('d M Y') }}</td>
                                <td>
                                    {{ $tx->user->name ?? '-' }}<br>
                                    <small class="text-muted">{{ $tx->user->connection ?? '' }}</small>
                                </td>
                                <td>{{ $tx->package->name ?? '-' }}</td>
                                <td>
                                    @if($tx->package_category === 'prime_package')
                                        <span class="badge badge-warning">Prime Sponsor</span>
                                    @elseif($tx->package_category === 'premium_package')
                                        <span class="badge badge-success">Premium Sponsor</span>
                                    @else
                                        <span class="badge badge-info">Basic Sponsor</span>
                                    @endif
                                </td>
                                <td class="text-success font-weight-bold">₹{{ number_format($tx->income, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No sponsor income yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Detail popup modal --}}
<div class="modal fade" id="incomeDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="incomeDetailTitle">Income Detail</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="incomeDetailBody">
                <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading…</div>
            </div>
        </div>
    </div>
</div>

{{-- Pairs modal --}}
<div class="modal fade" id="pairsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-users mr-1"></i> Paired Users Detail</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="pairsModalBody"></div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// ── Detail popup ──────────────────────────────────────────────────────────────
document.querySelectorAll('.btn-popup').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const userId    = this.dataset.user;
        const date      = this.dataset.date;
        const pkg       = this.dataset.package;
        const packageId = this.dataset.packageId;
        const logId     = this.dataset.log;
        document.getElementById('incomeDetailTitle').textContent = 'Detail — ' + pkg + ' — ' + date;
        document.getElementById('incomeDetailBody').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading…</div>';
        $('#incomeDetailModal').modal('show');

        fetch('{{ route('admin.binary_income.popup') }}?log_id=' + logId + '&user_id=' + userId + '&date=' + date + '&package_id=' + packageId)
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">' + data.error + '</p>';
                    return;
                }
                const log = data.log;
                const hasPrime      = data.has_prime || false;
                const leftPremium   = data.left_premium  ?? log.new_left;
                const rightPremium  = data.right_premium ?? log.new_right;
                const leftPrime     = data.left_prime    ?? 0;
                const rightPrime    = data.right_prime   ?? 0;
                const primeCarryInL = data.prime_carry_in_left  ?? 0;
                const primeCarryInR = data.prime_carry_in_right ?? 0;
                const oddPrimeL = hasPrime ? (leftPrime  + primeCarryInL) % 2 : 0;
                const oddPrimeR = hasPrime ? (rightPrime + primeCarryInR) % 2 : 0;
                const flushedPrimeL = Math.max(0, oddPrimeL - (log.prime_carry_out_left  ?? 0));
                const flushedPrimeR = Math.max(0, oddPrimeR - (log.prime_carry_out_right ?? 0));
                const isBasic = log.package_type === 'basic_package';
                const pBadge  = isBasic
                    ? `<span class="badge" style="background:#cce5ff;color:#004085;border:1px solid #007bff;font-size:10px;">Basic</span>`
                    : `<span class="badge" style="background:#d4edda;color:#155724;border:1px solid #28a745;font-size:10px;">Premium</span>`;
                const prBadge = `<span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:10px;">Prime</span>`;

                function sectionCard(title, color, rows) {
                    return `<div class="card card-outline card-${color} mb-2">
                        <div class="card-header p-2 font-weight-bold" style="font-size:13px;">${title}</div>
                        <div class="card-body p-2" style="font-size:13px;">${rows}</div>
                    </div>`;
                }
                function splitRow(pVal, prVal, pClass='', prClass='') {
                    return `<div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span>${pBadge} <span class="${pClass} font-weight-bold">${pVal}</span></span>
                        ${hasPrime ? `<span>${prBadge} <span class="${prClass} font-weight-bold">${prVal}</span></span>` : ''}
                    </div>`;
                }

                document.getElementById('incomeDetailBody').innerHTML = `
                <h6 class="text-primary border-bottom pb-1 mb-3" style="font-size:14px;">Pair Calculation — ${date}</h6>
                <div class="row mb-1">
                    <div class="col-6 pr-1">${sectionCard('New Left',  'primary', splitRow(leftPremium,  leftPrime))}</div>
                    <div class="col-6 pl-1">${sectionCard('New Right', 'primary', splitRow(rightPremium, rightPrime))}</div>
                </div>
                ${(log.carry_in_left > 0 || log.carry_in_right > 0) ? `
                <div class="row mb-1">
                    <div class="col-6 pr-1">${sectionCard('Carry In Left',  'info', splitRow(log.carry_in_left,  0, log.carry_in_left  > 0 ? 'text-info' : '', ''))}</div>
                    <div class="col-6 pl-1">${sectionCard('Carry In Right', 'info', splitRow(log.carry_in_right, 0, log.carry_in_right > 0 ? 'text-info' : '', ''))}</div>
                </div>` : ''}
                <div class="card card-outline card-success mb-2">
                    <div class="card-header p-2 font-weight-bold" style="font-size:13px;">Matched Pairs</div>
                    <div class="card-body p-2">
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <div style="font-size:11px;color:#888;">Matched</div>
                                <div class="font-weight-bold" style="font-size:18px;">${log.matched_pairs}</div>
                            </div>
                            <div class="col-6">
                                <div style="font-size:11px;color:#888;">Capped</div>
                                <div class="font-weight-bold text-primary" style="font-size:18px;">${log.capped_pairs}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-success py-2 mb-2 text-center">
                    <b style="font-size:15px;">Income Earned — ₹${parseFloat(log.income).toLocaleString('en-IN', {minimumFractionDigits:2})}</b>
                </div>
                <div class="row mb-1">
                    <div class="col-6 pr-1">${sectionCard('Carry Out Left',  'warning', splitRow(log.carry_out_left,  log.prime_carry_out_left  ?? 0, log.carry_out_left  > 0 ? 'text-warning' : '', (log.prime_carry_out_left  ?? 0) > 0 ? 'text-warning' : ''))}</div>
                    <div class="col-6 pl-1">${sectionCard('Carry Out Right', 'warning', splitRow(log.carry_out_right, log.prime_carry_out_right ?? 0, log.carry_out_right > 0 ? 'text-warning' : '', (log.prime_carry_out_right ?? 0) > 0 ? 'text-warning' : ''))}</div>
                </div>
                <div class="card card-outline card-danger mb-2">
                    <div class="card-header p-2 font-weight-bold" style="font-size:13px;">Flushed</div>
                    <div class="card-body p-2" style="font-size:13px;">
                        <div class="row">
                            <div class="col-6 border-right">
                                <div style="font-size:11px;color:#888;margin-bottom:4px;">Left</div>
                                ${splitRow(log.flushed_left, flushedPrimeL, log.flushed_left > 0 ? 'text-danger' : '', flushedPrimeL > 0 ? 'text-danger' : '')}
                            </div>
                            <div class="col-6">
                                <div style="font-size:11px;color:#888;margin-bottom:4px;">Right</div>
                                ${splitRow(log.flushed_right, flushedPrimeR, log.flushed_right > 0 ? 'text-danger' : '', flushedPrimeR > 0 ? 'text-danger' : '')}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="callout callout-info mb-0" style="font-size:12px;">
                    <b>How it worked:</b><br>
                    ${hasPrime
                        ? `New L: ${leftPremium} premium + ${leftPrime} prime (÷2=${Math.floor(leftPrime/2)}) + Carry(${log.carry_in_left}) = <b>${log.total_left}</b> equiv<br>
                           New R: ${rightPremium} premium + ${rightPrime} prime (÷2=${Math.floor(rightPrime/2)}) + Carry(${log.carry_in_right}) = <b>${log.total_right}</b> equiv<br>`
                        : `New L(${log.new_left}) + Carry(${log.carry_in_left}) = <b>${log.total_left}</b><br>
                           New R(${log.new_right}) + Carry(${log.carry_in_right}) = <b>${log.total_right}</b><br>`}
                    Min(${log.total_left}, ${log.total_right}) = <b>${log.matched_pairs}</b> matched → capped at <b>${log.capped_pairs}</b> → ₹<b>${parseFloat(log.income).toLocaleString('en-IN')}</b>
                    ${(log.carry_out_left > 0) ? `<br>Left carries <b>${log.carry_out_left}</b> forward` : ''}
                    ${(log.carry_out_right > 0) ? `<br>Right carries <b>${log.carry_out_right}</b> forward` : ''}
                    ${(log.flushed_left > 0 || log.flushed_right > 0) ? `<br><span class="text-danger">Flushed — L: ${log.flushed_left} | R: ${log.flushed_right}</span>` : ''}
                </div>`;
            })
            .catch(() => {
                document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">Failed to load data.</p>';
            });
    });
});

// ── Pairs popup ───────────────────────────────────────────────────────────────
document.querySelectorAll('.btn-pairs').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const logId = this.dataset.log;
        document.getElementById('pairsModalBody').innerHTML =
            '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
        $('#pairsModal').modal('show');

        fetch('{{ url("/admin/binary-income/log") }}/' + logId + '/pairs')
            .then(r => r.json())
            .then(data => {
                const log = data.log;
                let html = `<div class="row mb-3">
                    <div class="col-md-4"><b>Date:</b> ${log.date}</div>
                    <div class="col-md-4"><b>Package:</b> ${log.package}</div>
                    <div class="col-md-4"><b>Income:</b> <span class="text-success font-weight-bold">₹${log.income}</span></div>
                </div>`;

                if (log.carry_in_left > 0 || log.carry_in_right > 0) {
                    html += `<div class="alert alert-warning py-1 mb-3"><i class="fas fa-info-circle"></i>
                        Carry-in from previous run: <b>${log.carry_in_left}</b> Left, <b>${log.carry_in_right}</b> Right</div>`;
                }

                function fmtUserLine(u) {
                    const kindBadge = u._kind === 'prime'
                        ? `<span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:0.65em;">Prime</span>`
                        : u._kind === 'basic'
                        ? `<span class="badge" style="background:#e2f0fb;color:#0c4a7c;border:1px solid #3b8fd4;font-size:0.65em;">Basic</span>`
                        : `<span class="badge" style="background:#d4edda;color:#155724;border:1px solid #28a745;font-size:0.65em;">Premium</span>`;
                    const carryBadge = u.carry_in ? ` <span class="badge badge-warning" style="font-size:0.65em;">Carry Forward</span>` : '';
                    return `${kindBadge} ${u.connection||u.id} — ${u.name}${carryBadge}<br><small class="text-muted">${new Date(u.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</small>`;
                }
                function fmtCell(users) {
                    if (!users || !users.length) return '<span class="text-muted">—</span>';
                    return users.map(fmtUserLine).join('<hr style="margin:3px 0;">');
                }
                function rowStatusBadge(status) {
                    if (status === 'matched') return '<span class="badge badge-success">Matched</span>';
                    if (status === 'carry')   return '<span class="badge badge-warning">Carry Fwd</span>';
                    return '<span class="badge badge-danger">Flushed</span>';
                }

                const pkgKind    = log.package_code === 'basic_package' ? 'basic' : 'premium';
                const lPremPool  = data.left.map(u => Object.assign({}, u, {_kind: pkgKind}));
                const rPremPool  = data.right.map(u => Object.assign({}, u, {_kind: pkgKind}));
                const lPrimePool = data.has_prime ? data.left_prime.map(u => Object.assign({}, u, {_kind: 'prime'})) : [];
                const rPrimePool = data.has_prime ? data.right_prime.map(u => Object.assign({}, u, {_kind: 'prime'})) : [];

                function take2Equiv(pPool, prPool) {
                    if (pPool.length >= 2)                        return [pPool.shift(), pPool.shift()];
                    if (pPool.length >= 1 && prPool.length >= 2)  return [pPool.shift(), prPool.shift(), prPool.shift()];
                    if (prPool.length >= 4)                        return [prPool.shift(), prPool.shift(), prPool.shift(), prPool.shift()];
                    const r = []; if (pPool.length) r.push(pPool.shift()); while (prPool.length && r.length < 4) r.push(prPool.shift()); return r;
                }
                function take1PremFirst(pPool, prPool) {
                    if (pPool.length >= 1)  return [pPool.shift()];
                    if (prPool.length >= 2) return [prPool.shift(), prPool.shift()];
                    if (prPool.length >= 1) return [prPool.shift()];
                    return [];
                }

                const allRows = [];
                const capped = log.capped, isFirst = log.is_first_run;
                const leftIsPrimary = isFirst && (log.total_left >= log.total_right);
                if (capped > 0) {
                    if (isFirst) {
                        const lc = leftIsPrimary ? take2Equiv(lPremPool, lPrimePool) : take1PremFirst(lPremPool, lPrimePool);
                        const rc = leftIsPrimary ? take1PremFirst(rPremPool, rPrimePool) : take2Equiv(rPremPool, rPrimePool);
                        allRows.push({lc, rc, status: 'matched'});
                        for (let p = 1; p < capped; p++) allRows.push({lc: take1PremFirst(lPremPool, lPrimePool), rc: take1PremFirst(rPremPool, rPrimePool), status: 'matched'});
                    } else {
                        for (let p = 0; p < capped; p++) allRows.push({lc: take1PremFirst(lPremPool, lPrimePool), rc: take1PremFirst(rPremPool, rPrimePool), status: 'matched'});
                    }
                }
                const lCarry = (log.carry_out_left > 0 || log.prime_carry_out_left > 0) ? 'carry' : 'flushed';
                const rCarry = (log.carry_out_right > 0 || log.prime_carry_out_right > 0) ? 'carry' : 'flushed';
                while (lPremPool.length || lPrimePool.length) { const lc = take1PremFirst(lPremPool, lPrimePool); if (!lc.length) break; allRows.push({lc, rc: [], status: lCarry}); }
                while (rPremPool.length || rPrimePool.length) { const rc = take1PremFirst(rPremPool, rPrimePool); if (!rc.length) break; allRows.push({lc: [], rc, status: rCarry}); }

                html += `<table class="table table-sm table-bordered">
                    <thead class="thead-dark"><tr><th>#</th><th>Left</th><th>Right</th><th>Status</th></tr></thead><tbody>`;
                if (allRows.length === 0) html += `<tr><td colspan="4" class="text-center text-muted">No activations this run</td></tr>`;
                allRows.forEach(({lc, rc, status}, i) => {
                    const cls = status === 'matched' ? 'table-success' : '';
                    html += `<tr class="${cls}"><td>${i+1}</td><td>${fmtCell(lc)}</td><td>${fmtCell(rc)}</td><td>${rowStatusBadge(status)}</td></tr>`;
                });
                html += `</tbody></table>`;
                if (log.capped > 0) html += `<div class="alert alert-success mt-2 mb-0"><i class="fas fa-check-circle mr-1"></i><b>${log.capped} pair${log.capped>1?'s':''}</b> matched — income of <b>₹${log.income}</b>.</div>`;
                document.getElementById('pairsModalBody').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('pairsModalBody').innerHTML = '<div class="alert alert-danger">Failed to load pair details.</div>';
            });
    });
});
</script>
@endsection

@endsection
