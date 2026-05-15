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
                    <tr class="{{ $packageLabel === 'Basic' ? 'bg-info' : 'bg-success' }} text-white">
                        <th>#</th>
                        <th>From</th>
                        <th>Package</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->from }}</td>
                        <td>{{ $row->package }}</td>
                        <td>{{ $row->type }}</td>
                        <td>₹{{ number_format($row->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">Total {{ $packageLabel }} Income: ₹{{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(function () { $('#binaryIncomeTable').DataTable(); });
</script>
@endsection

@endsection
