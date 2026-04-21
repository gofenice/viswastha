@extends('Delivey_partner.parter_header')
@section('title', 'Vishwastha | Offline Purchase List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Offline Purchase List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li> --}}
                            <li class="breadcrumb-item active">Offline Purchase List</li>
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
                            <th>Shop Name</th>
                            <th>Category & Percentage</th>
                            <th>Purchase Date</th>
                            <th>Product count</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseList as $index => $list)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $list->user->name ?? 'N/A' }} <br> {{ $list->user->connection }}</td>
                                <td>{{ $list->shop->name ?? 'N/A' }} <br> {{ $list->shop->phone }}</td>
                                <td>{{ $list->category->name ?? 'N/A' }} <br> {{ $list->category->percentage ?? 'N/A' }}%
                                </td>
                                <td>{{ $list->purchase_date }}</td>
                                <td>{{ $list->product_count }} </td>
                                <td>{{ $list->total }}</td>
                                {{-- <td>
                                    @if ($list->status == 1)
                                        <span class="badge badge-warning"> Wating For Shop Approvel </span>
                                    @elseif($list->status == 2)
                                        <span class="badge badge-success"> Shop Approved </span>
                                    @elseif($list->status == 3)
                                        <span class="badge badge-danger"> Shop Rejected </span>
                                    @elseif($list->status == 4)
                                        <span class="badge badge-success"> Vishwastha Rejected </span>
                                    @else
                                        <span class="badge badge-danger"> Vishwastha Approved </span>
                                    @endif
                                </td> --}}
                                <td>
                                    @if ($list->status == 2 && $list->admin_status == 1)
                                        <span class="badge badge-success"> Vishwastha Approved </span>
                                    @elseif($list->status == 2 && $list->admin_status == 2)
                                        <span class="badge badge-danger"> Vishwastha Rejected </span>
                                    @else
                                        @if ($list->status == 1)
                                            <span class="badge badge-warning"> Wating For Shop Approvel </span>
                                        @elseif($list->status == 2)
                                            <span class="badge badge-success"> Shop Approved </span>
                                        @elseif($list->status == 3)
                                            <span class="badge badge-danger"> Shop Rejected </span>
                                        @else
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($list->status == 2 && $list->admin_status != 1)
                                        <button class="btn btn-success btn-sm mt-2" data-toggle="modal"
                                            data-target="#modal-approvebill"
                                            onclick="approvebill({{ $list->id }},
                                            {{ $list->user->id }},
                                            {{ $list->category->percentage }},
                                            {{ $list->total }},
                                            {{ $list->shop_id }}
                                            )">
                                            Approve Amount
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-approvebill">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="approvebill-form" method="POST" action="{{ route('adminbillapprove') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Approve the Bill</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="billid">
                        <input type="hidden" name="amount" id="amount">
                        <input type="hidden" name="user_id" id="user_id">
                        <input type="hidden" name="percentage" id="percentage">
                        <input type="hidden" name="shop_id" id="shop_id">
                        <div class="form-group">
                            <p class="font-weight-bold text-primary">
                                Are you sure you want to approve this bill?
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('footer')
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
                text: "{{ session()->get('error') }}"
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
        $(document).ready(function() {
            const teamLink = $('.nav-link.repurchases');
            const treeviewLink = $('.nav.nav-treeview.repurchases');
            const mainLiLink = $('.nav-item.has-treeview.repurchases');
            const walletLink = $('.nav-link.ofpurlist');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        // Show trash money modal
        function approvebill(billid, userID, percentage, amount, shop_id) {
            $('#billid').val(billid);
            $('#user_id').val(userID);
            $('#percentage').val(percentage);
            $('#amount').val(amount);
            $('#shop_id').val(shop_id);
            $('#modal-trashMoney').modal('show');
        }
    </script>
@endsection
