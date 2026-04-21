@extends('Admin.admin_header')
@section('title', 'vishwastha | Admin Wallet')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                            <li class="breadcrumb-item active">Wallet</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="container">
            <div class="row">
                <div class="card card-info col-md-4 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Transactions</h3>
                    </div>
                    <form id="walletForm" action="{{ route('withdraw.request') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="transaction_type">Select Transaction Type</label>
                            <select name="transaction_type" id="transaction_type" class="form-control" required>
                                <option value="">-- Choose Type --</option>
                                <option value="withdrawal" data-action="{{ route('withdraw.request') }}">Withdrawal</option>
                                <option value="royalty" data-action="{{ route('adminToRoyalty') }}">Transfer to Royalty
                                </option>
                                <option value="bonus" data-action="{{ route('adminToBonus') }}">Transfer to Special
                                    Incentive</option>
                                <option value="rank" data-action="{{ route('adminToRank') }}">Transfer to Premium Rank
                                </option>
                                <option value="basicrank" data-action="{{ route('adminToBasicRank') }}">Transfer to Basic
                                    Rank
                                </option>
                                <option value="privilege" data-action="{{ route('adminToPrivilege') }}">Transfer to
                                    Privilege</option>
                                <option value="board" data-action="{{ route('adminToBoard') }}">Transfer to Board</option>
                                <option value="executive" data-action="{{ route('adminToExecutive') }}">Transfer to
                                    Executive
                                </option>
                                <option value="executive" data-action="{{ route('adminToIncentive') }}">Transfer to
                                    Incentive
                                </option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="rankSelectGroup">
                            <label for="rank_id">Choose Rank</label>
                            <select name="rank_id" id="rank_id" class="form-control">
                                <option value="">-- Select Rank --</option>
                                <option value="2">Gold</option>
                                <option value="3">Platinum</option>
                                <option value="4">Pearl</option>
                                <option value="5">Ruby</option>
                                <option value="6">Diamond</option>
                                <option value="7">Double Diamond</option>
                                <option value="8">Emerald</option>
                                <option value="9">Crown</option>
                                <option value="10">Royal Crown</option>
                                <option value="11">Manager</option>
                                <option value="12">Ambassador</option>
                                <option value="13">Royal Crown Ambassador</option>
                            </select>
                        </div>

                        {{-- Basic Rank Dropdown --}}
                        <div class="form-group d-none" id="basicRankSelectGroup">
                            <label for="basic_rank_id">Choose Basic Rank</label>
                            <select name="basic_rank_id" id="basic_rank_id" class="form-control">
                                <option value="2">1 star</option>
                                <option value="3">2 star</option>
                                <option value="4">3 star</option>
                                <option value="5">4 star</option>
                                <option value="6">5 star</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>

                        <div class="form-group">
                            <label for="total_income">Current Balance</label>
                            <input type="text" name="total_income" id="total_income" class="form-control"
                                value="{{ $adminWallet->total_income ?? 0 }}" readonly>
                            <input type="hidden" name="id" id="id" value="{{ $adminWallet->id }}">
                        </div>

                        {{-- <div class="form-group">
                            <input type="checkbox" name="terms" required>
                            <label>I agree to the <a href="#!" class="toastsDefaultWarning">Terms and
                                    Conditions</a></label>
                        </div> --}}

                        <button type="submit" class="btn btn-primary float-right mb-2">Submit Transaction</button>
                    </form>

                </div>

                <div class="card card-info col-md-3 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Add income to Admin Wallet</h3>
                    </div>
                    <form id="adminform" action="{{ route('addManuallyadmin') }}" method="POST">
                        @csrf

                        <p class="mt-4"> This option allows adding a specific amount directly to the admin wallet for
                            bonus distribution purposes.</p>
                        <div class="form-group mt-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>
                        <input type="hidden" name="admin_id" value="{{ $adminWallet->id }}">

                        <button type="submit" class="btn btn-primary float-right mb-2">Add</button>
                    </form>

                </div>

                <div class="card card-info col-md-4 mx-auto">
                    <div class="card-header">
                        <h3 class="card-title">Add income to user Wallet</h3>
                    </div>
                    <form id="getUserId" action="{{ route('admininctoUser') }}" method="POST"
                        data-user-url="{{ route('get_user_name') }}">
                        @csrf
                        <div class="form-group">
                            <label for="userId">User ID</label>
                            <input type="text" class="form-control" id="userId" required name="userId"
                                placeholder="Enter User ID">
                        </div>
                        <div class="form-group">
                            <label for="userName">User Name</label>
                            <input type="text" class="form-control" id="userName" name="userName"
                                placeholder="Name" readonly>
                            @error('userName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="Enter amount" required>
                        </div>


                        <button type="submit" class="btn btn-primary float-right mb-2">Transfer</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="card recieptList col-md-11 mx-auto">

            <div class="card-header">
                <h3 class="card-title">Amount List</h3>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr class="bg-info">
                            <th>#</th>
                            <th>From / To</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Created</th>
                            <th>Running Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $runningTotal = 0; // Initialize running total
                            $transactions = $adminAmountList->reverse(); // Reverse the list to process in chronological order
                        @endphp

                        @forelse($transactions as $index => $adminAmount)
                            @php
                                if (in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21])) {
                                    $runningTotal -= $adminAmount->amount; // Deduct withdrawal amount for type 4,6 and 8
                                } else {
                                    $runningTotal += $adminAmount->amount; // Add income amount for other types
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $adminAmount->fromUser->name ?? 'Rank' }}<br>{{ $adminAmount->fromUser->connection ?? '' }}
                                </td>

                                {{-- Color-coded Amount Display --}}
                                <td
                                    style="color: {{ in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? 'red' : 'green' }};">
                                    {{ in_array($adminAmount->type, [4, 6, 8, 9, 10, 12, 13, 14, 15, 21]) ? '-' : '+' }}
                                    {{ $adminAmount->amount }}
                                </td>
                                {{-- Type Label --}}
                                <td>
                                    @if ($adminAmount->type == 1)
                                        Rank Income
                                    @elseif($adminAmount->type == 2)
                                        Admin Fee
                                    @elseif($adminAmount->type == 3)
                                        TDS Fee
                                    @elseif($adminAmount->type == 4)
                                        <span class="text-danger">Transfer to Royalty</span>
                                    @elseif($adminAmount->type == 5)
                                        Unpaid Rank Amount
                                    @elseif($adminAmount->type == 7)
                                        Donation Amount
                                    @elseif($adminAmount->type == 8)
                                        <span class="text-danger">Transfer to Special Incentive</span>
                                    @elseif($adminAmount->type == 9)
                                        <span class="text-danger">Transfer to User</span>
                                    @elseif($adminAmount->type == 10)
                                        <span class="text-danger">Transfer to Rank income</span>
                                    @elseif($adminAmount->type == 11)
                                        <span class="text-success">Manually add to wallet</span>
                                    @elseif($adminAmount->type == 12)
                                        <span class="text-danger">Transfer to Privilege income</span>
                                    @elseif($adminAmount->type == 13)
                                        <span class="text-danger">Transfer to Board income</span>
                                    @elseif($adminAmount->type == 14)
                                        <span class="text-danger">Transfer to Executive income</span>
                                    @elseif($adminAmount->type == 15)
                                        <span class="text-danger">Transfer to Incentive income</span>
                                    @elseif($adminAmount->type == 16)
                                        <span class="text-success">Board Incentive</span>
                                    @elseif($adminAmount->type == 17)
                                        <span class="text-success">Executive Incentive</span>
                                    @elseif($adminAmount->type == 18)
                                        <span class="text-success">Privilege Incentive</span>
                                    @elseif($adminAmount->type == 19)
                                        <span class="text-success">Basic Rank Income</span>
                                    @elseif($adminAmount->type == 20)
                                        <span class="text-success">Unpaid Basic Rank Income</span>
                                    @elseif($adminAmount->type == 21)
                                        <span class="text-danger">Transfer Basic Rank Income</span>
                                    @elseif($adminAmount->type == 22)
                                        <span class="text-success">Vstore Incentive</span>
                                    @elseif($adminAmount->type == 23)
                                        <span class="text-success">TCS Income - My Vstore</span>
                                    @elseif($adminAmount->type == 24)
                                        <span class="text-success">GST Income - My Vstore</span>
                                    @else
                                        <span class="text-danger">Withdrawal</span>
                                    @endif
                                </td>

                                <td>{{ $adminAmount->created_at->format('d-m-Y') }}</td>

                                {{-- Running Total Column --}}
                                <td>{{ $runningTotal }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No details found.</td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>

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
            const teamLink = $('.nav-link.adminWallet');
            const treeviewLink = $('.nav.nav-treeview.adminWallet');
            const mainLiLink = $('.nav-item.has-treeview.adminWallet');
            const walletLink = $('.nav-link.adminWallet');
            if (walletLink.length) {
                walletLink.addClass('active');
                teamLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
        $('.toastsDefaultWarning').click(function() {
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Withdrawal Charges',
                subtitle: 'Important Notice',
                body: '5% TDS will be deducted from the withdrawal amount.'
            })
        });
    </script>
    <script>
        document.getElementById('transaction_type').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const newAction = selectedOption.getAttribute('data-action');
            if (newAction) {
                document.getElementById('walletForm').setAttribute('action', newAction);
            }
        });

        $(document).ready(function() {
            $('#userId').on('change', function() {
                const userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: $('#getUserId').data("user-url"),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token for security
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            userId: userId
                        }),
                        success: function(response) {
                            if (response.name) {
                                $('#userName').val(response.name);
                            }
                        },
                        error: function() {
                            $('#userName').val('');
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "User Not Found",
                            });
                        }
                    });
                } else {
                    $('#userName').val(''); // Clear the field if userId is empty
                }
            });
        });
    </script>
    <script>
        document.getElementById('transaction_type').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedText = selectedOption.text.trim();
            const newAction = selectedOption.getAttribute('data-action');

            const rankGroup = document.getElementById('rankSelectGroup');
            const basicRankGroup = document.getElementById('basicRankSelectGroup');

            // Update the form action dynamically
            if (newAction) {
                document.getElementById('walletForm').setAttribute('action', newAction);
            }

            // Handle visibility logic
            if (selectedText === 'Transfer to Premium Rank') {
                rankGroup.classList.remove('d-none');
                basicRankGroup.classList.add('d-none');
                document.getElementById('rank_id').setAttribute('required', 'required');
                document.getElementById('basic_rank_id').removeAttribute('required');
            } else if (selectedText === 'Transfer to Basic Rank') {
                basicRankGroup.classList.remove('d-none');
                rankGroup.classList.add('d-none');
                document.getElementById('basic_rank_id').setAttribute('required', 'required');
                document.getElementById('rank_id').removeAttribute('required');
            } else {
                rankGroup.classList.add('d-none');
                basicRankGroup.classList.add('d-none');
                document.getElementById('rank_id').removeAttribute('required');
                document.getElementById('basic_rank_id').removeAttribute('required');
            }
        });
    </script>

@endsection
