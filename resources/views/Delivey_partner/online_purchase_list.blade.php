@extends('Delivey_partner.parter_header')
@section('title', 'Vishwastha | OnlinePurchase List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Online Purchase List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li> --}}
                            <li class="breadcrumb-item active">Online Purchase List</li>
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
                            <th>Percentage</th>
                            <th>Order ID</th>
                            <!-- <th>Franchisee</th> -->
                            <th>Purchase Date</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseList as $index => $list)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $list->user->name ?? 'N/A' }} <br> {{ $list->user->connection }}</td>
                                <td>{{ $list->percentage ?? 'N/A' }}</td>
                                <td>{{ $list->order_id }}</td>
                                <!-- <td>{{ $list->franchisee_code }}</td> -->
                                <td>{{ $list->created_at->format('d-m-Y') }}</td>
                                <td>{{ $list->amount }}</td>
                                {{-- <td>
                                    @if ($list->status)
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
                                </td> --}}
                                <td>
                                    @if ($list->is_approve == 0)
                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#modal-approvebill"
                                            onclick="approvebill({{ $list->id }},
                                            {{ $list->user->id }},
                                            {{ $list->percentage }},
                                            {{ $list->amount }},
                                            {{ $list->franchisee_code ?? 0 }})">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#modal-rejectbill"
                                            onclick="rejectbill({{ $list->id }})">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @elseif($list->is_approve == 1)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved</span>
                                    @elseif($list->is_approve == 2)
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Approve Bill Modal --}}
    <div class="modal fade" id="modal-approvebill">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="approvebill-form" method="POST" action="{{ route('commission.approve') }}">
                    @csrf
                    <div class="modal-header bg-success">
                        <h4 class="modal-title text-white">
                            <i class="fas fa-check-circle"></i> Approve Bill
                        </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="billid">
                        <input type="hidden" name="amount" id="amount">
                        <input type="hidden" name="user_id" id="user_id">
                        <input type="hidden" name="percentage" id="percentage">
                        <input type="hidden" name="franchisee_code" id="franchisee_code">

                        <div class="text-center py-3">
                            <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                            <h5 class="font-weight-bold text-dark">
                                Are you sure you want to approve this bill?
                            </h5>
                            <p class="text-muted">
                                This action will process the commission and update the wallet.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Approve Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Bill Modal --}}
    <div class="modal fade" id="modal-rejectbill">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="rejectbill-form" method="POST" action="{{ route('commission.reject') }}">
                    @csrf
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title text-white">
                            <i class="fas fa-times-circle"></i> Reject Bill
                        </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="reject_billid">

                        <div class="text-center py-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5 class="font-weight-bold text-dark">
                                Are you sure you want to reject this bill?
                            </h5>
                            <p class="text-muted">
                                This action will mark the bill as rejected and no commission will be processed.
                            </p>
                        </div>

                        <!-- <div class="form-group">
                            <label for="reject_reason">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reject_reason" class="form-control" rows="3"
                                placeholder="Please provide a reason for rejection..." required></textarea>
                        </div> -->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Reject Bill
                        </button>
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
            const walletLink = $('.nav-link.onpurlist');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        // Show approve bill modal
        function approvebill(billid, userID, percentage, amount, franchisee_code) {
            $('#billid').val(billid);
            $('#user_id').val(userID);
            $('#percentage').val(percentage);
            $('#amount').val(amount);
            $('#franchisee_code').val(franchisee_code);
        }

        // Show reject bill modal
        function rejectbill(billid) {
            $('#reject_billid').val(billid);
            $('#reject_reason').val('');
        }
    </script>
@endsection
