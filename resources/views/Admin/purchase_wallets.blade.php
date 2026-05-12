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
            <div class="row mb-3">
                @foreach($cards as $type => $card)
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $card['color'] }}">
                        <div class="inner">
                            <h4>₹{{ number_format($totals[$type] ?? 0, 2) }}</h4>
                            <p>{{ $card['label'] }}</p>
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
                            @php $activeCount = ($members[$type] ?? collect())->where('status', 1)->count(); @endphp
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded border">
                                <div>
                                    <span class="font-weight-bold">Undistributed Pool:</span>
                                    <span class="text-primary font-weight-bold ml-1">₹{{ number_format($totals[$type] ?? 0, 2) }}</span>
                                    <span class="text-muted ml-3">Active Members: <strong>{{ $activeCount }}</strong></span>
                                    @if($activeCount > 0 && ($totals[$type] ?? 0) > 0)
                                        <span class="text-muted ml-3">→ Each gets:
                                            <strong class="text-success">₹{{ number_format(floor(($totals[$type] ?? 0) / $activeCount), 2) }}</strong>
                                            | Remainder: <strong class="text-warning">₹{{ number_format(($totals[$type] ?? 0) - floor(($totals[$type] ?? 0) / $activeCount) * $activeCount, 2) }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <form action="{{ route('purchase_wallets.distribute') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="wallet_type" value="{{ $type }}">
                                    <button type="submit" class="btn btn-success btn-sm"
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
                                        Members
                                        <span class="badge badge-secondary ml-1">{{ ($members[$type] ?? collect())->count() }}</span>
                                        <span class="badge badge-success ml-1">{{ $activeCount }} Active</span>
                                    </h6>
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr><th>#</th><th>ID</th><th>Name</th><th>Status</th></tr>
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
                                            <tr><td colspan="4" class="text-center text-muted">No members.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- RIGHT: Pending entries + distribution history --}}
                                <div class="col-md-8">

                                    {{-- Pending entries --}}
                                    <h6 class="font-weight-bold border-bottom pb-1 mb-2">
                                        <i class="fas fa-receipt mr-1"></i> Pending Purchase Entries
                                        <span class="badge badge-secondary ml-1">{{ ($entries[$type] ?? collect())->count() }}</span>
                                        <span class="float-right text-primary font-weight-bold">
                                            Pool: ₹{{ number_format($totals[$type] ?? 0, 2) }}
                                        </span>
                                    </h6>
                                    <div style="max-height:220px; overflow-y:auto;" class="mb-4">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="thead-dark" style="position:sticky;top:0;">
                                                <tr><th>#</th><th>User ID</th><th>Name</th><th>Package</th><th>Amount</th><th>Date</th></tr>
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
                                                <tr><td colspan="6" class="text-center text-muted">No pending entries.</td></tr>
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
                                        <i class="fas fa-history mr-1"></i> Distribution History
                                        <span class="badge badge-info ml-1">{{ ($distributions[$type] ?? collect())->count() }}</span>
                                    </h6>
                                    <div style="max-height:220px; overflow-y:auto;">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="thead-light" style="position:sticky;top:0;">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Pool (₹)</th>
                                                    <th>Users</th>
                                                    <th>Per User (₹)</th>
                                                    <th>Distributed (₹)</th>
                                                    <th>Admin Reserve (₹)</th>
                                                    <th></th>
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
                                                    <td>
                                                        <button class="btn btn-xs btn-outline-info view-recipients-btn"
                                                            data-id="{{ $dist->id }}"
                                                            data-date="{{ $dist->created_at->format('d-m-Y H:i') }}"
                                                            data-type="{{ ucfirst($type) }}"
                                                            data-toggle="modal" data-target="#recipientsModal">
                                                            <i class="fas fa-users"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="7" class="text-center text-muted">No distributions yet.</td></tr>
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

{{-- Recipients Modal --}}
<div class="modal fade" id="recipientsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recipients — <span id="modalLabel"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modalSummary" class="alert alert-info mb-3"></div>
                <div id="modalBody">
                    <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footer')
<script>
function showTab(type) {
    setTimeout(function() { $('#link-' + type).tab('show'); }, 100);
}

$(document).on('click', '.view-recipients-btn', function () {
    var id   = $(this).data('id');
    var date = $(this).data('date');
    var type = $(this).data('type');

    $('#modalLabel').text(type + ' — ' + date);
    $('#modalSummary').text('');
    $('#modalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

    $.getJSON('{{ url("/distribution") }}/' + id + '/recipients', function (res) {
        var d = res.distribution;
        $('#modalSummary').html(
            'Pool: <strong>₹' + parseFloat(d.pool_amount).toFixed(2) + '</strong> &nbsp;|&nbsp; ' +
            'Per User: <strong class="text-success">₹' + parseFloat(d.per_user_amount).toFixed(2) + '</strong> &nbsp;|&nbsp; ' +
            'Users: <strong>' + d.user_count + '</strong> &nbsp;|&nbsp; ' +
            'Total Distributed: <strong>₹' + parseFloat(d.total_distributed).toFixed(2) + '</strong> &nbsp;|&nbsp; ' +
            'Admin Reserve: <strong class="text-warning">₹' + parseFloat(d.remainder).toFixed(2) + '</strong>'
        );

        var rows = '';
        $.each(res.credits, function (i, c) {
            rows += '<tr><td>' + (i+1) + '</td><td>' + c.connection + '</td><td>' + c.name + '</td><td class="text-success font-weight-bold">₹' + parseFloat(c.amount).toFixed(2) + '</td></tr>';
        });
        $('#modalBody').html(
            '<table class="table table-sm table-bordered table-striped">' +
            '<thead class="thead-dark"><tr><th>#</th><th>User ID</th><th>Name</th><th>Amount Received</th></tr></thead>' +
            '<tbody>' + (rows || '<tr><td colspan="4" class="text-center text-muted">No recipients.</td></tr>') + '</tbody>' +
            '</table>'
        );
    });
});
</script>
@endsection
