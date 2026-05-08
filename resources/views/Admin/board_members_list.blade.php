@extends('Admin.admin_header')
@section('title', 'VISHWASTHA | Board Members')
@section('content')

<style>
    .queue-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }
    .stat-box {
        text-align: center;
        padding: 8px 4px;
    }
    .stat-box .stat-num {
        font-size: 22px;
        font-weight: 700;
        color: #343a40;
        line-height: 1;
    }
    .stat-box .stat-label {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }
    .fill-pref-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
    }
    .fill-pref-left  { background: #e3f2fd; color: #1565c0; }
    .fill-pref-right { background: #fce4ec; color: #b71c1c; }
    .last-assigned-text {
        font-size: 12px;
        color: #495057;
        line-height: 1.3;
    }
    .last-assigned-text small {
        color: #adb5bd;
        font-size: 11px;
    }
    .recent-assignment-row td { vertical-align: middle; font-size: 13px; }
    .section-title {
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #495057;
        margin-bottom: 10px;
    }
    .card-header .card-title { font-weight: 700; }
    .total-stat-bar {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        align-items: center;
    }
    .total-stat-bar .item { text-align: center; }
    .total-stat-bar .item .num { font-size: 20px; font-weight: 700; color: #343a40; }
    .total-stat-bar .item .lbl { font-size: 11px; color: #6c757d; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Board Members</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">Board Members</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="row">

                {{-- ── Add Board Member Form ─────────────────────────────── --}}
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-plus mr-1"></i> Add Board Member</h3>
                        </div>
                        <form action="{{ route('store_board_member') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="connection">User ID (Connection ID)</label>
                                    <input type="text" name="connection" class="form-control" id="connection"
                                        placeholder="Enter User Connection ID"
                                        oninput="this.value = this.value.toUpperCase()" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus mr-1"></i> Add Member
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ── Summary stats ─────────────────────────────── --}}
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Summary</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $totalActive   = $boardMembers->where('status', 1)->count();
                                $totalInactive = $boardMembers->where('status', 0)->count();
                                $totalAssigned = $assignmentStats->sum('total_assigned');
                            @endphp
                            <div class="total-stat-bar">
                                <div class="item">
                                    <div class="num text-success">{{ $totalActive }}</div>
                                    <div class="lbl">Active</div>
                                </div>
                                <div class="item">
                                    <div class="num text-danger">{{ $totalInactive }}</div>
                                    <div class="lbl">Inactive</div>
                                </div>
                                <div class="item">
                                    <div class="num text-primary">{{ $totalAssigned }}</div>
                                    <div class="lbl">Total Assigned</div>
                                </div>
                            </div>
                            <p class="text-muted mb-0" style="font-size:12px;">
                                <i class="fas fa-info-circle mr-1"></i>
                                New free registrations without a sponsor are assigned round-robin to active board members.
                                The <span class="queue-badge" style="font-size:10px;padding:1px 7px;">NEXT</span> badge shows who gets the next assignment.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Board Members Table ───────────────────────────────── --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users mr-1"></i> Board Members &amp; Activity</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size:13px;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Member</th>
                                            <th>Fill Dir.</th>
                                            <th>Assigned</th>
                                            <th>Last Assigned</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($boardMembers as $member)
                                            @php
                                                $stats      = $assignmentStats->get($member->user_id);
                                                $lastUser   = $lastAssignedUsers->get($member->user_id);
                                                $isNext     = $member->status && $member->user_id === $nextBoardMemberId;
                                                $fillPref   = $member->user->fill_preference ?? 'left';
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                    @if($isNext)
                                                        <br><span class="queue-badge mt-1">NEXT</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $member->user->name ?? 'N/A' }}</strong>
                                                    <br><small class="text-muted">{{ $member->user->connection ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <span class="fill-pref-badge {{ $fillPref === 'left' ? 'fill-pref-left' : 'fill-pref-right' }}">
                                                        <i class="fas fa-arrow-{{ $fillPref }}"></i>
                                                        {{ ucfirst($fillPref) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="stat-box">
                                                        <div class="stat-num">{{ $stats->total_assigned ?? 0 }}</div>
                                                        <div class="stat-label">users</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($lastUser && $stats)
                                                        <div class="last-assigned-text">
                                                            {{ $lastUser->last_user_name }}
                                                            <br><small>{{ $lastUser->last_user_connection }}</small>
                                                            <br><small>{{ \Carbon\Carbon::parse($stats->last_assigned_at)->diffForHumans() }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted" style="font-size:12px;">No assignments yet</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($member->status)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('delete_board_member', $member->id) }}"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Remove {{ $member->user->name ?? 'this member' }} from board members?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-users-slash fa-2x mb-2 d-block"></i>
                                                    No board members added yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /row --}}

            {{-- ── Recent Assignments ────────────────────────────────────── --}}
            @if($recentAssignments->isNotEmpty())
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-1"></i> Recent Free Registrations via Board Member Assignment
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>New User</th>
                                            <th>Connection ID</th>
                                            <th>Assigned Board Member</th>
                                            <th>Registered At</th>
                                            <th>Path</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAssignments as $i => $assignment)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $assignment->name }}</td>
                                                <td><code>{{ $assignment->connection }}</code></td>
                                                <td>{{ $boardMemberNames->get($assignment->assigned_board_member_id, 'Unknown') }}</td>
                                                <td>
                                                    {{ $assignment->created_at->format('d M Y, h:i A') }}
                                                    <small class="text-muted d-block">{{ $assignment->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm btn-view-path"
                                                        data-sponsor-id="{{ $assignment->assigned_board_member_id }}"
                                                        data-label="{{ $assignment->name }} ({{ $assignment->connection }})"
                                                        data-user-connection="{{ $assignment->connection }}"
                                                        title="View placement path">
                                                        <i class="fas fa-sitemap"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

{{-- ── Placement Path Modal ── --}}
<div class="modal fade" id="placementPathModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sitemap mr-1"></i>
                    Placement Path — <span id="ppUserConn"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div id="ppLoading" class="text-center p-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="ppContent" style="display:none;">
                    <div class="px-3 pt-3 pb-1">
                        <small class="text-muted">
                            Sponsor: <strong id="ppSponsorName"></strong>
                            &nbsp;|&nbsp;
                            Fill preference: <span id="ppPreference" class="badge badge-info"></span>
                        </small>
                    </div>
                    <div class="table-responsive px-3 pb-3">
                        <table class="table table-bordered table-sm mt-2 mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:70px;">Level</th>
                                    <th>Name</th>
                                    <th>Connection</th>
                                </tr>
                            </thead>
                            <tbody id="ppTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
const PLACEMENT_PATH_URL = '{{ route("board_members.placement_path") }}';

function openPlacementModal(sponsorId, label, targetConnection) {
    $('#ppUserConn').text(label);
    $('#ppLoading').show();
    $('#ppContent').hide();
    $('#placementPathModal').modal('show');

    $.get(PLACEMENT_PATH_URL, { sponsor_id: sponsorId }, function (res) {
        $('#ppSponsorName').text(res.sponsor.name + ' (' + res.sponsor.connection + ')');
        $('#ppPreference').text(res.preference.toUpperCase());

        const tbody = $('#ppTableBody').empty();
        for (var i = 0; i < res.path.length; i++) {
            var row = res.path[i];
            if (!row.vacant) {
                tbody.append(
                    '<tr><td>' + row.level + '</td>' +
                    '<td>' + row.name + '</td>' +
                    '<td><code>' + row.connection + '</code></td></tr>'
                );
                if (targetConnection && row.connection === targetConnection) {
                    break;
                }
            }
        }

        $('#ppLoading').hide();
        $('#ppContent').show();
    }).fail(function () {
        $('#ppLoading').html('<p class="text-danger p-3">Failed to load placement path.</p>');
    });
}

$(document).on('click', '.btn-view-path', function (e) {
    e.stopPropagation();
    openPlacementModal($(this).data('sponsor-id'), $(this).data('label'), $(this).data('user-connection'));
});

$(document).on('click', '.recent-assignment-row', function () {
    openPlacementModal($(this).data('sponsor-id'), $(this).data('user-conn') + ' — ' + $(this).data('user-name'));
});
</script>
@endsection
