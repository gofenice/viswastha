@extends('Admin.admin_header')
@section('title', 'vishwastha | Premium Rank List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Premium Rank List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Premium Rank List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Rank</th>
                            <th>Achive Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pre_rank_list as $index => $ranklist)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $ranklist->name }}<br> ( {{ $ranklist->connection }} )</td>
                                <td>{{ $ranklist->rank->rank_name }}</td>
                                <td>
                                    @if ($ranklist->rank_status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                    <br>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#editUserStatusModal"
                                        onclick="editProduct('{{ $ranklist->id }}', '{{ $ranklist->rank_status }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserStatusModal" tabindex="-1" aria-labelledby="editUserStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="editProductForm" method="POST" action="{{ route('edit_user_rankStatus') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserStatusModalLabel">Edit User</h5>
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

        // $(document).ready(function() {
        //     const rankLink = $('.nav-link.rankpreList');
        //     if (rankLink.length) {
        //         rankLink.addClass('active');
        //     }
        // });

        function editProduct(id, status) {
            var modal = $('#editUserStatusModal');

            modal.find('#packageId').val(id);
            modal.find('#status_active').prop('checked', status == 1);
            modal.find('#status_inactive').prop('checked', status == 0);
            modal.find('.error-message').text("");

            modal.modal('toggle');
        }

        $(document).ready(function() {
            const teamLink = $('.nav-link.preRank');
            const treeviewLink = $('.nav.nav-treeview.preRank');
            const mainLiLink = $('.nav-item.has-treeview.preRank');
            const directLink = $('.nav-link.rankpreList');
            if (directLink.length) {
                directLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
