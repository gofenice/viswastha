@extends('Admin.admin_header')
@section('title', 'vishwastha | Repurchase Income')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> Repurchase Income</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Repurchase Income List</li>
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
                            <th>Purchased User</th>
                            <th>User</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Level</th>
                            <th>Received Date</th>
                            {{-- <th>Amount Type</th> --}}
                            {{-- <th>Purchase Type</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->productOrderedUser->name ?? 'N/A' }}<br>{{ $user->productOrderedUser->connection ?? '' }}
                                </td>
                                <td>{{ $user->user->name ?? 'N/A' }}<br>{{ $user->user->connection ?? '' }}</td>
                                <td><b>#{{ $user->order_id }}</b></td>
                                <td>{{ number_format($user->amount, 2) }}</td>
                                <td>Level{{ $user->sponsor_level ?? 'Before Implement' }}</td>
                                <td>{{ $user->created_at ? $user->created_at->format('d-m-Y') : 'N/A' }}</td>
                                {{-- <td>{{ $user->amount_type }}</td> --}}
                                {{-- <td>
                                    @if ($user->status == 1)
                                        Online purchase
                                    @else
                                        Offline purchase
                                    @endif
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7">Total Repurchase Income: {{ number_format($totalAmount, 2) }}</th>
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
            const teamLink = $('.nav-link.myvstore');
            const treeviewLink = $('.nav.nav-treeview.myvstore');
            const mainLiLink = $('.nav-item.has-treeview.myvstore');
            const directLink = $('.nav-link.repurchaseli');
            if (directLink.length) {
                directLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
