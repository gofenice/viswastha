@extends('Admin.admin_header')
@section('title', 'vishwastha   | Company Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Company Pair Match Income</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Company Income </li>
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
                            <th>Sponsored By</th>
                            <th>Pairs</th>
                            <th>Package</th>
                            <th>Commission Credited</th>
                            <th>Credited Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pairMatches as $index => $pairMatch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{$pairMatch->sponsor_name->name }}</td>
                                <td>1. {{ $pairMatch->pair_user_1->name }}<br>
                                    2. {{ $pairMatch->pair_user_2->name }}</td>
                                <td>{{ $pairMatch->package->name }}</td>
                                <td>{{$pairMatch->pair_match_income }}</td>
                                <td>{{ \Carbon\Carbon::parse($pairMatch->pair_match_income_date)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
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
            const matchLink = $('.nav-link.company');
            if (matchLink.length) {
                matchLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
