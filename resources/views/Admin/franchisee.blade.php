@extends('Admin.admin_header')
@section('title', 'VISHWASTHA | Category')
@section('content')
    <style>
        .radio {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .packagelist {
            margin: 0 auto;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Franchisee</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Franchisee</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Add Franchisee</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="getUserId" class="form-horizontal" method="POST" action="{{ route('add_franchisee') }}"
                data-user-url="{{ route('get_user_name') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="district" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="district" id="district">
                                <option>-- Choose option --</option>
                                @foreach ($district as $dist)
                                    <option value="{{ $dist->district_id }}"> {{ $dist->district_name }}</option>
                                @endforeach
                            </select>
                            @error('categoryName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="localbodytype" class="col-sm-4 col-form-label">Local body type</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="localbodytype" id="localbodytype">
                                <option>-- Choose option --</option>
                                @foreach ($localbodytype as $type)
                                    <option value="{{ $type->id }}"> {{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="localbody" class="col-sm-4 col-form-label">Local body</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="localbody" id="localbody">
                                <option>-- Choose option --</option>
                            </select>
                            @error('categoryName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="percentage" class="col-sm-4 col-form-label">User Id</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="userId" required name="userId"
                                placeholder="Enter User ID">
                            @error('percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="percentage" class="col-sm-4 col-form-label">User name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="userName" name="userName" placeholder="Name"
                                readonly>
                            @error('percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Add User</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
        <div class="card mt-3 packagelist col-md-11">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>SL.NO</th>
                            <th>District</th>
                            <th>Local body Type</th>
                            <th>Local body</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($franchisee as $index => $franchis)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $franchis->localBody->district->district_name }}</td>
                                <td>{{ $franchis->localBodyType->name }}</td>
                                <td>{{ $franchis->localBody->name }}</td>
                                <td>{{ $franchis->user->name }} <br> {{ $franchis->user->connection }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
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
    </script>
    <script>
        function fetchLocalBodies() {

            let usedLocalBodies = @json($usedLocalBodies ?? []);
            let districtId = $('#district').val();
            let typeId = $('#localbodytype').val();

            if (districtId && typeId) {
                $.ajax({
                    url: '{{ route('get_localbodies') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        district_id: districtId,
                        type_id: typeId
                    },
                    success: function(response) {
                        $('#localbody').empty().append('<option value="">-- Choose Local Body --</option>');
                        $.each(response, function(key, localbody) {
                            if (!usedLocalBodies.includes(localbody.id)) {
                                $('#localbody').append(
                                    `<option value="${localbody.id}">${localbody.name}</option>`);
                            }
                        });
                    }
                });
            }
        }

        $('#district, #localbodytype').change(fetchLocalBodies);

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
    </script>
    <script>
        $(document).ready(function() {
            const teamLink = $('.nav-link.franc');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
@endsection
