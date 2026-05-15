@extends('Admin.admin_header')
@section('title', 'Vishwastha | Pair Details')
@section('content')

<style>
    .side-badge  { font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
    .side-left   { background: #cfe2ff; color: #084298; }
    .side-right  { background: #fff3cd; color: #664d03; }
    .carry-note  { font-size: 0.7rem; color: #6c757d; display: block; margin-top: 2px; }
    .ratio-cell  { font-weight: 700; letter-spacing: 1px; }
    tfoot th     { background: #f4f6f9; }
</style>

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

    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table id="pairTable" class="table table-bordered table-striped text-center align-middle">
                <thead>
                    <tr class="bg-dark text-white">
                        <th>#</th>
                        <th>Date</th>
                        <th>Package</th>
                        <th>Left</th>
                        <th>Right</th>
                        <th>Ratio</th>
                        <th>Matched</th>
                        <th>Paid</th>
                        <th>Flushed</th>
                        <th>Carry Forward</th>
                        <th>Income</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $i => $log)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->calc_date)->format('d M Y') }}</td>
                        <td>
                            @if($log->package_type === 'basic_package')
                                <span class="badge badge-info">Basic</span>
                            @else
                                <span class="badge badge-success">Premium</span>
                            @endif
                        </td>

                        {{-- Left --}}
                        <td>
                            <strong>{{ $log->total_left }}</strong>
                            @if($log->carry_in_left > 0)
                                <span class="carry-note">{{ $log->new_left }} new + {{ $log->carry_in_left }} carried</span>
                            @else
                                <span class="carry-note">{{ $log->new_left }} new</span>
                            @endif
                        </td>

                        {{-- Right --}}
                        <td>
                            <strong>{{ $log->total_right }}</strong>
                            @if($log->carry_in_right > 0)
                                <span class="carry-note">{{ $log->new_right }} new + {{ $log->carry_in_right }} carried</span>
                            @else
                                <span class="carry-note">{{ $log->new_right }} new</span>
                            @endif
                        </td>

                        <td class="ratio-cell">{{ $log->total_left }} : {{ $log->total_right }}</td>

                        <td>{{ $log->matched_pairs }}</td>

                        <td>
                            {{ $log->capped_pairs }}
                            @if($log->matched_pairs > $log->capped_pairs)
                                <span class="carry-note">capped</span>
                            @endif
                        </td>

                        {{-- Flushed --}}
                        <td>
                            @if($log->flushed_left > 0 || $log->flushed_right > 0)
                                <span class="side-badge side-left">L {{ $log->flushed_left }}</span>
                                <span class="side-badge side-right">R {{ $log->flushed_right }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Carry Forward --}}
                        <td>
                            @if($log->carry_out_left > 0 || $log->carry_out_right > 0)
                                <span class="side-badge side-left">L {{ $log->carry_out_left }}</span>
                                <span class="side-badge side-right">R {{ $log->carry_out_right }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td><strong class="text-success">₹{{ number_format($log->income, 2) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-muted py-3">No pair records found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="10" class="text-right">Total Income:</th>
                        <th class="text-success">₹{{ number_format($totalIncome, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(function () {
        $('#pairTable').DataTable({
            order: [[1, 'desc']],
            columnDefs: [{ orderable: false, targets: [0] }]
        });
    });
</script>
@endsection

@endsection
