@extends('Admin.admin_header')
@section('title', 'Vishwastha | Pair Details')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Pair Details</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pair Details</li>
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
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h4>₹{{ number_format($totalIncome, 2) }}</h4>
                            <p>Total Pair Income</p>
                        </div>
                        <div class="icon"><i class="fas fa-rupee-sign"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ $logs->sum('capped_pairs') }}</h4>
                            <p>Total Pairs Paid</p>
                        </div>
                        <div class="icon"><i class="fas fa-handshake"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h4>{{ $logs->count() }}</h4>
                            <p>Total Runs</p>
                        </div>
                        <div class="icon"><i class="fas fa-sync"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h4>{{ $logs->sum('flushed_left') + $logs->sum('flushed_right') }}</h4>
                            <p>Total Flushed</p>
                        </div>
                        <div class="icon"><i class="fas fa-ban"></i></div>
                    </div>
                </div>
            </div>

            {{-- Main pair log table --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Pair Log</h3>
                </div>
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-bordered table-sm mb-0" style="font-size:13px;">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" class="align-middle text-center">#</th>
                                <th rowspan="2" class="align-middle">Date</th>
                                <th rowspan="2" class="align-middle text-center">Package</th>
                                <th colspan="2" class="text-center bg-info text-white">New</th>
                                <th colspan="2" class="text-center bg-secondary text-white">Carry In</th>
                                <th colspan="2" class="text-center bg-primary text-white">Total</th>
                                <th rowspan="2" class="align-middle text-center">Matched</th>
                                <th rowspan="2" class="align-middle text-center">Capped</th>
                                <th rowspan="2" class="align-middle text-center text-success">Income ₹</th>
                                <th colspan="2" class="text-center bg-warning">Carry Out</th>
                                <th colspan="2" class="text-center bg-danger text-white">Flushed</th>
                                <th rowspan="2" class="align-middle text-center">Detail</th>
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
                            @forelse($logs as $i => $log)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td style="white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($log->calc_date)->format('d M Y') }}
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    @if($log->package_type === 'basic_package')
                                        <span class="badge badge-info">Basic</span>
                                    @else
                                        <span class="badge badge-success">Premium</span>
                                    @endif
                                </td>
                                <td class="text-center text-info font-weight-bold">{{ $log->new_left }}</td>
                                <td class="text-center text-info font-weight-bold">{{ $log->new_right }}</td>
                                <td class="text-center text-secondary">{{ $log->carry_in_left }}</td>
                                <td class="text-center text-secondary">{{ $log->carry_in_right }}</td>
                                <td class="text-center font-weight-bold">{{ $log->total_left }}</td>
                                <td class="text-center font-weight-bold">{{ $log->total_right }}</td>
                                <td class="text-center">{{ $log->matched_pairs }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ $log->capped_pairs }}</td>
                                <td class="text-center font-weight-bold text-success">₹{{ number_format($log->income, 2) }}</td>
                                <td class="text-center {{ $log->carry_out_left  > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_left }}</td>
                                <td class="text-center {{ $log->carry_out_right > 0 ? 'text-warning font-weight-bold' : '' }}">{{ $log->carry_out_right }}</td>
                                <td class="text-center {{ $log->flushed_left  > 0 ? 'text-danger' : '' }}">{{ $log->flushed_left }}</td>
                                <td class="text-center {{ $log->flushed_right > 0 ? 'text-danger' : '' }}">{{ $log->flushed_right }}</td>
                                <td class="text-center text-nowrap">
                                    <button class="btn btn-xs btn-info btn-popup"
                                            data-log="{{ $log->id }}"
                                            data-user="{{ $log->user_id }}"
                                            data-date="{{ \Carbon\Carbon::parse($log->calc_date)->toDateString() }}"
                                            data-package-id="{{ $log->package_id }}"
                                            data-package="{{ $log->package_type === 'basic_package' ? 'Basic' : 'Premium' }}"
                                            title="Calculation detail">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if($log->capped_pairs > 0)
                                    <button class="btn btn-xs btn-success ml-1 btn-pairs"
                                            data-log="{{ $log->id }}"
                                            title="Paired users">
                                        <i class="fas fa-users"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="17" class="text-center text-muted py-3">No pair records found yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($logs->count())
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="11" class="text-right">Totals:</th>
                                <th class="text-center text-success">₹{{ number_format($totalIncome, 2) }}</th>
                                <th class="text-center text-warning">{{ $logs->sum('carry_out_left') }}</th>
                                <th class="text-center text-warning">{{ $logs->sum('carry_out_right') }}</th>
                                <th class="text-center text-danger">{{ $logs->sum('flushed_left') }}</th>
                                <th class="text-center text-danger">{{ $logs->sum('flushed_right') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Calculation Detail Modal --}}
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

{{-- Paired Users Modal --}}
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

@endsection

@section('footer')
<script>
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
                const w   = data.wallet || {};
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
                    ? `<span class="badge" style="background:#cce5ff;color:#004085;border:1px solid #007bff;font-size:10px;vertical-align:middle;">Basic</span>`
                    : `<span class="badge" style="background:#d4edda;color:#155724;border:1px solid #28a745;font-size:10px;vertical-align:middle;">Premium</span>`;
                const prBadge = `<span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:10px;vertical-align:middle;">Prime</span>`;
                function sectionCard(title, color, rows) {
                    return `<div class="card card-outline card-${color} mb-2">
                        <div class="card-header p-2 font-weight-bold" style="font-size:13px;">${title}</div>
                        <div class="card-body p-2" style="font-size:13px;">${rows}</div>
                    </div>`;
                }
                function splitRow(pVal, prVal, pClass='', prClass='') {
                    return `<div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span>${pBadge} <span class="${pClass} font-weight-bold" style="font-size:13px;">${pVal}</span></span>
                        ${hasPrime ? `<span>${prBadge} <span class="${prClass} font-weight-bold" style="font-size:13px;">${prVal}</span></span>` : ''}
                    </div>`;
                }
                document.getElementById('incomeDetailBody').innerHTML = `
                <h6 class="text-primary border-bottom pb-1 mb-3" style="font-size:14px;">Pair Calculation — ${date}</h6>

                <div class="row mb-1">
                    <div class="col-6 pr-1">
                        ${sectionCard('New Left', 'primary', splitRow(leftPremium, leftPrime))}
                    </div>
                    <div class="col-6 pl-1">
                        ${sectionCard('New Right', 'primary', splitRow(rightPremium, rightPrime))}
                    </div>
                </div>

                ${(log.carry_in_left > 0 || log.carry_in_right > 0) ? `
                <div class="row mb-1">
                    <div class="col-6 pr-1">
                        ${sectionCard('Carry In Left', 'info', splitRow(log.carry_in_left, 0, log.carry_in_left > 0 ? 'text-info' : '', ''))}
                    </div>
                    <div class="col-6 pl-1">
                        ${sectionCard('Carry In Right', 'info', splitRow(log.carry_in_right, 0, log.carry_in_right > 0 ? 'text-info' : '', ''))}
                    </div>
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
                    <div class="col-6 pr-1">
                        ${sectionCard('Carry Out Left', 'warning', splitRow(log.carry_out_left, log.prime_carry_out_left ?? 0, log.carry_out_left > 0 ? 'text-warning' : '', (log.prime_carry_out_left ?? 0) > 0 ? 'text-warning' : ''))}
                    </div>
                    <div class="col-6 pl-1">
                        ${sectionCard('Carry Out Right', 'warning', splitRow(log.carry_out_right, log.prime_carry_out_right ?? 0, log.carry_out_right > 0 ? 'text-warning' : '', (log.prime_carry_out_right ?? 0) > 0 ? 'text-warning' : ''))}
                    </div>
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
                    <b style="font-size:12px;">How it worked:</b><br>
                    ${hasPrime
                        ? `<span style="font-size:12px;">New L: ${leftPremium} premium + ${leftPrime} prime (÷2=${Math.floor(leftPrime/2)}) + Carry(${log.carry_in_left}) = <b>${log.total_left}</b> equiv</span><br>
                           <span style="font-size:12px;">New R: ${rightPremium} premium + ${rightPrime} prime (÷2=${Math.floor(rightPrime/2)}) + Carry(${log.carry_in_right}) = <b>${log.total_right}</b> equiv</span><br>`
                        : `<span style="font-size:12px;">New L(${log.new_left}) + Carry(${log.carry_in_left}) = <b>${log.total_left}</b> left</span><br>
                           <span style="font-size:12px;">New R(${log.new_right}) + Carry(${log.carry_in_right}) = <b>${log.total_right}</b> right</span><br>`}
                    ${(() => {
                        const L = log.total_left, R = log.total_right;
                        if (log.is_first_run) {
                            const leftPrimary = L >= R;
                            const primary   = leftPrimary ? 'Left' : 'Right';
                            const secondary = leftPrimary ? 'Right' : 'Left';
                            const extraPairs = log.matched_pairs - 1;
                            return `<span class="text-warning" style="font-size:12px;">First run 2:1 — 2 from ${primary} + 1 from ${secondary} = 1st pair</span><br>` +
                                   (extraPairs > 0 ? `<span style="font-size:12px;">Then 1:1 → <b>${extraPairs}</b> more pair(s)</span><br>` : '');
                        }
                        return `<span style="font-size:12px;">Min(${L}, ${R}) = <b>${log.matched_pairs}</b> matched</span><br>`;
                    })()}
                    <span style="font-size:12px;">Capped at <b>${log.capped_pairs}</b> → ₹<b>${parseFloat(log.income).toLocaleString('en-IN')}</b></span><br>
                    ${(log.carry_out_left > 0 || (log.prime_carry_out_left ?? 0) > 0) ? `<span style="font-size:12px;">Left carries <b>${log.carry_out_left}</b> ${isBasic ? 'basic' : 'premium'}${(log.prime_carry_out_left ?? 0) > 0 ? ` + <b>${log.prime_carry_out_left}</b> prime` : ''} forward</span><br>` : ''}
                    ${(log.carry_out_right > 0 || (log.prime_carry_out_right ?? 0) > 0) ? `<span style="font-size:12px;">Right carries <b>${log.carry_out_right}</b> ${isBasic ? 'basic' : 'premium'}${(log.prime_carry_out_right ?? 0) > 0 ? ` + <b>${log.prime_carry_out_right}</b> prime` : ''} forward</span><br>` : ''}
                    ${(log.flushed_left > 0 || flushedPrimeL > 0 || log.flushed_right > 0 || flushedPrimeR > 0)
                        ? `<span class="text-danger" style="font-size:12px;">Flushed — L: ${log.flushed_left} premium, ${flushedPrimeL} prime &nbsp;|&nbsp; R: ${log.flushed_right} premium, ${flushedPrimeR} prime</span>` : ''}
                </div>`;
            })
            .catch(() => {
                document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">Failed to load data.</p>';
            });
    });
});

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
                        Carry-in from previous run: <b>${log.carry_in_left}</b> Left, <b>${log.carry_in_right}</b> Right
                        (shown with <span class="badge badge-warning">Carry Forward</span> badge below)</div>`;
                }

                function fmtUserLine(u) {
                    const kindBadge = u._kind === 'prime'
                        ? `<span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:0.65em;">Prime</span>`
                        : u._kind === 'basic'
                        ? `<span class="badge" style="background:#e2f0fb;color:#0c4a7c;border:1px solid #3b8fd4;font-size:0.65em;">Basic</span>`
                        : `<span class="badge" style="background:#d4edda;color:#155724;border:1px solid #28a745;font-size:0.65em;">Premium</span>`;
                    const carryBadge = u.carry_in
                        ? ` <span class="badge badge-warning" style="font-size:0.65em;">Carry Forward</span>` : '';
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
                    if (pPool.length >= 1 && prPool.length >= 2) return [pPool.shift(), prPool.shift(), prPool.shift()];
                    if (prPool.length >= 4)                       return [prPool.shift(), prPool.shift(), prPool.shift(), prPool.shift()];
                    const r = [];
                    if (pPool.length) r.push(pPool.shift());
                    while (prPool.length && r.length < 4) r.push(prPool.shift());
                    return r;
                }
                function take1PremFirst(pPool, prPool) {
                    if (pPool.length >= 1)  return [pPool.shift()];
                    if (prPool.length >= 2) return [prPool.shift(), prPool.shift()];
                    if (prPool.length >= 1) return [prPool.shift()];
                    return [];
                }

                const allRows = [];
                const capped        = log.capped;
                const isFirst       = log.is_first_run;
                const leftIsPrimary = isFirst && (log.total_left >= log.total_right);

                if (capped > 0) {
                    if (isFirst) {
                        const lc = leftIsPrimary ? take2Equiv(lPremPool, lPrimePool) : take1PremFirst(lPremPool, lPrimePool);
                        const rc = leftIsPrimary ? take1PremFirst(rPremPool, rPrimePool) : take2Equiv(rPremPool, rPrimePool);
                        allRows.push({lc, rc, status: 'matched'});
                        for (let p = 1; p < capped; p++) {
                            allRows.push({
                                lc: take1PremFirst(lPremPool, lPrimePool),
                                rc: take1PremFirst(rPremPool, rPrimePool),
                                status: 'matched'
                            });
                        }
                    } else {
                        for (let p = 0; p < capped; p++) {
                            allRows.push({
                                lc: take1PremFirst(lPremPool, lPrimePool),
                                rc: take1PremFirst(rPremPool, rPrimePool),
                                status: 'matched'
                            });
                        }
                    }
                }

                const lCarryStatus = (log.carry_out_left > 0 || log.prime_carry_out_left > 0) ? 'carry' : 'flushed';
                const rCarryStatus = (log.carry_out_right > 0 || log.prime_carry_out_right > 0) ? 'carry' : 'flushed';
                while (lPremPool.length || lPrimePool.length) {
                    const lc = take1PremFirst(lPremPool, lPrimePool);
                    if (!lc.length) break;
                    allRows.push({lc, rc: [], status: lCarryStatus});
                }
                while (rPremPool.length || rPrimePool.length) {
                    const rc = take1PremFirst(rPremPool, rPrimePool);
                    if (!rc.length) break;
                    allRows.push({lc: [], rc, status: rCarryStatus});
                }

                html += `<table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-arrow-left mr-1 text-primary"></i>Left</th>
                            <th><i class="fas fa-arrow-right mr-1 text-danger"></i>Right</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>`;

                if (allRows.length === 0) {
                    html += `<tr><td colspan="4" class="text-center text-muted">No activations this run</td></tr>`;
                }

                allRows.forEach(({lc, rc, status}, i) => {
                    const cls = status === 'matched' ? 'table-success' : '';
                    html += `<tr class="${cls}">
                        <td>${i+1}</td>
                        <td>${fmtCell(lc)}</td>
                        <td>${fmtCell(rc)}</td>
                        <td>${rowStatusBadge(status)}</td>
                    </tr>`;
                });

                html += `</tbody></table>`;

                if (log.capped > 0) {
                    html += `<div class="alert alert-success mt-2 mb-0">
                        <i class="fas fa-check-circle mr-1"></i>
                        <b>${log.capped} pair${log.capped>1?'s':''}</b> matched — income of <b>₹${log.income}</b>.</div>`;
                }

                document.getElementById('pairsModalBody').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('pairsModalBody').innerHTML =
                    '<div class="alert alert-danger">Failed to load pair details.</div>';
            });
    });
});
</script>
@endsection
