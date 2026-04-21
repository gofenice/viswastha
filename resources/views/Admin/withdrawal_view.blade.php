@extends('Admin.admin_header')
@section('title', 'vishwastha  | Withdrawal')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Withdrawal List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Withdrawal List </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        @if (Auth::check() && Auth::user()->role !== 'superadmin')
            <div class="card card-info col-md-4" style="margin: 0 auto;">
                <div class="card-header">
                    <h3 class="card-title">Withdrawal</h3>
                </div>
                <form action="{{ route('withdraw.request') }}" method="POST" id="withdrawalModal">
                    @csrf
                    <div class="form-group">
                        <label for="total_income">Current Balance</label>
                        <input type="text" name="total_income" id="total_income" class="form-control"
                            value="{{ $userdata->total_income ?? 0 }}" placeholder="Enter amount" readonly>
                        <input type="hidden" name="id" id="id" class="form-control" value="{{ $userdata->id }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="amount">Withdrawal Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount"
                            required>
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <input type="checkbox" checked name="donate" value="1">
                        <label class="toastsDefaultSuccess"> I agree to donate Rs 50 for charity</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" required checked>
                        <label>I agree to the <a href="#!" class="toastsDefaultWarning">Terms and Conditions
                            </a></label>
                    </div>
                    <div class="card-footer">
                        @if ($userBankDetails)
                            @if ($userBankDetails->status == 2)
                                @if (!$lastWithdrawal || $lastWithdrawal->status != 'pending')
                                    <button type="submit" id="submitButton" class="btn btn-primary float-right"
                                        @if (!$canWithdraw) disabled @endif>
                                        Submit Withdrawal Request
                                    </button>
                                @endif
                            @elseif ($userBankDetails->status == 1)
                                <p class="text-danger">Waiting for admin approval</p>
                            @endif
                        @else
                            <p class="text-danger">Please Update Your Bank Details</p>
                        @endif

                    </div>
                    @if ($lastWithdrawal)
                        @if ($lastWithdrawal->status == 'pending')
                            <p class="text-danger mt-2 text-center">Waiting for your last withdrawal approval. The next
                                withdrawal request can be made 7 days after the last approval date. </p>
                        @elseif (!$canWithdraw && $nextWithdrawalDate)
                            <p class="text-danger mt-2 text-center">Next withdrawal available in: <span
                                    id="countdown"></span>
                            </p>
                        @endif
                    @endif
                </form>
            </div>
        @endif


        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="widthdrawl" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                @if (Auth::check() && Auth::user()->role === 'superadmin')
                                    <th style="width: 150px">Current Wallet<br>Amount</th>
                                @endif
                                @if (Auth::check() && Auth::user()->role === 'superadmin')
                                    <th style="width: 150px">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $key => $request)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $request->user->name }} <br> {{ $request->user->connection }}</td>
                                    <td>
                                        Total :{{ $request->amount }}<br>
                                        Deduction :{{ $request->deduction_amount }}<br>
                                        Balance :{{ $request->balance_amount }}<br>

                                    </td>
                                    <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($request->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($request->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                    @if (Auth::check() && Auth::user()->role === 'superadmin')
                                        <td>
                                            {{ $request->user->total_income }}
                                        </td>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role === 'superadmin')
                                        <td>

                                            @if ($request->status == 'pending')
                                                <div class="d-flex" style="justify-content: space-around;">
                                                    <button class="btn btn-sm btn-success" data-toggle="modal"
                                                        data-target="#approveModal{{ $request->id }}">
                                                        Approve
                                                    </button>

                                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#rejectModal{{ $request->id }}">
                                                        Reject
                                                    </button>
                                                </div>

                                                <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1"
                                                    aria-labelledby="approveModalLabel{{ $request->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="{{ route('admin.withdraw.approve', $request->id) }}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="approveModalLabel{{ $request->id }}">Confirm
                                                                        Approval</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to approve this withdrawal
                                                                        request?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-success">Confirm</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1"
                                                    aria-labelledby="rejectModalLabel{{ $request->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="{{ route('admin.withdraw.reject', $request->id) }}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="rejectModalLabel{{ $request->id }}">Confirm
                                                                        Rejection</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to reject this withdrawal
                                                                        request?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Confirm</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($request->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
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
        $(function() {
            $("#widthdrawl").DataTable();
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
            const motherLink = $('.nav-link.wallet');
            const motherviewLink = $('.nav.nav-treeview.wallet');
            const mainLink = $('.nav-item.has-treeview.wallet');
            const motherIdLink = $('.nav-link.withdrawal');
            if (motherIdLink.length) {
                motherIdLink.addClass('active');
                motherLink.addClass('active');
                mainLink.addClass('menu-open');
                motherviewLink.css('display', 'block');
            }
        });
        $('.toastsDefaultWarning').click(function() {
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Withdrawal Charges',
                subtitle: 'Important Notice',
                body: '5% admin charge and 5% TDS will be deducted from the withdrawal amount.'
            })
        });
        // Set the countdown date from server-side data
        var countdownDate = new Date("{{ $nextWithdrawalDate }}").getTime();

        // Update the countdown every 1 second
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countdownDate - now;

            if (distance > 0) {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML =
                    (days > 0 ? days + "d " : "") +
                    hours + "h " + minutes + "m " + seconds + "s ";
            } else {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "Now!";
                location.reload(); // Reload the page to enable the button
            }
        }, 1000);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("#withdrawalModal");
            if (form) {
                form.addEventListener("submit", function(e) {
                    const submitButton = form.querySelector("button[type='submit']");
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = "Processing...";
                    }
                });
            }
        });
    </script>
    <script>
        function disableSubmitButton() {
            document.getElementById('submitButton').disabled = true;
        }
    </script>
@endsection
