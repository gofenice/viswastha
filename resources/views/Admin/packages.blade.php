@extends('Admin.admin_header')
@section('title', 'vishwastha | Package')
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
                        <h1>Package</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Package</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Add Package</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ route('add_package') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="packageName" class="col-sm-4 col-form-label">Name of Package</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="packageName" name="packageName"
                                placeholder="Name of Package" required>
                            @error('packageName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageAmount" class="col-sm-4 col-form-label">Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="packageAmount" name="packageAmount"
                                placeholder="Amount of Package" required>
                            @error('packageAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="binary_commission" class="col-sm-4 col-form-label">Binary Commission (BV)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" id="binary_commission" name="binary_commission"
                                placeholder="BV points per activation (e.g. 200)" required>
                            <small class="text-muted">Points added to parent's leg BSV/PSV per activation</small>
                            @error('binary_commission')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sponsor_commission" class="col-sm-4 col-form-label">Sponsor Commission (₹)</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" id="sponsor_commission" name="sponsor_commission"
                                placeholder="₹ credited to direct sponsor (e.g. 50)" required>
                            <small class="text-muted">Fixed ₹ amount credited to direct sponsor on activation</small>
                            @error('sponsor_commission')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="daily_pair_cap" class="col-sm-4 col-form-label">Daily Pair Cap</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="1" class="form-control" id="daily_pair_cap" name="daily_pair_cap"
                                placeholder="Max pairs per day (e.g. 25)" required>
                            <small class="text-muted">Maximum pair matches allowed per day for this package</small>
                            @error('daily_pair_cap')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageCategory" class="col-sm-4 col-form-label">Category</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="packageCategory" name="packageCategory" required>
                                <option value="basic_package">Basic</option>
                                <option value="premium_package">Premium</option>
                            </select>
                            @error('packageCategory')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="packageCat" class="col-sm-4 col-form-label">Type</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="packageCat" name="packageCat" required>
                                <option value="0">Basic</option>
                                <option value="1">Premium</option>
                            </select>
                            @error('packageCat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
                    <button type="submit" class="btn btn-info float-right">Add Package</button>
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
                            <th>Package Name</th>
                            <th>Amount</th>
                            <th>Binary BV</th>
                            <th>Sponsor ₹</th>
                            <th>Daily Cap</th>
                            <th>Category</th>
                            <th>Active/Inactive</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($packages) && $packages->isNotEmpty())
                            @foreach ($packages as $package)
                                <tr>
                                    <td>{{ $package->name }}</td>
                                    <td>₹{{ number_format($package->amount, 2) }}</td>
                                    <td>{{ number_format($package->binary_commission, 2) }}</td>
                                    <td>₹{{ number_format($package->sponsor_commission, 2) }}</td>
                                    <td>{{ $package->daily_pair_cap }}</td>
                                    <td>
                                        @if ($package->package_code == 'basic_package')
                                            Basic Package
                                        @else
                                            Premium Package
                                        @endif
                                    </td>
                                    <td>{{ $package->status ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        @if ($package->package_cat == '0')
                                            Basic
                                        @else
                                            Premium
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                            data-target="#editPackageModal"
                                            onclick="editPackage('{{ $package->id }}', '{{ $package->name }}', '{{ $package->amount }}', '{{ $package->status }}', '{{ $package->binary_commission }}', '{{ $package->sponsor_commission }}', '{{ $package->daily_pair_cap }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        {{-- <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deletePackageModal"
                                        onclick="confirmDelete('{{ $package->id }}', '{{ $package->name }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>

        {{-- Edit package modal --}}
        <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editPackageForm" method="POST" action="{{ route('edit_package') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="packageId" name="id">
                                <label for="editName">Package Name</label>
                                <input type="text" class="form-control" id="editName" name="name" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="editAmount">Amount (₹)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="editAmount" name="amount" required>
                            </div>
                            <div class="form-group">
                                <label for="editBinaryCommission">Binary Commission (BV)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="editBinaryCommission" name="binary_commission" required>
                                <small class="text-muted">BV points added to parent's leg per activation</small>
                            </div>
                            <div class="form-group">
                                <label for="editSponsorCommission">Sponsor Commission (₹)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="editSponsorCommission" name="sponsor_commission" required>
                                <small class="text-muted">₹ credited to direct sponsor on activation</small>
                            </div>
                            <div class="form-group">
                                <label for="editDailyPairCap">Daily Pair Cap</label>
                                <input type="number" min="0" step="1" class="form-control" id="editDailyPairCap" name="daily_pair_cap" required>
                                <small class="text-muted">Maximum pair matches allowed per day</small>
                            </div>
                            <div class="form-group">
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
        {{-- Delete package modal --}}
        <div class="modal fade" id="deletePackageModal" tabindex="-1" role="dialog"
            aria-labelledby="deletePackageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePackageModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteMessage">
                            Are you sure you want to delete this package?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form id="deletePackageForm" method="POST" action="{{ route('delete_package') }}">
                            @csrf
                            <input type="hidden" class="form-control" id="packageId" name="id">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
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
        function editPackage(id, name, amount, status, binaryCommission, sponsorCommission, dailyPairCap) {
            $('#editPackageModal #packageId').val(id);
            $('#editPackageModal #editName').val(name);
            $('#editPackageModal #editAmount').val(amount);
            $('#editPackageModal #editBinaryCommission').val(binaryCommission);
            $('#editPackageModal #editSponsorCommission').val(sponsorCommission);
            $('#editPackageModal #editDailyPairCap').val(dailyPairCap);
            if (status == 1) {
                $('#editPackageModal #status_active').prop('checked', true);
            } else {
                $('#editPackageModal #status_inactive').prop('checked', true);
            }
            $('#editPackageModal').modal('toggle');
        }

        function confirmDelete(id, name) {
            var packageId = $('#deletePackageModal').find('#packageId');
            packageId.val(id);
            $('#deletePackageModal').modal('toggle');
        }
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
