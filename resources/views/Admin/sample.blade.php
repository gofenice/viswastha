@extends('Admin.admin_header')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Binary Tree test</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Binary Tree</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <style>
            .atable.table.table-bordered.text-center {
                border: 2px solid #007bff;
            }

            .atable.table.table-bordered.text-center td,
            .atable.table.table-bordered.text-center th {
                border: 2px solid #007bff;
            }

            .back-parent {
                display: flex;
            }

            .parent_button {
                margin: 0 auto;
            }
        </style>

        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4" style="margin: 0 auto;">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">{{ $rootUser->name }}</span>
                            <span class="info-box-text">VM{{ $rootUser->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹35,210.43</h5>
                            <span class="description-text">Team Left Basic BV</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹10,390.90</h5>
                            <span class="description-text">Team Right Basic BV</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹24,813.53</h5>
                            <span class="description-text">Self Basic BV</span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹35,210.43</h5>
                            <span class="description-text">Team Left Premium BV </span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹10,390.90</h5>
                            <span class="description-text">Team Right Premium BV</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header">₹24,813.53</h5>
                            <span class="description-text">Self Premium BV</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row d-flex">
                <div class="col-md-4" style="margin: 0 auto;">
                    <form action="{{ route('sample') }}" method="GET" class="mb-4">
                        @csrf
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" name="id" class="form-control" placeholder="Enter User ID"
                                    required>
                                <button type="submit" class="btn btn-primary ml-3">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if ($parent)
                <div class="back-parent">
                    <a href="{{ route('sample', ['id' => $parent->id]) }}" class="btn btn-primary mb-3 parent_button">Back
                        to Parent</a>
                </div>
            @endif
            <div class="table-responsive">
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

                                        // Display the user details
                                        echo '<a href="' . route('sample', $node['user']['id']) . '">';
                                        echo '<img src="' .
                                            asset('assets/dist/img/user.svg') .
                                            '" alt="user" width="50">';
                                        echo '<p>' . $node['user']['name'] . '</p>';
                                        echo '<p class="mb-0">' . $node['user']['email'] . '</p>';
                                        echo '<p class="mb-0">' . $node['user']['phone_no'] . '</p>';
                                        echo '<p class="mb-0">User ID: ' . $node['user']['id'] . '</p>';
                                        echo '<p class="mb-0">Parent ID: ' .
                                            ($node['user']['parent_id'] ?? 'N/A') .
                                            '</p>';
                                        if (isset($positions[$index])) {
                                            echo '<p class="mb-0">Position: ' . $positionindex . '</p>';
                                        }
                                        echo '</a>';

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
                                            echo '<div class="no-user">';
                                            echo '<p class="mb-0">VACANT</p>';
                                            echo '<p class="mb-0">Parent ID: ' . $parentId . '</p>';
                                            echo '<p class="mb-0">Position: ' . $positionindex . '</p>';
                                            echo '</div>';
                                        } else {
                                            $buttonaddeda[] = $buttonindextoadd;
                                            echo '<div class="no-user clickable" data-parent-id="' .
                                                $parentId .
                                                '" data-position-index="' .
                                                $positionindex .
                                                '">';
                                            echo '<img src="https://www.hrinnovation.in/CommonPages/images/newPerson.png" alt="No User" class="no-user-image">';
                                            echo '<p class="mb-0">Parent ID: ' . $parentId . '</p>';
                                            echo '<p class="mb-0">Position: ' . $positionindex . '</p>';
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
                            <label for="parent_id" class="form-label">Parent</label>
                            <input type="text" name="parent_name" id="parent_name" class="form-control"
                                value="{{ old('parent_name') }}" disabled>
                            <input type="hidden" name="parent_id" id="parent_id" class="form-control"
                                value="{{ old('parent_id') }}">
                            <input type="hidden" name="parent_level" id="level" class="form-control"
                                value="{{ old('level') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="position_child" class="form-label">Postion</label>
                            <input type="text" name="position_child" id="position_child" class="form-control"
                                value="{{ old('position_child') }}" disabled>
                            <input type="hidden" name="position" id="position" class="form-control"
                                value="{{ old('position') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="package_select" class="form-label">Select Package</label>
                            <select name="package" id="package_select" class="form-control" required>
                                <option value="">-- Select Package --</option>
                            </select>
                        </div>
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
                                value="{{ old('pan_card_no') }}" required>
                            <span class="error-message text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="error-message text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                            <span class="error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            $('.no-user.clickable').click(function(e) {
                e.preventDefault();
                const parentId = $(this).data('parentId');
                const positionIndex = $(this).data('positionIndex');

                $.ajax({
                    url: $('#tbl-binary-tree').data("user-url"),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include CSRF token for security
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        parentId: parentId,
                        positionIndex: positionIndex
                    }),
                    success: function(data) {
                        if (data.success) {
                            const parent = data.parent;

                            // Set the values for specific form fields
                            $('#parent_id').val(parent.id || parentId);
                            $('#position_child').val(parent.position_child);
                            $('#position').val(parent.position);
                            $('#parent_name').val(data.parent.name);
                            $('#level').val(parent.level);
                            $('#name, #email, #phone_no, #pan_card_no, #address, #password')
                                .val('');

                            const $ancestorsSelect = $('#ancestors_select');
                            $ancestorsSelect.empty(); // Clear existing options

                            // Add the current parent as the first option
                            $('<option>')
                                .val(data.parent.id)
                                .text(data.parent.name + ' (Current Parent)')
                                .appendTo($ancestorsSelect);

                            // Add the ancestor options
                            data.ancestors.forEach(function(ancestor) {
                                $('<option>')
                                    .val(ancestor.id)
                                    .text(ancestor.name)
                                    .appendTo($ancestorsSelect);
                            });

                            // Populate the packages dropdown
                            const $packagesSelect = $('#package_select');
                            $packagesSelect.empty(); // Clear existing options

                            // Add package options
                            data.packages.forEach(function(package) {
                                $('<option>')
                                    .val(package.id)
                                    .text(package.name)
                                    .appendTo($packagesSelect);
                            });

                            $('#modal-lg').modal('show');
                        } else {
                            alert('Failed to add user: ' + data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                });

            });

            $('#user-form').ajaxForm({
                beforeSubmit: function(formData, jqForm, options) {
                    $('#user-form button[type="submit"]').prop('disabled', true);
                },
                success: function(responseText, statusText, xhr, $form) {
                    const data = JSON.parse(responseText);
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    $('#user-form .error-message').text("");
                    if (data.status == "validation") {
                        $.each(data.errors, function(key, val) {
                            $('[name="' + key + '"]').closest('.form-group').find(
                                '.error-message').text(val);
                        })
                    } else if (data.status == "success") {
                        $form[0].reset();
                        window.location.reload();
                        console.log('submit');

                    }
                },
                error: function(xhr, status, error) {
                    $('#user-form button[type="submit"]').prop('disabled', false);
                    console.error(error, xhr, status);
                }
            });
        });
    </script>
@endsection
