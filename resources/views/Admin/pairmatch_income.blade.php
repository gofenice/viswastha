@extends('Admin.admin_header')
@section('title', 'vishwastha   | Level Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Level Income</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Level Income List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User 1 Name</th>
                            <th>User 2 Name</th>
                            <th>Package</th>
                            <th>Commission Credited</th>
                            <th>Remarks</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pairMatches as $index => $pairMatch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pairMatch->pair_user_1->name }}</td>
                                <td>{{ $pairMatch->pair_user_2->name }}</td>
                                <td>{{ $pairMatch->package->name }}</td>
                                <td>{{ !$pairMatch->pair_income ? 0 : ($pairMatch->pair_income->user_id == 1 ? 0 : $pairMatch->pair_income->income) }}
                                </td>
                                <td>
                                    @if (!$pairMatch->pair_income)
                                        <span class="text-danger">No commission credited. Exceeded daily limit.</span>
                                    @elseif ($pairMatch->pair_income->user_id == 1)
                                        <span class="text-warning">Commission amount {{ $pairMatch->pair_income->income }}
                                            credited for admin.</span>
                                    @else
                                        <span class="text-success">Commission amount {{ $pairMatch->pair_income->income }}
                                            credited .</span>
                                    @endif
                                </td>
                                <td>{{ $pairMatch->pair_match_income_date }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{$totalAmount}}</th>
                        <th></th>
                        <th></th>
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
            const matchLink = $('.nav-link.match');
            if (matchLink.length) {
                matchLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
