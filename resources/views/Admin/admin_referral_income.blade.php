@extends('Admin.admin_header')
@section('title', 'vishwastha  | Referral Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Referral Incentive</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Referral List </li>
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
                            <th>Sponsor</th>
                            <th>Sponsored User</th>
                            <th>Package</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponsors as $index => $sponsor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sponsor->sponsor->name }}<br>{{ $sponsor->sponsor->connection }}</td>
                                <td>{{ $sponsor->user->name }}<br>{{ $sponsor->user->connection }}</td>
                                <td>{{ $sponsor->package->name }}</td>
                                <td>{{ $sponsor->income }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    </tfoot>
                    <tr>
                        <th colspan="5">Total Referral Income: {{ $totalAmount }}</th>
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
            const directLink = $('.nav-link.referal');
            if (directLink.length) {
                directLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
