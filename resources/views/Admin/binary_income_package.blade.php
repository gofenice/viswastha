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

    <div class="card mt-3">
        <div class="card-body">
            <table id="binaryIncomeTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr class="{{ $packageLabel === 'Basic' ? 'bg-info' : ($packageLabel === 'Prime' ? 'bg-warning' : 'bg-success') }} text-white">
                        <th>#</th>
                        <th>Name</th>
                        <th>Connection ID</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Side</th>
                        <th>Activated On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->connection }}</td>
                        <td>
                            {{ $row->package_name }}
                            @if(!empty($row->upgraded_from_package_id))
                                <br><span class="badge badge-warning" style="font-size:0.7rem">2 Prime → Premium</span>
                            @endif
                        </td>
                        <td>₹{{ number_format($row->amount, 2) }}</td>
                        <td>
                            @if($row->side === 'left')
                                <span class="badge badge-primary">Left</span>
                            @else
                                <span class="badge badge-warning">Right</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($row->activated_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Members: {{ count($users) }}</th>
                        <th>₹{{ number_format(array_sum(array_column((array)$users, 'amount')), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(function () { $('#binaryIncomeTable').DataTable({ order: [[6, 'desc']] }); });
</script>
@endsection

@endsection
