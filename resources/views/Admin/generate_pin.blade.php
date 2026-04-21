@extends('Admin.admin_header')
@section('title', 'vishwastha   | Generate Pin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Generate Pin(s)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Generate Pin(s)</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Generate Pin(s)</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('add_pin') }}">
                @csrf
                <p data-user-url="{{ route('get_package') }}" id="package_amount"></p>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="package" class="col-sm-4 col-form-label">Package</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="package_id" required>
                                <option value="">--Choose Option---</option>
                                {{-- <option value="13">Premium Package</option> --}}
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="package_value" class="col-sm-4 col-form-label">Pin Value</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="package_value" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numOfPins" class="col-sm-4 col-form-label">No. Of Pins</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="numOfPins" name="numOfPins"
                                placeholder="No. Of Pins" required>
                            @error('numOfPins')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="totalCost" class="col-sm-4 col-form-label">Total Pin Cost</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="totalCost" name="totalCost"
                                placeholder="Total Pin Cost" readonly>
                            @error('totalCost')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="walletBalance" class="col-sm-4 col-form-label">Wallet Balance</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="walletBalance"
                                value="{{ isset($walletBalance) ? $walletBalance : '' }}" disabled>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Generate</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
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
        $(document).ready(function() {

            function updateTotalCost() {
                let numOfPins = parseFloat($('#numOfPins').val()) || 0;
                let packageValue = parseFloat($('#package_value').val()) || 0;
                let totalCost = numOfPins * packageValue;
                $('#totalCost').val(totalCost.toFixed(2));
            }

            $('#numOfPins').on('input', updateTotalCost);

            $('select[name="package_id"]').on('change', function() {
                var packageId = $(this).val();
                if (packageId) {
                    $.ajax({
                        url: $('#package_amount').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            packageId: packageId
                        }),
                        success: function(data) {
                            if (data.success) {
                                $('#package_value').val(data.amount);
                                updateTotalCost();
                            } else {
                                $('#package_value').val('');
                                $('#totalCost').val('');
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Package Amount Not Found",
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error fetching package details.",
                            });
                        }
                    });
                } else {
                    $('#totalCost').val('');
                    $('#package_value').val('');
                }
            });
        });

        $(document).ready(function() {
            const pinLink = $('.nav-link.pin');
            const treeviewLink = $('.nav.nav-treeview.pin');
            const mainLiLink = $('.nav-item.has-treeview.pin');
            const generatepinLink = $('.nav-link.generatepin');
            if (generatepinLink.length) {
                generatepinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
@endsection
