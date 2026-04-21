@extends('Delivey_partner.parter_header')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Holiday Packages List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('partner') }}">Home</a></li>
                            <li class="breadcrumb-item active">Holiday List </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Holiday Bookings
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
                                        @foreach ($holidays as $key => $allorder)
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
            const teamLink = $('.nav-link.hlpackage');
            if (teamLink.length) {
                teamLink.addClass('active');
            }
        });
    </script>
    <script>
        $(function() {
            // $("#example1").DataTable();
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
