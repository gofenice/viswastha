@extends('Admin.admin_header')
@section('title', 'vishwastha | Shop receipt')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Receipt </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Receipt </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Account Holder Name &<br> Transaction ID</th>
                            <th>Amount</th>
                            <th>Date of Send</th>
                            <th>Image</th>
                            <th>Status</th>
                            @if (Auth::user()->role === 'superadmin')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($shopReceipt_list)
                            @foreach ($shopReceipt_list as $key => $reciept)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $reciept->user->name ?? '' }}<br>{{ $reciept->user->connection ?? '' }}</td>
                                    <td>{{ $reciept->acc_holder_name }}<br>{{ $reciept->transaction_id }}</td>
                                    <td>{{ number_format($reciept->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reciept->date_of_send)->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($reciept->image)
                                            <a href="{{ asset($reciept->image) }}" target="_blank">
                                                <img src="{{ asset($reciept->image) }}" alt="Receipt Image"
                                                    style="width: 50px; height: 50px;">
                                            </a>
                                        @else
                                            <a href="javascript:void(0)">No Image</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reciept->status === 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($reciept->status === 1)
                                            <span class="badge badge-success">Completed</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                    @if (Auth::user()->role === 'superadmin')
                                        <td>
                                            @if ($reciept->status === 1)
                                                <span class="badge badge-success">Confirmed</span>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#editModal" data-id="{{ $reciept->id }}">
                                                    Approve
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">No records found.</td>
                            </tr>
                        @endif


                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('shopReceiptApprove') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Approve Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="receiptId" id="receiptId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="1">Approved</option>
                                <option value="2">Rejected</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="rejectNoteContainer">
                            <label for="reject_note">Reason for Rejection</label>
                            <textarea name="reject_note" id="reject_note" class="form-control" rows="3"
                                placeholder="Enter reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
    <script>
        $(document).ready(function() {
            $('#status').on('change', function() {
                if ($(this).val() == "2") {
                    $('#rejectNoteContainer').removeClass('d-none'); // Show textarea
                } else {
                    $('#rejectNoteContainer').addClass('d-none'); // Hide textarea
                    $('#reject_note').val(''); // Clear textarea
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var receiptId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#receiptId').val(receiptId);
            });
        });

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
            const walletLink = $('.nav-link.shreli');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#editModal form").addEventListener("submit", function(e) {
                let submitButton = this.querySelector("button[type='submit']");
                submitButton.disabled = true;
                submitButton.innerHTML = "Processing...";
            });
        });
    </script>
    {{-- <script>
        function disableSubmitButton() {
            document.getElementById('submitButton').disabled = true;
        }
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("user-pin-wallet");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                // Disable button to prevent multiple clicks
                submitBtn.disabled = true;
                // Change button text to loading
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
            });
        });
    </script>
@endsection
