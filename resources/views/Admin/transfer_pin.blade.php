@extends('Admin.admin_header')
@section('title', 'vishwastha   | Transfer Pin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transfer Pin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Transfer Pin</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Horizontal Form -->
    <div class="card card-info col-md-8" style="margin: 0 auto;">
        <div class="card-header">
            <h3 class="card-title">Transfer Pin</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal">
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Package</label>
                    <div class="col-sm-8">
                        <select class="form-control">
                            <option value="1">Package-1180 (200BV) with GST(18%)</option>
                            <option value="2">Package-11800 (1500BV) with GST(18%)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">No. Of Pins Available</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputPassword3" value="2" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">To User Id</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputPassword3">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">To User Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputPassword3">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">No. Of Pins To Transfer</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputPassword3">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">Transfer</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
    <!-- /.card -->
</div>
@endsection