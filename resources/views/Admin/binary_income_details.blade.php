@extends('Admin.admin_header')
@section('title', 'Vishwastha | Binary Income Details')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Binary Income</h1></div>
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

            {{-- Run result alert --}}
            @if(session('run_result'))
                @php $r = session('run_result'); @endphp
                <div class="alert alert-{{ $r['status'] === 'success' ? 'success' : 'danger' }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{{ $r['status'] === 'success' ? 'Done!' : 'Error' }}</strong>
                    <br><small>{{ $r['output'] }}</small>
                </div>
            @endif

            {{-- Action bar --}}
            <div class="card card-outline card-primary">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <label class="mb-1 font-weight-bold">Run Income Calculation</label>
                            <form method="GET" action="{{ route('admin.binary_income.run') }}">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-play"></i> Run Now
                                </button>
                            </form>
                            <small class="text-muted">Cron URL: <code>{{ route('admin.binary_income.run') }}</code></small>
                        </div>
                        <div class="col-md-4 text-center border-left border-right">
                            <label class="mb-1 font-weight-bold text-danger">Danger Zone</label><br>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmClear()">
                                <i class="fas fa-trash"></i> Clear All Wallets & Logs
                            </button>
                            <form id="clearForm" method="POST" action="{{ route('admin.binary_income.clear_wallets') }}" style="display:none;">
                                @csrf
                            </form>
                        </div>
                        <div class="col-md-3 text-right">
                            <form method="GET" action="{{ route('admin.binary_income') }}" class="form-inline justify-content-end">
                                <input type="date" name="from_date" class="form-control form-control-sm mr-1" value="{{ $fromDate }}">
                                <input type="date" name="to_date" class="form-control form-control-sm mr-1" value="{{ $toDate }}">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== USER STATUS OVERVIEW ===== --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-1"></i> User Status Overview</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm mb-0" style="font-size:13px;">
                        <thead class="thead-dark">
                            <tr>
                                <th>User</th>
                                <th class="text-center text-success">Pair Income</th>
                                <th class="text-center text-info">Referral Income</th>
                                <th class="text-center text-primary">Total Wallet</th>
                                <th class="text-center">Carry Fwd L</th>
                                <th class="text-center">Carry Fwd R</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($userSummary as $u)
                            <tr>
                                <td>
                                    <span class="font-weight-bold text-primary">{{ $u->connection }}</span><br>
                                    <small class="text-muted">{{ $u->name }}</small>
                                </td>
                                <td class="text-center text-success font-weight-bold">
                                    ₹{{ number_format($u->pair_income, 2) }}
                                </td>
                                <td class="text-center text-info font-weight-bold">
                                    ₹{{ number_format($u->sponsor_income, 2) }}
                                </td>
                                <td class="text-center font-weight-bold">
                                    ₹{{ number_format($u->balance, 2) }}
                                </td>
                                <td class="text-center {{ $u->carry_forward_left > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $u->carry_forward_left }}
                                </td>
                                <td class="text-center {{ $u->carry_forward_right > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $u->carry_forward_right }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No users with binary activity yet.</td></tr>
                            @endforelse
                        </tbody>
                        @if($userSummary->count() > 0)
                        <tfoot class="bg-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-center text-success">₹{{ number_format($userSummary->sum('pair_income'), 2) }}</th>
                                <th class="text-center text-info">₹{{ number_format($userSummary->sum('sponsor_income'), 2) }}</th>
                                <th class="text-center">₹{{ number_format($userSummary->sum('balance'), 2) }}</th>
                                <th></th><th></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- ===== PER-PACKAGE CARRY-FORWARD STATUS ===== --}}
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-forward mr-1"></i> Package Carry-Forward Status (after last run)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-sm mb-0" style="font-size:13px;">
                        <thead class="thead-dark">
                            <tr>
                                <th>User</th>
                                <th>Package</th>
                                <th class="text-center">Carry Fwd Left</th>
                                <th class="text-center">Carry Fwd Right</th>
                                <th class="text-center">Last Run Income</th>
                                <th class="text-center">Last Run At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packageStatus as $ps)
                            <tr>
                                <td>
                                    <span class="font-weight-bold text-primary">{{ $ps->connection }}</span><br>
                                    <small class="text-muted">{{ $ps->name }}</small>
                                </td>
                                <td><small>{{ $ps->package_name }}</small></td>
                                <td class="text-center {{ $ps->carry_out_left > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $ps->carry_out_left > 0 ? $ps->carry_out_left . ' units' : '—' }}
                                </td>
                                <td class="text-center {{ $ps->carry_out_right > 0 ? 'text-warning font-weight-bold' : 'text-muted' }}">
                                    {{ $ps->carry_out_right > 0 ? $ps->carry_out_right . ' units' : '—' }}
                                </td>
                                <td class="text-center text-success font-weight-bold">
                                    ₹{{ number_format($ps->last_income, 2) }}
                                </td>
                                <td class="text-center text-muted" style="font-size:11px;">
                                    {{ \Carbon\Carbon::parse($ps->last_run)->format('d M Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No calculation runs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary cards for filtered period --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h4>₹{{ number_format($logs->sum('income'), 2) }}</h4>
                            <p>Pair Income (period)</p>
                        </div>
                        <div class="icon"><i class="fas fa-rupee-sign"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>{{ $logs->sum('capped_pairs') }}</h4>
                            <p>Total Pairs Matched</p>
                        </div>
                        <div class="icon"><i class="fas fa-handshake"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h4>{{ $logs->unique('user_id')->count() }}</h4>
                            <p>Users Earned</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
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
                    <h3 class="card-title">Pair Log — {{ $fromDate }} to {{ $toDate }}</h3>
                </div>
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-bordered table-sm mb-0" style="font-size:13px;">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" class="align-middle">#</th>
                                <th rowspan="2" class="align-middle">Date</th>
                                <th rowspan="2" class="align-middle">User</th>
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
                            @forelse($logs as $i => $log)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->calc_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="text-primary font-weight-bold">{{ $log->user->connection ?? '—' }}</span><br>
                                    <small>{{ $log->user->name ?? '—' }}</small>
                                </td>
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
                                            data-user="{{ $log->user_id }}"
                                            data-date="{{ \Carbon\Carbon::parse($log->calc_date)->toDateString() }}"
                                            data-package-id="{{ $log->package_id }}"
                                            data-package="{{ $log->package->name ?? $log->package_type }}"
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
                            <tr><td colspan="18" class="text-center text-muted py-3">No income records for this period.</td></tr>
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

@endsection

@section('footer')
<script>
function confirmClear() {
    Swal.fire({
        title: 'Clear all binary data?',
        html: 'This will <b>delete all pair logs, transactions and reset all wallets to ₹0</b>.<br>This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, clear everything',
        cancelButtonText: 'Cancel',
    }).then(function(result) {
        if (result.isConfirmed) document.getElementById('clearForm').submit();
    });
}

document.querySelectorAll('.btn-popup').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const userId    = this.dataset.user;
        const date      = this.dataset.date;
        const pkg       = this.dataset.package;
        const packageId = this.dataset.packageId;
        document.getElementById('incomeDetailTitle').textContent = 'Detail — ' + pkg + ' — ' + date;
        document.getElementById('incomeDetailBody').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading…</div>';
        $('#incomeDetailModal').modal('show');

        fetch('{{ route('admin.binary_income.popup') }}?user_id=' + userId + '&date=' + date + '&package_id=' + packageId)
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">' + data.error + '</p>';
                    return;
                }
                const log = data.log;
                const w   = data.wallet || {};
                document.getElementById('incomeDetailBody').innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary border-bottom pb-1">Pair Calculation — ${date}</h6>
                        <table class="table table-sm table-bordered">
                            <tbody>
                                <tr class="table-info">
                                    <th>New Left</th><td>${log.new_left}</td>
                                    <th>New Right</th><td>${log.new_right}</td>
                                </tr>
                                <tr>
                                    <th>Carry In Left</th><td>${log.carry_in_left}</td>
                                    <th>Carry In Right</th><td>${log.carry_in_right}</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <th>Total Left</th><td>${log.total_left}</td>
                                    <th>Total Right</th><td>${log.total_right}</td>
                                </tr>
                                <tr>
                                    <th>Matched Pairs</th><td>${log.matched_pairs}</td>
                                    <th>Capped</th><td class="text-primary font-weight-bold">${log.capped_pairs}</td>
                                </tr>
                                <tr class="table-success">
                                    <th>Income Earned</th>
                                    <td colspan="3" class="text-success font-weight-bold" style="font-size:16px;">
                                        ₹${parseFloat(log.income).toLocaleString('en-IN', {minimumFractionDigits:2})}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Carry Out Left</th>
                                    <td class="${log.carry_out_left > 0 ? 'text-warning font-weight-bold' : ''}">${log.carry_out_left}</td>
                                    <th>Carry Out Right</th>
                                    <td class="${log.carry_out_right > 0 ? 'text-warning font-weight-bold' : ''}">${log.carry_out_right}</td>
                                </tr>
                                <tr>
                                    <th>Flushed Left</th>
                                    <td class="${log.flushed_left > 0 ? 'text-danger' : ''}">${log.flushed_left}</td>
                                    <th>Flushed Right</th>
                                    <td class="${log.flushed_right > 0 ? 'text-danger' : ''}">${log.flushed_right}</td>
                                </tr>
                                ${(log.prime_carry_out_left > 0 || log.prime_carry_out_right > 0) ? `
                                <tr class="table-warning">
                                    <th>Prime Carry Out Left</th>
                                    <td class="text-warning font-weight-bold">${log.prime_carry_out_left} prime</td>
                                    <th>Prime Carry Out Right</th>
                                    <td class="text-warning font-weight-bold">${log.prime_carry_out_right} prime</td>
                                </tr>` : ''}
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <div class="callout callout-info mt-2" style="font-size:12px;">
                            <b>How it worked:</b><br>
                            New L(${log.new_left}) + Carry(${log.carry_in_left}) = <b>${log.total_left}</b> left<br>
                            New R(${log.new_right}) + Carry(${log.carry_in_right}) = <b>${log.total_right}</b> right<br>
                            ${(() => {
                                const L = log.total_left, R = log.total_right;
                                const isFirst = log.carry_in_left === 0 && log.carry_in_right === 0 && log.matched_pairs < Math.min(L, R);
                                if (isFirst) {
                                    const leftPrimary = L >= R;
                                    const primary = leftPrimary ? 'Left' : 'Right';
                                    const secondary = leftPrimary ? 'Right' : 'Left';
                                    return `<span class="text-warning">Unlock 2:1 — 2 from ${primary} + 1 from ${secondary} = 1st pair</span><br>` +
                                           `Remaining: ${leftPrimary ? L-2 : L-1}L vs ${leftPrimary ? R-1 : R-2}R → ${log.matched_pairs - 1} more pair(s)<br>`;
                                }
                                return `Min(${L}, ${R}) = <b>${log.matched_pairs}</b> matched<br>`;
                            })()}
                            Capped at <b>${log.capped_pairs}</b> → ₹<b>${parseFloat(log.income).toLocaleString('en-IN')}</b><br>
                            ${log.carry_out_left  > 0 ? `Left carries <b>${log.carry_out_left}</b> forward<br>`  : ''}
                            ${log.carry_out_right > 0 ? `Right carries <b>${log.carry_out_right}</b> forward<br>` : ''}
                            ${(log.flushed_left > 0 || log.flushed_right > 0) ? `<span class="text-danger">Flushed: L${log.flushed_left} R${log.flushed_right}</span>` : ''}
                        </div>
                    </div>
                </div>`;
            })
            .catch(() => {
                document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">Failed to load data.</p>';
            });
    });
});

// ── Paired Users Modal ────────────────────────────────────────────────────────
const pairsModal = new bootstrap.Modal(document.getElementById('pairsModal') || document.createElement('div'));

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

                if (data.has_prime && (log.prime_carry_in_left > 0 || log.prime_carry_in_right > 0)) {
                    html += `<div class="alert alert-info py-1 mb-3"><i class="fas fa-info-circle"></i>
                        Prime carry-in from previous run: <b>${log.prime_carry_in_left}</b> Left, <b>${log.prime_carry_in_right}</b> Right
                        (odd prime from last cycle counted here)</div>`;
                }

                function statusBadge(u) {
                    if (!u) return '';
                    if (u.status === 'matched')    return '<span class="badge badge-success">Matched</span>';
                    if (u.status === 'carry')      return '<span class="badge badge-warning">Carry Fwd</span>';
                    return '<span class="badge badge-danger">Flushed</span>';
                }
                function buildRows(left, right) {
                    const rows = [];
                    let li = 0, ri = 0;
                    while (li < left.length || ri < right.length) {
                        const l = left[li], r = right[ri];
                        if (l && l.status === 'first_sale') {
                            // 2:1 rule extra activation — merge into the first matched row, not a separate row
                            if (rows.length > 0) rows[rows.length - 1].extra_l = l;
                            li++;
                        } else if (r && r.status === 'first_sale') {
                            if (rows.length > 0) rows[rows.length - 1].extra_r = r;
                            ri++;
                        } else if (l && r && l.status === 'matched' && r.status === 'matched') {
                            rows.push({l, r}); li++; ri++;
                        } else if (l) {
                            rows.push({l, r: null}); li++;
                        } else {
                            rows.push({l: null, r}); ri++;
                        }
                    }
                    return rows;
                }

                const fmtUser = u => `${u.connection||u.id} — ${u.name}${u.carry_in ? ' <span class="badge badge-warning" style="font-size:0.7em;">Carry Forward</span>' : ''}<br><small class="text-muted">${new Date(u.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</small>`;
                const fmtPrimeInCell = arr => arr.length
                    ? `<div style="margin-top:5px;padding-top:4px;border-top:1px dashed #fd7e14;">${arr.map(u => `<small><span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:0.65em;">Prime</span> ${u.connection||u.id} — ${u.name}<br><span class="text-muted" style="font-size:0.75em;">${new Date(u.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</span></small>`).join('<br>')}</div>`
                    : '';

                const premiumRows = buildRows(data.left, data.right);

                // ── Distribute prime users inline into premium rows ────────────
                // Pass 1: assign 2 prime users to each matched/first_sale row (left then right)
                // Pass 2: assign remaining prime to the first available carry row on each side
                if (data.has_prime) {
                    let lPool = [...data.left_prime];
                    let rPool = [...data.right_prime];
                    premiumRows.forEach(row => { row.lPrime = []; row.rPrime = []; });

                    premiumRows.forEach(row => {
                        const ls = row.l ? row.l.status : null;
                        const rs = row.r ? row.r.status : null;
                        if (lPool.length >= 2 && (ls === 'matched' || ls === 'first_sale'))
                            row.lPrime = lPool.splice(0, 2);
                        if (rPool.length >= 2 && (rs === 'matched' || rs === 'first_sale'))
                            row.rPrime = rPool.splice(0, 2);
                    });
                    premiumRows.forEach(row => {
                        const ls = row.l ? row.l.status : null;
                        const rs = row.r ? row.r.status : null;
                        if (lPool.length > 0 && (ls === 'carry' || ls === 'flushed') && !row.lPrime.length)
                            row.lPrime = lPool.splice(0, Math.min(2, lPool.length));
                        if (rPool.length > 0 && (rs === 'carry' || rs === 'flushed') && !row.rPrime.length)
                            row.rPrime = rPool.splice(0, Math.min(2, rPool.length));
                    });
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

                if (premiumRows.length === 0) {
                    html += `<tr><td colspan="4" class="text-center text-muted">No activations this run</td></tr>`;
                }

                const fmtExtraUser = u => `<div style="margin-top:5px;padding-top:4px;border-top:1px dashed #6f42c1;"><small><span class="badge" style="background:#6f42c1;color:#fff;font-size:0.65em;">2:1</span> ${u.connection||u.id} — ${u.name}<br><span class="text-muted" style="font-size:0.75em;">${new Date(u.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</span></small></div>`;

                premiumRows.forEach(({l, r, lPrime, rPrime, extra_l, extra_r}, i) => {
                    const status = l ? l.status : r ? r.status : 'carry';
                    const cls = (status === 'matched') ? 'table-success' : '';
                    const leftCell  = l ? fmtUser(l) + (extra_l ? fmtExtraUser(extra_l) : '') + fmtPrimeInCell(lPrime||[])
                                       : (lPrime && lPrime.length ? fmtPrimeInCell(lPrime) : '<span class="text-muted">—</span>');
                    const rightCell = r ? fmtUser(r) + (extra_r ? fmtExtraUser(extra_r) : '') + fmtPrimeInCell(rPrime||[])
                                       : (rPrime && rPrime.length ? fmtPrimeInCell(rPrime) : '<span class="text-muted">—</span>');
                    html += `<tr class="${cls}">
                        <td>${i+1}</td>
                        <td>${leftCell}</td>
                        <td>${rightCell}</td>
                        <td>${statusBadge(l||r)}</td>
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

{{-- Pairs Modal --}}
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
