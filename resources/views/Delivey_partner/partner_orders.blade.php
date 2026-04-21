@extends('Delivey_partner.parter_header')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('partner') }}">Home</a></li>
                            <li class="breadcrumb-item active">order List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (isset($holiday_bookings) && $holiday_bookings->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Holiday Package Bookings</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <!-- <th>User Login Time</th> -->
                                                <th>Package</th>
                                                <th>Phone</th>
                                                <th>Booking Date</th>
                                                <!-- <th>Status</th> -->
                                                @if (Auth::check() && Auth::user()->role == 'partner')
                                                    <th>Action</th>
                                                @endif
                                                @if (Auth::check() && Auth::user()->role == 'gst')
                                                    <th>Invoice</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($holiday_bookings as $key => $booking)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $booking->user->name ?? 'N/A' }}<br>{{ $booking->user->connection ?? 'N/A' }}
                                                    </td>
                                                    <!-- <td>{{ $booking->created_at->format('d-M-Y h:i A') }}</td> -->
                                                    <td>{{ $booking->package->name ?? 'N/A' }}</td>
                                                    <td>{{ $booking->phone_no }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->date)->format('d-M-Y') }}</td>
                                                    <!-- <td>
                                                        @if ($booking->status == 0)
    <span class="badge badge-warning">Pending</span>
@elseif($booking->status == 1)
    <span class="badge badge-success">Confirmed</span>
@elseif($booking->status == 2)
    <span class="badge badge-success">Activated</span>
@else
    <span class="badge badge-secondary">{{ $booking->status }}</span>
    @endif
                                                    </td> -->
                                                    @if (Auth::check() && Auth::user()->role == 'partner')
                                                        <td>
                                                            @if ($booking->status == 0)
                                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                    data-target="#holidayEditModal"
                                                                    data-id="{{ $booking->id }}">
                                                                    Confirm
                                                                </button>
                                                            @elseif($booking->status == 1)
                                                                <span class="badge badge-warning">Confirmed</span><br>
                                                                @if (isset($controlId) && $controlId == 1)
                                                                    <button class="btn btn-sm btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#holidayActivateModal"
                                                                        data-id="{{ $booking->id }}">
                                                                        Change Status
                                                                    </button>
                                                                @endif
                                                            @elseif ($booking->status == 2)
                                                                <span class="badge badge-success">Activated</span>
                                                                <br>
                                                                <span class="badge badge-info">
                                                                    Activated:
                                                                    {{ \Carbon\Carbon::parse($booking->date)->format('d-M-Y') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if (Auth::check() && Auth::user()->role == 'gst')
                                                        <td>
                                                            @if ($booking->invoice_path)
                                                                <a href="{{ asset($booking->invoice_path) }}" download
                                                                    class="btn btn-sm btn-primary">Download</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    @if ($controlId == 1)
                                        Holiday Bookings
                                    @else
                                        Orders
                                    @endif
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Product</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Pacakge</th>
                                            <th>Created</th>
                                            @if (Auth::check() && Auth::user()->role == 'partner')
                                                <th>Action</th>
                                            @endif
                                            @if (Auth::check() && Auth::user()->role == 'gst')
                                                <th>Invoice</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allorders as $key => $allorder)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $allorder->user->name }}<br>{{ $allorder->user->connection }}</td>
                                                <td>{{ $allorder->product->product_name }}</td>
                                                <td>
                                                    <textarea class="form-control" readonly>{{ $allorder->address }}</textarea>
                                                </td>
                                                <td>{{ $allorder->phone_no }}</td>
                                                <td>{{ $allorder->package->name }}</td>
                                                <td>{{ $allorder->created_at->format('d-M-y') }}</td>
                                                @if (Auth::check() && Auth::user()->role == 'partner')
                                                    <td>
                                                        @if ($allorder->status === '0')
                                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                data-target="#editModal" data-id="{{ $allorder->id }}">
                                                                Confirm
                                                            </button>
                                                        @elseif ($allorder->status === '1')
                                                            <span class="badge badge-warning">Confirmed</span><br>
                                                            @if ($controlId == 1)
                                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                    data-target="#CompanyModal"
                                                                    data-id="{{ $allorder->id }}">
                                                                    Change Status
                                                                </button>
                                                            @else
                                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                    data-target="#statusModal"
                                                                    data-id="{{ $allorder->id }}">
                                                                    Change Status
                                                                </button>
                                                            @endif
                                                        @else
                                                            @if ($controlId == 1)
                                                                <span class="badge badge-success">Activated</span><br>
                                                                <span class="badge badge-info">
                                                                    Activated date:
                                                                    {{ \Carbon\Carbon::parse($allorder->date)->format('d-M-Y') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-success">Product
                                                                    Delivered</span><br>
                                                                @if ($allorder->delivery_type)
                                                                    <span class="badge badge-info">
                                                                        Delivery Type:
                                                                        {{ ucfirst(str_replace('_', ' ', $allorder->delivery_type)) }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                @endif
                                                @if (Auth::check() && Auth::user()->role == 'gst')
                                                    <td>
                                                        @if ($allorder->invoice_path)
                                                            <a href="{{ asset($allorder->invoice_path) }}" download
                                                                class="btn btn-primary">
                                                                Download Invoice
                                                            </a>
                                                        @else
                                                            <p>Invoice not available</p>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('approveproduct') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Confirm the Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="productListId" id="productListId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="1">Confirmed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('partnerstatus') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Product status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="orderId" id="orderId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="2">Delivered</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="delivery_type">Select Delivery Type</label>
                            <select name="delivery_type" id="delivery_type" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="courier">Courier</option>
                                <option value="office_pickup">Office Pickup</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CompanyModal" tabindex="-1" aria-labelledby="CompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('holidaystatus') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="CompanyModalLabel">Change Product status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="orderId" id="orderholId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">--Choose option--</option>
                                <option value="2">Activated</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="activeDate">Activation Date</label>
                            <input type="date" name="activeDate" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modals for Holiday Package Booking Actions -->
    <div class="modal fade" id="holidayEditModal" tabindex="-1" aria-labelledby="holidayEditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('approveHolidayBooking') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="holidayEditModalLabel">Confirm Holiday Booking</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="bookingId" id="bookingConfirmId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" class="form-control" required>
                                <option value="1">Confirmed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="holidayActivateModal" tabindex="-1" aria-labelledby="holidayActivateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('activateHolidayBooking') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="holidayActivateModalLabel">Activate Holiday Booking</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="bookingId" id="bookingActivateId">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" class="form-control" required>
                                <option value="2">Activated</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="activeDate">Activation Date</label>
                            <input type="date" name="activeDate" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {

            const now = new Date();

            const headingText = $('.card-title').text().trim().replace(/\s+/g, '_');
            // Format date
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();

            // Format time to 12-hour with AM/PM
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert to 12-hour format
            const formattedTime = String(hours).padStart(2, '0') + '-' + minutes + ampm;

            const formattedDateTime = `${day}-${month}-${year}_${formattedTime}`;

            const filename = `${headingText}_${formattedDateTime}`;

            $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'btn btn-sm btn-success mb-2',
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        title: filename,
                        filename: filename,
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },

                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm btn-danger mb-2',
                        text: '<i class="fas fa-file-pdf"></i> Export PDF',
                        title: filename,
                        filename: filename,
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                ],
                responsive: true,
                paging: true,
                ordering: true,
                info: true,
                stateSave: true
            });
        });
    </script>

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
        $(document).ready(function() {
            const teamLink = $('.nav-link.order');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
    <script>
        $(function() {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var productListId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#productListId').val(productListId);
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            $('#statusModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var orderId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#orderId').val(orderId);
            });
            $('#CompanyModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var orderId = button.data('id'); // Extract info from data-* attributes

                var modal = $(this);
                modal.find('#orderholId').val(orderId);
            });
        });
    </script>
@endsection
