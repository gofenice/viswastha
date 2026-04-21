@extends('Admin.admin_header')
@section('title', 'vishwastha  | Add Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Bank account List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Bank List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="card mt-3 recieptList col-md-11 mx-auto">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Bank & IFSC Code &<br>Branch</th>
                            <th>Account No</th>
                            <th>Account Holder Name</th>
                            <th>Documents</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banklists as $key => $banklist)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $banklist->user->name }}<br>{{ $banklist->user->connection }}</td>
                                <td>{{ $banklist->bank_name }}<br>{{ $banklist->ifs_code }}<br>{{ $banklist->branch_name }}
                                </td>
                                <td>{{ $banklist->account_number }}</td>
                                <td>{{ $banklist->account_holder_name }}</td>
                                <td>
                                    @if ($banklist->bank_passbook_image)
                                        <a href="{{ asset($banklist->bank_passbook_image) }}" target="_blank">
                                            <img src="{{ asset($banklist->bank_passbook_image) }}" alt="Receipt Image"
                                                style="width: 50px; height: 50px;">
                                        </a>
                                        <a href="{{ asset($banklist->pancard_image) }}" target="_blank">
                                            <img src="{{ asset($banklist->pancard_image) }}" alt="Receipt Image"
                                                style="width: 50px; height: 50px;">
                                        </a>
                                    @else
                                        Not Upload
                                    @endif
                                </td>
                                <td>
                                    @if ($banklist->status === 2)
                                        <span class="badge badge-success">Confirmed</span>
                                    @elseif ($banklist->status === 0)
                                        <span class="badge badge-danger">Rejected</span><br>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal"
                                            data-id="{{ $banklist->id }}">
                                            Confirm
                                        </button>
                                        <li>Reject Reason :<br>
                                            <textarea class="form-control text-danger" readonly>{{ $banklist->note }}</textarea>
                                        </li>
                                    @else
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal"
                                            data-id="{{ $banklist->id }}">
                                            Confirm
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('approvebank') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Approve Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="bankdtId" id="bankdtId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="2">Approved</option>
                                <option value="0">Rejected</option>
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

        document.addEventListener('DOMContentLoaded', function() {
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var bankdtId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#bankdtId').val(bankdtId);
            });
        });

        $(document).ready(function() {
            const teamLink = $('.nav-link.mother');
            const treeviewLink = $('.nav.nav-treeview.mother');
            const mainLiLink = $('.nav-item.has-treeview.mother');
            const walletLink = $('.nav-link.bank-list');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        $(document).ready(function() {
            $('#status').on('change', function() {
                if ($(this).val() == "0") {
                    $('#rejectNoteContainer').removeClass('d-none'); // Show textarea
                } else {
                    $('#rejectNoteContainer').addClass('d-none'); // Hide textarea
                    $('#reject_note').val(''); // Clear textarea
                }
            });
        });
    </script>
@endsection
