@extends('Admin.admin_header')
@section('title', 'vishwastha | Basic Rank Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Basic Rank Incentive</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Basic Rank Incentive</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Rank</th>
                            <th>Amount</th>
                            <th>Received Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ranks as $index => $rank)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rank->user->name ?? 'N/A' }}<br>{{ $rank->user->connection ?? '' }}</td>
                                @if ($rank->rank_id == 1)
                                    <td>Transfered from child</td>
                                @else
                                    <td>{{ $rank->rank ? $rank->rank->name : 'N/A' }}</td>
                                @endif
                                <td>{{ number_format($rank->amount, 2) }}</td>
                                <td>{{ $rank->created_at ? $rank->created_at->format('d-m-Y') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total Rank Income: {{ number_format($totalAmount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
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
        $(document).ready(function() {
            const teamLink = $('.nav-link.income');
            const treeviewLink = $('.nav.nav-treeview.income');
            const mainLiLink = $('.nav-item.has-treeview.income');
            const directLink = $('.nav-link.basicrank');
            if (directLink.length) {
                directLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
