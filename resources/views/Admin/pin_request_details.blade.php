@extends('Admin.admin_header')
@section('title', 'vishwastha   | Pin Request')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pin Request Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pin Request Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Horizontal Form -->
    <div class="card card-info col-md-8" style="margin: 0 auto;">
        <div class="card-header">
            <h3 class="card-title">Pin Request Details</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal">
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">From Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="inputPassword3" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">To Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="inputPassword3" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-8">
                        <select class="form-control">
                            <option value="0">Pending</option>
                            <option value="1">Allotted</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">View Details</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
    <!-- /.card -->
</div>
@endsection