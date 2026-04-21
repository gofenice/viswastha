@extends('Admin.admin_header')
@section('title', 'vishwastha | Child Id')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Mother ID Transactions</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Mother ID Transactions</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped text-center" id="example1">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Child</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Transfer Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($childToMotherIncome_list as $index => $list)
                            <tr class="">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $list->child->name }}<br>{{ $list->child->connection }}</td>
                                <td>
                                    @if ($list->type == 1)
                                        Basic Level Income
                                    @elseif ($list->type == 2)
                                        Premium Level Income
                                    @elseif ($list->type == 3)
                                        Basic Referral Income
                                    @elseif ($list->type == 4)
                                        Premium Referral Income
                                    @elseif ($list->type == 5)
                                        Rank Income
                                    @elseif ($list->type == 6)
                                        Royalty Income
                                    @elseif ($list->type == 7)
                                        Special Incentive
                                    @elseif ($list->type == 8)
                                        Privilege Incentive
                                    @elseif ($list->type == 9)
                                        Executive Incentive
                                    @elseif ($list->type == 10)
                                        Board Incentive
                                    @elseif ($list->type == 11)
                                        Incentive
                                    @elseif ($list->type == 12)
                                        Basic Rank Incentive
                                    @else
                                        Before Implementation
                                    @endif
                                </td>
                                <td>{{ $list->amount }}</td>
                                <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
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
            const motherLink = $('.nav-link.mother');
            const motherviewLink = $('.nav.nav-treeview.mother');
            const mainLink = $('.nav-item.has-treeview.mother');
            const motherIdLink = $('.nav-link.mother-list');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
