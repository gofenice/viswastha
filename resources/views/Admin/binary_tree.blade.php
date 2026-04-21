@extends('Admin.admin_header')
@section('title', 'VISHWASTHA  | My Team')
@section('content')
<link rel="stylesheet" href="{{asset('assets/dist/css/viewpages/binary_tree.css')}}">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Team</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">My Team</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $rootUser->name }}</h3>

                            <p>{{ $rootUser->connection }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Coming soon</h3>

                            <p>Basic Level income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $levelpremium ?? 0 }}</h3>

                            <p>Premium Level income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>₹{{ $rankincome->amount ?? 0 }}</h3>
                            <p>Rank Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            {{-- <h3>₹{{ $downlineSummary['basic']['total'] }}</h3> --}}
                            <h3>Coming Soon</h3>

                            <p>Basic Direct Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-custom">
                        <div class="inner">
                            <h3>₹{{ $downlineSummary['premium']['total'] }}</h3>

                            <p>Premium Direct Income</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <h2>Binary Tree Structure</h2> -->
            <div class="row d-flex">
                <div class="col-md-4" style="margin: 0 auto;">
                    <form action="{{ route('binary_tree') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" name="user_code" class="form-control" placeholder="Enter User ID"
                                    required>
                                <button type="submit" class="btn btn-primary ml-3">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if ($parent && $rootUser->id !== auth()->id())
                <div class="back-parent">
                    <a href="{{ route('binary_tree', ['id' => $parent->id]) }}"
                        class="btn btn-primary mb-3 parent_button">Back
                        to Parent</a>
                </div>
            @endif
        </div>

        <div class="table-responsive px-2">
            <table id="tbl-binary-tree" class="atable table table-bordered text-center"
                data-user-url="{{ route('addUser') }}">
                <thead>
                </thead>
                <tbody>
                    @php
                        $buttonaddeda = [];
                        $lastNonEmptyParents = [];
                        $positions = [];
                        // Recursive function for rendering rows
                        function renderRow(
                            $nodes,
                            $level,
                            $maxLevel,
                            $lastNonEmptyParents,
                            $positions = [],
                            &$buttonaddeda = [],
                        ) {
                            if ($level > $maxLevel) {
                                return;
                            }

                            $colspan = pow(2, $maxLevel - $level);
                            echo '<tr>';

                            $nextNonEmptyParents = [];
                            $nextPositions = [];

                            foreach ($nodes as $index => $node) {
                                echo '<td colspan="' . $colspan . '">';

                                if (!empty($node['user'])) {
                                    $positionindex = '';
                                    if (isset($positions[$index])) {
                                        $positionindex = ucfirst($positions[$index]);
                                    }

                                    $parentId = $node['user']['parent_id'];
                                    $buttonindextoadd = $parentId . '-' . $positionindex;

                                    if (!in_array($buttonindextoadd, $buttonaddeda)) {
                                        $buttonaddeda[] = $buttonindextoadd;
                                    }

                                    $packages = array_map(function ($pack) {
                                        return $pack['package_id'];
                                    }, $node['user']->userPackages->toArray());

                                    // Display the user details
                                    echo '<a href="' .
                                        route('binary_tree', $node['user']['id']) .
                                        '" class="tree-user">';
                                    echo '<img src="' .
                                        asset('assets/dist/img/user.svg') .
                                        '" class="' .
                                        $node['user']['border_class'] .
                                        '" alt="user" width="50">';
                                    echo '<p class="mb-0">' . $node['user']['name'] . '</p>';
                                    echo '<p class="mb-0">' . $node['user']['connection'] . '</p>';
                                    // echo '<p class="mb-0">' . $node['user']['email'] . '</p>';

                                    $leftCount = $node['user']->leftDownlineCount();
                                    $rightCount = $node['user']->rightDownlineCount();
                                    echo '<p class="mb-0"><b>' . $leftCount . ' | ' . $rightCount . '</b></p>';
                                    echo '</a>';
                                    if (!empty(array_diff([13], $packages))) {
                                        echo '<button class="btn btn-primary btn-sm btn-add-package ' .
                                            $node['user']['border_class'] .
                                            '" data-user="' .
                                            $node['user']['id'] .
                                            '" data-packages="' .
                                            json_encode($packages) .
                                            '"><i class="fas fa-plus"></i></button>';
                                    }

                                    $lastNonEmptyParents[$index] = $node['user']['id'];

                                    $nextNonEmptyParents[] = $node['user']['id'];
                                    $nextNonEmptyParents[] = $node['user']['id'];

                                    $nextPositions[] = 'left';
                                    $nextPositions[] = 'right';
                                } else {
                                    $positionindex = '';
                                    $parentId = $lastNonEmptyParents[$index] ?? 'N/A';
                                    if (isset($positions[$index])) {
                                        $positionindex = ucfirst($positions[$index]);
                                    }

                                    $buttonindextoadd = $parentId . '-' . $positionindex;

                                    if (in_array($buttonindextoadd, $buttonaddeda)) {
                                        echo '<div class="vacant-user">';
                                        echo '<img src="' .
                                            asset('assets/dist/img/user-block.svg') .
                                            '" alt="user" width="50">';
                                        echo '</div>';
                                    } else {
                                        $buttonaddeda[] = $buttonindextoadd;
                                        echo '<div class="no-user clickable" data-parent-id="' .
                                            $parentId .
                                            '" data-position-index="' .
                                            $positionindex .
                                            '">';
                                        echo '<img src="' .
                                            asset('assets/dist/img/add-user.svg') .
                                            '" alt="user" width="50">';
                                        echo '</div>';
                                    }

                                    $nextNonEmptyParents[] = $parentId;
                                    $nextNonEmptyParents[] = $parentId;

                                    $nextPositions[] = 'left';
                                    $nextPositions[] = 'right';
                                }

                                echo '</td>';
                            }

                            echo '</tr>';

                            $nextLevelNodes = [];
                            foreach ($nodes as $node) {
                                $nextLevelNodes[] = $node['left'] ?? null;
                                $nextLevelNodes[] = $node['right'] ?? null;
                            }

                            renderRow(
                                $nextLevelNodes,
                                $level + 1,
                                $maxLevel,
                                $nextNonEmptyParents,
                                $nextPositions,
                                $buttonaddeda,
                            );
                        }

                        renderRow([$binaryTree], 1, 4, $lastNonEmptyParents, $positions, $buttonaddeda);
                    @endphp
                </tbody>

            </table>
        </div>
    </div>
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="user-form" action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">User Registration</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label for="parent_id" class="form-label">Placement</label>
                            <input type="text" name="parent_name" id="parent_name" class="form-control"
                                value="{{ old('parent_name') }}" disabled>
                            <input type="hidden" name="parent_id" id="parent_id" class="form-control"
                                value="{{ old('parent_id') }}">
                            <input type="hidden" name="parent_level" id="level" class="form-control"
                                value="{{ old('level') }}">
                            <input type="hidden" name="connections" id="user_code" class="form-control"
                                value="{{ old('user_code') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="position_child" class="form-label">Postion</label>
                            <input type="text" name="position_child" id="position_child" class="form-control"
                                value="{{ old('position_child') }}" disabled>
                            <input type="hidden" name="position" id="position" class="form-control"
                                value="{{ old('position') }}">
                        </div>
                        {{-- <div class="form-group col-md-6">
                            <label for="package_select" class="form-label">Select Package</label>
                            <select name="package" id="package_select" class="form-control" required>
                                <option value="">-- Select Package --</option>
                            </select>
                        </div> --}}
                        <div class="form-group col-md-6">
                            <label for="ancestors_select" class="form-label">Select Sponsor</label>
                            <select name="sponsor" id="ancestors_select" class="form-control">
                                <option value="">-- Select Sponsor --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no" class="form-label">Phone Number</label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control"
                                value="{{ old('phone_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="pan_card_no" class="form-label">PAN Card Number</label>
                            <input type="text" name="pan_card_no" id="pan_card_no" class="form-control"
                            oninput="this.value = this.value.toUpperCase()" value="{{ old('pan_card_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pincode" class="form-label">Pin Code</label>
                            <input type="text" name="pincode" id="pincode" class="form-control"
                                value="{{ old('pincode') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                            <span class="error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updatePinForm" method="POST" action="{{ route('update_pin') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="packageModalLabel">Package Activation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="package_id">Select Package</label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">--Choose Option---</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pinId">Available Pins</label>
                            <select class="form-control" id="pinId" name="pin_id" required>
                                <option value="">--Choose Pin---</option>
                            </select>
                        </div>
                        <input type="hidden" class="form-control" id="userid" name="userid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        @if (session()->has('error'))
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session()->get('error') }}",
            });
        @endif
    </script>
  <script src="{{asset('assets/dist/js/viewpage/binary_tree.js?v=0.1')}}"></script>
@endsection
