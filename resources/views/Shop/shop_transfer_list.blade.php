@extends('Shop.shop_header')
@section('title', 'vishwastha | Shop Transfer')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shop Transfer </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                            <li class="breadcrumb-item active">Shop Transfer</li>
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
                            <th>Order ID</th>
                            <th>Receipt No</th>
                            <th>Total Amount</th>
                            <th>Vstore Amount</th>
                            <th>Date</th>
                            {{-- <th>Purchase Type</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfer_list as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->productOrderedUser->name ?? 'N/A' }}<br>{{ $item->productOrderedUser->connection ?? '' }}
                                </td>
                                <td><b>#{{ $item->order_id }}</b></td>
                                <td>{{ $item->orderBill->product_count ?? 0 }}</td>
                                <td>{{ $item->orderBill->total ?? 0 }}</td>
                                <td>{{ number_format($item->commission_amount, 2) }}</td>
                                <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : 'N/A' }}</td>
                                {{-- <td>
                                    @if ($item->status == 0)
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
                            <th colspan="6">Total Repurchase Amount:
                                {{ number_format($transfer_list->sum('commission_amount'), 2) }}</th>
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
