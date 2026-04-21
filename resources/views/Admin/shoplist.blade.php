@extends('Admin.admin_header')
@section('title', 'vishwastha | Shop List')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Offline Shop List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Shop List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="row" style="margin: 0 auto; padding-left: 3rem; padding-right: 3rem; ">
            <!-- Left Side: Search Form -->
            <div class="col-md-6 mx-auto">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Search Shop</h3>
                    </div>
                    <form method="GET" action="{{ route('shop_list') }}" class="row mb-3 mt-3">
                        <div class="col-md-4">
                            <select name="state_id" id="state_id" class="form-control">
                                <option value="">-- Select State --</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->state_id }}"
                                        {{ request('state_id') == $state->state_id ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">-- Select District --</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->district_id }}"
                                        {{ request('district_id') == $district->district_id ? 'selected' : '' }}>
                                        {{ $district->district_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('shop_list') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>Shop Name</th>
                            <th>Owner Name</th>
                            <th>Address</th>
                            <th>Email | Phone</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shops as $index => $shop)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $shop->name ?? 'N/A' }}
                                    @if ($shop->status == 2)
                                        - I
                                    @else
                                    @endif
                                </td>
                                <td>{{ $shop->owner->name ?? 'N/A' }}</td>
                                <td>{{ $shop->address }}</td>
                                <td>{{ $shop->email }} <br> {{ $shop->phone }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $shop->shop_profile) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $shop->shop_profile) }}" alt="Announcement Image"
                                            class="img-fluid" width="200px" height="200px"
                                            style="border-radius: 10px; transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                </td>
                            </tr>
                        @endforeach
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
        $(document).ready(function() {
            // const teamLink = $('.nav-link.repurchases');
            // const treeviewLink = $('.nav.nav-treeview.repurchases');
            // const mainLiLink = $('.nav-item.has-treeview.repurchases');
            const walletLink = $('.nav-link.shoplist');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        $(document).ready(function() {
            $('#state_id').on('change', function() {
                var stateId = $(this).val();
                $('#district_id').html('<option value="">-- Loading... --</option>');
                if (stateId) {
                    $.ajax({
                        url: "{{ route('get.districts') }}",
                        type: "GET",
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#district_id').empty().append(
                                '<option value="">-- Select District --</option>');
                            $.each(data, function(index, district) {
                                $('#district_id').append('<option value="' + district
                                    .district_id + '">' + district.district_name +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    $('#district_id').html('<option value="">-- Select District --</option>');
                }
            });
        });
    </script>
@endsection
