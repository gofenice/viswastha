@extends('Admin.admin_header')
@section('title', 'vishwastha   | Level Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Premium Level Incentive</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Premium Level Incentive List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Amount From</th>
                            @if(Auth::check() && (Auth::user()->role === 'superadmin'))
                            <th>Amount To</th>
                            @endif
                            <th>Level</th>
                            <th>Package</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($levelincomes as $index => $levelincome)
                            <tr>
                                <td>{{ $index + 1 }} </td>
                                <td>{{ $levelincome->user->name }}<br>{{ $levelincome->user->connection }}</td>
                                @if(Auth::check() && (Auth::user()->role === 'superadmin'))
                                <td>{{ $levelincome->sponsor->name }}<br>{{ $levelincome->sponsor->connection }}</td>
                                @endif
                                <td>level{{ $levelincome->sponsor_level }}</td>  
                                <td>{{ $levelincome->package->name }}</td>
                                <td>{{ $levelincome->amount }}</td>
                                
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                     </tfoot>
                    <tr>
                        <th colspan="6">Total Premium Level Income: {{ $totalAmount }}</th>
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
