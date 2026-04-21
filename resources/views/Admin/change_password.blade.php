@extends('Admin.admin_header')
@section('title', 'vishwastha   | Change Password')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Horizontal Form -->
    <div class="card card-info col-md-8" style="margin: 0 auto;">
        <div class="card-header">
            <h3 class="card-title">Change Password</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" method="POST" action="{{ route('change_password_process') }}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">User Id</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="inputEmail3" value="HRSH125101" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">Old Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword3" name="old_password" placeholder="Old Password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">New Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="New Password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-4 col-form-label">Confirm New Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="inputPassword3" name="password_confirmation" placeholder="Confirm New Password">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">Update Password</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
    <!-- /.card -->
</div>
@endsection