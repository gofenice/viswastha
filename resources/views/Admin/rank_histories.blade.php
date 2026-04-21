@extends('Admin.admin_header')
@section('title', 'vishwastha | Rank History')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Rank History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Rank History </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Rank</th>
                            <th>Achive Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rankHistories as $index => $rankHis)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rankHis->user->name }}<br> ( {{ $rankHis->user->connection }} )</td>
                                <td>{{ $rankHis->rank->rank_name }}</td>
                                <td> {{ $rankHis->created_at->format('d-M-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')

    <!-- Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script>
        $(document).ready(function() {
            const now = new Date();

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
            const filename = `RankHistory_${formattedDateTime}`;

            $('#example1').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'btn btn-sm btn-success mb-2',
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        title: filename,
                        filename: filename,
                    },

                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-sm btn-danger mb-2',
                        text: '<i class="fas fa-file-pdf"></i> Export PDF',
                        title: filename,
                        filename: filename,
                    },
                ]
            });
        });
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

        $(document).ready(function() {
            const rankLink = $('.nav-link.rankht');
            if (rankLink.length) {
                rankLink.addClass('active');
            }
        });
    </script>
@endsection
