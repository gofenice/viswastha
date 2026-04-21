@extends('Admin.admin_header')
@section('title', 'vishwastha  | User Product List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Product List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#!">Product list</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Users Product List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Product</th>
                                            <th>Pacakge</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userProductList as $key => $userProduct)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $userProduct->user->name }}<br>{{ $userProduct->user->connection }}
                                                </td>
                                                <td>{{ $userProduct->product->product_name }}</td>
                                                <td>{{ $userProduct->package->name }}</td>
                                                <td>{{ $userProduct->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    @if ($userProduct->status === '0')
                                                    <span class="badge badge-warning">Pending</span>
                                                    @elseif ($userProduct->status === '1')
                                                    <span class="badge badge-primary">Confirmed</span>
                                                    @else
                                                        <span class="badge badge-success">Completed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('approveproduct') }}">
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
                                <option value="1">Approved</option>
                                <option value="4">Rejected</option>
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
        $(document).ready(function() {
            const pinLink = $('.nav-link.product');
            const treeviewLink = $('.nav.nav-treeview.product');
            const mainLiLink = $('.nav-item.has-treeview.product');
            const viewpinLink = $('.nav-link.urpddy');
            if (viewpinLink.length) {
                viewpinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

    </script>
@endsection
