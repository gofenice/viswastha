@extends('Admin.admin_header')
@section('title', 'vishwastha | GST & TCS List')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>GST & TCS List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">GST & TCS List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container">
            <div class="card recieptList col-md-11 mx-auto">

                <div class="card-header">
                    <h3 class="card-title">Amount List</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>From / To</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Created</th>
                                <th>Running Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $runningTotal = 0; // Initialize running total
                                $transactions = $adminAmountList->reverse(); // Reverse the list to process in chronological order
                            @endphp

                            @forelse($transactions as $index => $adminAmount)
                                @php
                                    if (in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21])) {
                                        $runningTotal -= $adminAmount->amount; // Deduct withdrawal amount for type 4,6 and 8
                                    } else {
                                        $runningTotal += $adminAmount->amount; // Add income amount for other types
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $adminAmount->fromUser->name ?? 'Rank' }}<br>{{ $adminAmount->fromUser->connection ?? '' }}
                                    </td>

                                    {{-- Color-coded Amount Display --}}
                                    <td
                                        style="color: {{ in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? 'red' : 'green' }};">
                                        {{ in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? '-' : '+' }}
                                        {{ $adminAmount->amount }}
                                    </td>
                                    {{-- Type Label --}}
                                    <td>
                                        @if ($adminAmount->type == 1)
                                            Rank Income
                                        @elseif($adminAmount->type == 2)
                                            Admin Fee
                                        @elseif($adminAmount->type == 3)
                                            TDS Fee
                                        @elseif($adminAmount->type == 4)
                                            <span class="text-danger">Transfer to Royalty</span>
                                        @elseif($adminAmount->type == 5)
                                            Unpaid Rank Amount
                                        @elseif($adminAmount->type == 7)
                                            Donation Amount
                                        @elseif($adminAmount->type == 8)
                                            <span class="text-danger">Transfer to Bonus</span>
                                        @elseif($adminAmount->type == 9)
                                            <span class="text-danger">Transfer to User</span>
                                        @elseif($adminAmount->type == 10)
                                            <span class="text-danger">Transfer to Rank income</span>
                                        @elseif($adminAmount->type == 11)
                                            <span class="text-success">Manually add to wallet</span>
                                        @elseif($adminAmount->type == 12)
                                            <span class="text-danger">Transfer to Privilege income</span>
                                        @elseif($adminAmount->type == 13)
                                            <span class="text-danger">Transfer to Board income</span>
                                        @elseif($adminAmount->type == 14)
                                            <span class="text-danger">Transfer to Executive income</span>
                                        @elseif($adminAmount->type == 15)
                                            <span class="text-danger">Transfer to Incentive income</span>
                                        @elseif($adminAmount->type == 16)
                                            <span class="text-success">Board Incentive</span>
                                        @elseif($adminAmount->type == 17)
                                            <span class="text-success">Executive Incentive</span>
                                        @elseif($adminAmount->type == 18)
                                            <span class="text-success">Privilege Incentive</span>
                                        @elseif($adminAmount->type == 19)
                                            <span class="text-success">Basic Rank Income</span>
                                        @elseif($adminAmount->type == 20)
                                            <span class="text-success">Unpaid Basic Rank Incentive</span>
                                        @elseif($adminAmount->type == 21)
                                            <span class="text-danger">Transfer Basic Rank Incentive</span>
                                        @elseif($adminAmount->type == 22)
                                            <span class="text-success">Vstore Incentive</span>
                                        @elseif($adminAmount->type == 23)
                                            <span class="text-success">TCS Income - My Vstore</span>
                                        @elseif($adminAmount->type == 24)
                                            <span class="text-success">GST Income - My Vstore</span>
                                        @else
                                            <span class="text-danger">Withdrawal</span>
                                        @endif
                                    </td>

                                    <td>{{ $adminAmount->created_at->format('d-m-Y') }}</td>

                                    {{-- Running Total Column --}}
                                    <td>{{ $runningTotal }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No details found.</td>
                                </tr>
                            @endforelse


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- DataTables Buttons JS -->
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                    extend: 'csv',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }, {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            const teamLink = $('.nav-link.gst_tcs_list');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
