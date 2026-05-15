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
                                            data-package-id="{{ $log->package_id ?? '' }}"
                                            data-package="{{ $log->package_type === 'basic_package' ? 'Basic' : 'Premium' }}"
                                            title="View detail">
                                        <i class="fas fa-search"></i>
                                    </button>
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

@section('scripts')
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
                const hasPrime      = data.has_prime || false;
                const leftPremium   = data.left_premium  ?? log.new_left;
                const rightPremium  = data.right_premium ?? log.new_right;
                const leftPrime     = data.left_prime    ?? 0;
                const rightPrime    = data.right_prime   ?? 0;
                const primeCarryInL = data.prime_carry_in_left  ?? 0;
                const primeCarryInR = data.prime_carry_in_right ?? 0;
                const isBasic = log.package_type === 'basic_package';
                const pBadge  = isBasic
                    ? `<span class="badge" style="background:#cce5ff;color:#004085;border:1px solid #007bff;font-size:10px;">Basic</span>`
                    : `<span class="badge" style="background:#d4edda;color:#155724;border:1px solid #28a745;font-size:10px;">Premium</span>`;
                const prBadge = `<span class="badge" style="background:#fff3e0;color:#7a3300;border:1px solid #fd7e14;font-size:10px;">Prime</span>`;

                function row(label, val, cls='') {
                    return `<div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span class="text-muted">${label}</span>
                        <span class="font-weight-bold ${cls}">${val}</span>
                    </div>`;
                }

                document.getElementById('incomeDetailBody').innerHTML = `
                    <h6 class="text-primary border-bottom pb-1 mb-3">Pair Calculation — ${date}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-info mb-2">
                                <div class="card-header p-2 font-weight-bold" style="font-size:13px;">Left Leg</div>
                                <div class="card-body p-2">
                                    ${row('New', leftPremium)}
                                    ${hasPrime ? row('New (Prime)', leftPrime, 'text-warning') : ''}
                                    ${row('Carry In', log.carry_in_left, 'text-secondary')}
                                    ${row('Total', log.total_left, 'text-primary')}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-warning mb-2">
                                <div class="card-header p-2 font-weight-bold" style="font-size:13px;">Right Leg</div>
                                <div class="card-body p-2">
                                    ${row('New', rightPremium)}
                                    ${hasPrime ? row('New (Prime)', rightPrime, 'text-warning') : ''}
                                    ${row('Carry In', log.carry_in_right, 'text-secondary')}
                                    ${row('Total', log.total_right, 'text-primary')}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline card-success mb-2">
                        <div class="card-header p-2 font-weight-bold" style="font-size:13px;">Result</div>
                        <div class="card-body p-2">
                            ${row('Matched Pairs', log.matched_pairs)}
                            ${row('Capped (Paid)', log.capped_pairs, 'text-primary')}
                            ${row('Income', '₹' + parseFloat(log.income).toLocaleString('en-IN', {minimumFractionDigits:2}), 'text-success')}
                            ${row('Carry Out L', log.carry_out_left, log.carry_out_left > 0 ? 'text-warning' : '')}
                            ${row('Carry Out R', log.carry_out_right, log.carry_out_right > 0 ? 'text-warning' : '')}
                            ${row('Flushed L', log.flushed_left, log.flushed_left > 0 ? 'text-danger' : '')}
                            ${row('Flushed R', log.flushed_right, log.flushed_right > 0 ? 'text-danger' : '')}
                        </div>
                    </div>
                `;
            })
            .catch(() => {
                document.getElementById('incomeDetailBody').innerHTML = '<p class="text-danger">Failed to load detail.</p>';
            });
    });
});
</script>
@endsection

@endsection
