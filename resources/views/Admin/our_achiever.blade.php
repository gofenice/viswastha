@extends('Admin.admin_header')
@section('title', 'vishwastha   | Our Achiever')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Our Achiever</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Our Achiever</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="card card-info col-md-8" style="margin: 0 auto;">
        <div class="card-header">
            <h3 class="card-title">Our Achiever</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{route('our_achiever_list')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <label for="date" class="col-sm-4 col-form-label">Date:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ old('date', request('date', now()->toDateString())) }}">
                        @error('date')<span class="text-danger">{{ $message }}</span>@enderror

                    </div>
                </div>
                <div class="form-group row">
                    <label for="package" class="col-sm-4 col-form-label">Package</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="package" name="package">
                        <option value="13">
                            Premium Package
                        </option>
                        {{--
                            @if(isset($packages) && $packages->isNotEmpty())
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ request('package') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            @endif
                            --}}
                        </select>
                        @error('package')<span class="text-danger">{{ $message }}</span>@enderror
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
    
    <div class="card mt-3">
        <div class="card-body">
            <h3>Achievers List</h3>
            <table class="table table-bordered nested-table" id="example1">
                <thead class="bg-info">
                    <tr>
                        <th>SL NO</th>
                        <th>Name</th>
                        <th>User Id</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($users) && $users->isNotEmpty())
                    @foreach($users as $index => $user)
                        @php
                            $profileImage = $user->user_image 
                                ? asset($user->user_image)
                                : '/assets/dist/img/images.jpg';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }} </td>
                            <td>{{ $user->connection }}</td>
                            <td>
                                <img src="{{ $profileImage }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;" class="img-circle">
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">No records found.</td>
                    </tr>
                @endif
                </tbody>
            </table>
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
</script>
<script>
    $(document).ready(function() {
        const teamLink = $('.nav-link.achiever');
        if (teamLink.length) {
            teamLink.addClass('active');
        }
    });
</script>
@endsection