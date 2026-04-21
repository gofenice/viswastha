@extends('Admin.admin_header')
@section('title', 'vishwastha  | Statement')
@section('content')
    <style>
        .bg-light-danger {
            background-color: #f8d7da !important;
            /* Light red background */
        }
        .bg-custom {
            background-color: #007bff !important;
        }
        .inner {
            color: #fff !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Statement</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">View Statement</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="container">
            <div class="row d-flex">
                <div class="col-md-4" style="margin: 0 auto;">
                    <form action="{{ route('statement') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" name="user_code" class="form-control" placeholder="Enter User ID"
                                value="{{ old('user_code') }}" required>
                                <button type="submit" class="btn btn-primary ml-3">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" style="justify-content: center">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{$totalRoyaltyIncome}}</h3>

                            <p>Royalty Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$user->name}}</h3>

                            <p>{{$user->connection}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{$totallevelpremium}}</h3>

                            <p>Premium Level income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{$totalrankincome}}</h3>
                            <p>Rank Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                            <h3>{{$user->total_income}}</h3>

                            <p>Current Wallet Amount</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{$totalrefferalincomepremium}}</h3>

                            <p>Premium Sponsor Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totallevelbasic }}</h3>

                            <p>Basic Level Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $totalrefferalincomebasic }}</h3>

                            <p>Basic Sponsor Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹ {{ $BonusWallet }}</h3>

                            <p>Bonus Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>Amount</th>
                            <th>Running Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $runningTotal = 0; // Initialize running total
                        @endphp
                        @forelse($bankStatement->reverse() as $key => $entry)
                            @php
                                // Deduct withdrawal amounts from running total
                                $runningTotal += $entry->type === 'Withdrawal' ? -$entry->amount : $entry->amount;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $entry->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    {{ $entry->type }}
                                    @if ($entry->type === 'Rank Income' && $entry->rank)
                                        ({{ $entry->rank->name }})
                                    @endif
                                </td>
                                @if ($entry->type === 'Rank Income')
                                <td style="color: #6acbf1">vishwastha </td>
                                @elseif ($entry->type === 'Royalty Wallet')
                                <td style="color: #6acbf1">vishwastha </td>
                                @else
                                <td>
                                    {{ $entry->user ? $entry->user->name : 'N/A' }}<br>
                                    {{ $entry->user ? $entry->user->connection : 'N/A' }}
                                </td>
                                @endif
                                <td style="color: {{ in_array($entry->type, ['Withdrawal', 'Payment to Mother']) ? 'red' : 'green' }};">
                                    {{ in_array($entry->type, ['Withdrawal', 'Payment to Mother']) ? '-' : '+' }}{{ number_format(abs($entry->amount), 2) }}
                                </td>
                                <td>{{ number_format($runningTotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('footer')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable("#example1")) {
        $("#example1").DataTable().destroy(); // Destroy previous instance
    }

    $("#example1").DataTable({
        dom: 'Bfrtip', // Enables buttons
        buttons: [
            {
                extend: 'print',
                text: 'Print Statement',
                className: 'btn btn-primary',
                title: 'User Statement'
            }
        ]
    });
});

</script>
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session()->get('error') }}",
            });
        </script>
    @endif
    <script>
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });

     
    </script>
@endsection
