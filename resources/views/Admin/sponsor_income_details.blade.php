@extends('Admin.admin_header')
@section('title', 'vishwastha | Referral Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Referral Income Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Referral Income</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="card mt-3">
            <div class="card-header">
                <form method="GET" action="{{ route('sponsor_income_details') }}" class="form-inline">
                    <div class="form-group mr-2">
                        <label class="mr-1">From</label>
                        <input type="date" name="from_date" class="form-control form-control-sm"
                               value="{{ request('from_date') }}">
                    </div>
                    <div class="form-group mr-2">
                        <label class="mr-1">To</label>
                        <input type="date" name="to_date" class="form-control form-control-sm"
                               value="{{ request('to_date') }}">
                    </div>
                    <button type="submit" class="btn btn-info btn-sm mr-2">Filter</button>
                    <a href="{{ route('sponsor_income_details') }}" class="btn btn-secondary btn-sm">Reset</a>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                @if(Auth::check() && Auth::user()->role === 'superadmin')
                                    <th>Earned By</th>
                                @endif
                                <th>From (Activated By)</th>
                                <th>Package</th>
                                <th>Type</th>
                                <th>Amount (₹)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $index => $txn)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    @if(Auth::check() && Auth::user()->role === 'superadmin')
                                        <td>
                                            {{ $txn->sponsor->name ?? '-' }}<br>
                                            <small class="text-muted">{{ $txn->sponsor->connection ?? '' }}</small>
                                        </td>
                                    @endif
                                    <td>
                                        {{ $txn->user->name ?? '-' }}<br>
                                        <small class="text-muted">{{ $txn->user->connection ?? '' }}</small>
                                    </td>
                                    <td>{{ $txn->package->name ?? '-' }}</td>
                                    <td>
                                        @if($txn->package_category === 'prime_package')
                                            <span class="badge badge-warning">Prime Sponsor</span>
                                        @elseif($txn->package_category === 'premium_package')
                                            <span class="badge badge-success">Premium Sponsor</span>
                                        @else
                                            <span class="badge badge-info">Basic Sponsor</span>
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($txn->income, 2) }}</td>
                                    <td>{{ $txn->created_at->format('d-m-Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'superadmin' ? 7 : 6 }}" class="text-center text-muted">
                                        No referral income records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="{{ Auth::user()->role === 'superadmin' ? 7 : 6 }}" class="text-right">
                                    Total Referral Income: ₹{{ number_format($totalAmount, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(function () {
            $("#example1").DataTable({
                "order": []
            });
        });

        $(document).ready(function () {
            const teamLink = $('.nav-link.income');
            const treeviewLink = $('.nav.nav-treeview.income');
            const mainLiLink = $('.nav-item.has-treeview.income');
            const sponsorLink = $('.nav-link.sponsorincome');
            if (sponsorLink.length) {
                sponsorLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
