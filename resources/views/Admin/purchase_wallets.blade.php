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

            {{-- Summary Cards --}}
            <div class="row mb-4">
                @php
                    $cards = [
                        'privilege' => ['label' => 'Privilege Member Wallet', 'icon' => 'fas fa-star', 'color' => 'bg-purple'],
                        'board'     => ['label' => 'Board Member Wallet',     'icon' => 'fas fa-users', 'color' => 'bg-warning'],
                        'executive' => ['label' => 'Executive Wallet',         'icon' => 'fas fa-briefcase', 'color' => 'bg-info'],
                        'royalty'   => ['label' => 'Royalty Wallet',           'icon' => 'fas fa-crown', 'color' => 'bg-success'],
                    ];
                @endphp
                @foreach($cards as $type => $card)
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $card['color'] }}">
                        <div class="inner">
                            <h4>₹{{ number_format($totals[$type] ?? 0, 2) }}</h4>
                            <p>{{ $card['label'] }}</p>
                        </div>
                        <div class="icon"><i class="{{ $card['icon'] }}"></i></div>
                        <a href="#tab-{{ $type }}" class="small-box-footer"
                           onclick="showTab('{{ $type }}')" style="cursor:pointer;">
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
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-privilege" id="link-privilege">
                                <i class="fas fa-star mr-1"></i> Privilege
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-board" id="link-board">
                                <i class="fas fa-users mr-1"></i> Board
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-executive" id="link-executive">
                                <i class="fas fa-briefcase mr-1"></i> Executive
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-royalty" id="link-royalty">
                                <i class="fas fa-crown mr-1"></i> Royalty
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        @foreach(['privilege','board','executive','royalty'] as $type)
                        <div class="tab-pane fade {{ $type === 'privilege' ? 'show active' : '' }}" id="tab-{{ $type }}">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>Package</th>
                                        <th>Amount (₹)</th>
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
                                        <td>{{ $entry->created_at->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No entries yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if(($entries[$type] ?? collect())->isNotEmpty())
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-right">Total</td>
                                        <td>₹{{ number_format($totals[$type] ?? 0, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
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
    setTimeout(function() {
        $('#link-' + type).tab('show');
    }, 100);
}
</script>
@endsection
