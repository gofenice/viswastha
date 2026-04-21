@extends('Admin.admin_header')
@section('title', 'VISHWASTHA  | Category')
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
                        <h1>Category</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('adminhome')}}">Home</a></li>
                            <li class="breadcrumb-item active">Category</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Add Category</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ route('add_category') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="categoryName" class="col-sm-4 col-form-label">Name of Category</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="categoryName" name="categoryName"
                                placeholder="Name of Category" required>
                                @error('categoryName')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="percentage" class="col-sm-4 col-form-label">Percentage</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="percentage" name="percentage"
                                placeholder="Percentage of Category" required>
                            @error('percentage')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8 radio">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check ml-2">
                                <input class="form-check-input" type="radio" name="status" value="0">
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Add Category</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
        <div class="card mt-3 packagelist col-md-11">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Percentage</th>
                            <th>Active/Incative</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (isset($categoryList) && $categoryList->isNotEmpty())
                        @foreach ($categoryList as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->percentage }}%</td>
                                <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection
@section('footer')
    @if(session()->has('success'))
    <script>Swal.fire({
        position: "top-center",
        icon: "success",
        title: "{{ session()->get('success') }}",
        showConfirmButton: false,
        timer: 1500
    });</script> 
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
@endsection
