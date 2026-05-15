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
                <div class="col-md-6">
                    <div class="info-box {{ $packageLabel === 'Basic' ? 'bg-info' : 'bg-success' }}">
                        <span class="info-box-icon"><i class="fas fa-handshake"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pair Income</span>
                            <span class="info-box-number">₹{{ number_format($pairIncomeTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-user-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sponsor Income</span>
                            <span class="info-box-number">₹{{ number_format($sponsorIncomeTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Income is calculated and credited each time the admin runs the income processor. Showing all confirmed runs below.
            </div>

            {{-- Pair income table --}}
            <div class="card {{ $packageLabel === 'Basic' ? 'card-info' : 'card-success' }}">
                <div class="card-header">
                    <h3 class="card-title">{{ $packageLabel }} Pair Income</h3>
                    <div class="card-tools">
                        <span class="badge badge-light">Total ₹{{ number_format($pairIncomeTotal, 2) }}</span>
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
                                <th></th>
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
                                <td>
                                    @if($log->capped_pairs > 0)
                                    <button class="btn btn-xs btn-outline-info" onclick="viewPairs({{ $log->id }})">
                                        <i class="fas fa-users"></i> Pairs
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted">No pair income records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pairs Detail Modal --}}
            <div class="modal fade" id="pairsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title text-white"><i class="fas fa-users mr-1"></i> Matched Pairs Detail</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" id="pairsModalBody">
                            <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sponsor income table --}}
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

@section('scripts')
<script>
function viewPairs(logId) {
    $('#pairsModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
    $('#pairsModal').modal('show');

    fetch('{{ url("/admin/binary-income/log") }}/' + logId + '/pairs')
        .then(r => r.json())
        .then(data => {
            const log = data.log;
            let html = `
                <div class="row mb-3">
                    <div class="col-md-4"><b>Date:</b> ${log.date}</div>
                    <div class="col-md-4"><b>Package:</b> ${log.package}</div>
                    <div class="col-md-4"><b>Income:</b> <span class="text-success font-weight-bold">₹${log.income}</span></div>
                </div>`;

            if (log.carry_in_left > 0 || log.carry_in_right > 0) {
                html += `<div class="alert alert-warning py-1 mb-3">
                    <i class="fas fa-info-circle"></i>
                    Carry-in from previous run: <b>${log.carry_in_left}</b> Left, <b>${log.carry_in_right}</b> Right
                </div>`;
            }

            const rows = buildPairRows(data.left, data.right);
            html += `<table class="table table-sm table-bordered">
                <thead class="thead-dark">
                    <tr><th>#</th><th>Left User</th><th>Right User</th><th>Status</th></tr>
                </thead><tbody>`;

            if (rows.length === 0) {
                html += `<tr><td colspan="4" class="text-center text-muted">No activations this run</td></tr>`;
            }
            rows.forEach(({l, r}, i) => {
                const status = l ? l.status : r.status;
                const cls = status === 'matched' ? 'table-success' : status === 'first_sale' ? 'table-info' : '';
                const lCell = l ? `${l.connection||l.id} — ${l.name}<br><small class="text-muted">${new Date(l.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</small>` : '<span class="text-muted">—</span>';
                const rCell = r ? `${r.connection||r.id} — ${r.name}<br><small class="text-muted">${new Date(r.activated_at).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'})}</small>` : '<span class="text-muted">—</span>';
                const badge = statusBadge(l || r);
                html += `<tr class="${cls}"><td>${i+1}</td><td>${lCell}</td><td>${rCell}</td><td>${badge}</td></tr>`;
            });
            html += `</tbody></table>`;
            $('#pairsModalBody').html(html);
        })
        .catch(() => {
            $('#pairsModalBody').html('<div class="alert alert-danger">Failed to load pair details.</div>');
        });
}

function buildPairRows(left, right) {
    const rows = []; let li = 0, ri = 0;
    while (li < left.length || ri < right.length) {
        const l = left[li], r = right[ri];
        if (l && l.status === 'first_sale')                              { rows.push({l, r: null}); li++; }
        else if (r && r.status === 'first_sale')                         { rows.push({l: null, r}); ri++; }
        else if (l && r && l.status === 'matched' && r.status === 'matched') { rows.push({l, r}); li++; ri++; }
        else if (l)                                                       { rows.push({l, r: null}); li++; }
        else                                                              { rows.push({l: null, r}); ri++; }
    }
    return rows;
}

function statusBadge(u) {
    if (!u) return '';
    if (u.status === 'matched')    return '<span class="badge badge-success">Matched</span>';
    if (u.status === 'first_sale') return '<span class="badge" style="background:#6f42c1;color:#fff;">First Sale</span>';
    if (u.status === 'carry')      return '<span class="badge badge-warning">Carry Fwd</span>';
    return '<span class="badge badge-danger">Flushed</span>';
}
</script>
@endsection

@endsection
