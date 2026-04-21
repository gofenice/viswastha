@extends('Admin.admin_header')
@section('title', 'vishwastha  | Board Users')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Board users</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Board</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-4 mx-auto">
            <div class="card-header">
                <h3 class="card-title">Add Board Users</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('add_board_user') }}" data-user-url="{{ route('get_user_name') }}"
                id="getUserId">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="userId" class="col-sm-4 col-form-label">User ID</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="userId" required name="userId"
                                placeholder="Enter User ID">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="userName" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="userName" name="userName" placeholder="Name"
                                readonly>
                            @error('userName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Add</button>
                </div>
            </form>
        </div>

        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">User List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>User</th>
                            <th>Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boardusers as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->user->name ?? 'N/A' }}<br>{{ $user->user->connection ?? '' }}</td>
                                <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if ($user->status == 0)
                                    <span class="badge badge-danger">Inactive</span> 
                                    @else
                                    <span class="badge badge-success">Active</span>
                                    @endif
                                    <br>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProductModal" onclick="editProduct('{{ $user->id }}', '{{ $user->status }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Board users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="editProductForm" method="POST" action="{{ route('edit_board_user') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <div class="form-group col-md-12">
                                <input type="hidden" class="form-control" id="packageId" name="id">
                                <label>Status</label><br>
                                <input type="radio" name="status" id="status_active" value="1"> Active
                                <input type="radio" name="status" id="status_inactive" value="0"> Inactive
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
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
                text: "{{ session()->get('error') }}",
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
            const teamLink = $('.nav-link.board');
            const treeviewLink = $('.nav.nav-treeview.board');
            const mainLiLink = $('.nav-item.has-treeview.board');
            const walletLink = $('.nav-link.boarduser');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });

        $(document).ready(function() {
            $('#userId').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: $('#getUserId').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token for security
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            userId: userId
                        }),
                        success: function(response) {
                            if (response.name) {
                                $('#userName').val(response.name);
                            }
                        },
                        error: function() {
                            $('#userName').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User Not Found",
                            });
                        }
                    });
                } else {
                    $('#userName').val(''); // Clear the field if userId is empty
                }
            });
        });

        function editProduct(id,status) {
            var modal = $('#editProductModal');

            modal.find('#packageId').val(id);
            modal.find('#status_active').prop('checked', status == 1);
            modal.find('#status_inactive').prop('checked', status == 0);
            modal.find('.error-message').text("");

            modal.modal('toggle');
        }
    </script>
@endsection
