@extends('Admin.admin_header')
@section('title', 'vishwastha  | Admin Wallet')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Bookings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">order List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Product</th>
                                            <th>Pacakge</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookingList as $key => $allorder)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $allorder->user->name }}<br>{{ $allorder->user->connection }}</td>
                                                <td>{{ $allorder->product->product_name }}</td>
                                                <td>{{ $allorder->package->name }}</td>
                                                <td>
                                                    @if ($allorder->status === '0')
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#editModal" data-id="{{ $allorder->id }}">
                                                            Confirm
                                                        </button>
                                                    @elseif ($allorder->status === '1')
                                                        <span class="badge badge-warning">Confirmed</span><br>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#statusModal" data-id="{{ $allorder->id }}">
                                                            Change Status
                                                        </button>
                                                    @else
                                                        <span class="badge badge-success">Completed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('approvepackageAdmin') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Confirm the Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="productListId" id="productListId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="1">Confirmed</option>
                            </select>
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
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('Adminstatus') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Change Product status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="orderId" id="orderId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="2">Completed</option>
                            </select>
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
            const teamLink = $('.nav-link.order');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
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
                var productListId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#productListId').val(productListId);
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            $('#statusModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var orderId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#orderId').val(orderId);
            });
        });
    </script>
@endsection
