@extends('Admin.admin_header')
@section('title', 'VISHWASTHA | My Binary Tree')
@section('content')

<style>
.binary-page-wrapper { padding: 20px; }
.binary-topbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
.tree-scroll-wrapper { overflow: hidden; padding: 30px 20px 20px; background: #f8f9fa; border-radius: 0 0 8px 8px; }
#treeScaler { transform-origin: top center; display: flex; justify-content: center; }
#treeCanvas { position: relative; flex-shrink: 0; }
#treeSvg { position: absolute; top: 0; left: 0; pointer-events: none; overflow: visible; }

.node-card { position: absolute; width: 348px; background: #fff; border: 2px solid #007bff; border-radius: 14px; padding: 12px 10px 10px; box-shadow: 0 2px 10px rgba(0,123,255,.15); text-align: center; z-index: 2; }
.node-card.root-node { border-color: #fd7e14; }
.node-img { width: 132px; height: 132px; border-radius: 50%; object-fit: cover; border: 4px solid #007bff; margin-bottom: 10px; cursor: pointer; }
.root-node .node-img { border-color: #fd7e14; }
.node-img-wrap { position: relative; display: inline-block; }
.has-more-badge { position: absolute; top: 50%; right: -24px; transform: translateY(-50%); background: #dc3545; color: #fff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 22px; cursor: pointer; box-shadow: 0 3px 10px rgba(220,53,69,.5); z-index: 5; transition: transform .15s, box-shadow .15s; }
.has-more-badge:hover { transform: translateY(-50%) scale(1.2); box-shadow: 0 5px 16px rgba(220,53,69,.7); }
.node-id   { font-size: 43px; font-weight: 700; color: #333; margin-bottom: 2px; }
.node-name { font-size: 43px; color: #555; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.node-lr   { font-size: 34px; color: #555; margin-bottom: 6px; display: flex; justify-content: center; gap: 18px; }
.node-lr .leg-side { font-weight: 700; font-size: 36px; margin-bottom: 2px; }
.node-lr .leg-basic   { color: #856404; background: #fff3cd; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.node-lr .leg-premium { color: #155724; background: #d4edda; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.leg-clickable { cursor: pointer; text-decoration: underline dotted; transition: opacity .15s; }
.leg-clickable:hover { opacity: .75; }
.node-leg    { display: flex; flex-direction: column; align-items: center; gap: 3px; }

.node-action-btn { width: 72px; height: 72px; border-radius: 10px; border: none; font-size: 36px; cursor: pointer; transition: opacity .2s, transform .2s; }
.node-action-btn:hover { opacity: .8; transform: scale(1.1); }
.btn-node-view    { background: #e8f4ff; color: #007bff; }
.btn-node-package { background: #eafaf1; color: #28a745; }
.node-actions { display: flex; justify-content: center; gap: 10px; margin-top: 8px; }

.vacant-node { position: absolute; width: 348px; background: #fff8f0; border: 2px dashed #fd7e14; border-radius: 14px; padding: 20px 10px; text-align: center; z-index: 2; cursor: pointer; transition: background .2s, transform .2s; }
.vacant-node:hover { background: #fff0dc; transform: translateY(-2px); }
.vacant-circle { width: 108px; height: 108px; border-radius: 50%; background: #fff3e0; border: 2px dashed #fd7e14; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 40px; color: #fd7e14; }
.vacant-label { font-size: 40px; font-weight: 700; color: #fd7e14; }
.vacant-slot  { font-size: 34px; color: #aaa; margin-top: 4px; }
.vacant-hint  { font-size: 28px; color: #fd7e14; margin-top: 6px; opacity: .7; }

.pkg-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin: 2px; border: 1px solid; }
.pkg-badge.basic   { background: #fff9e6; color: #856404; border-color: #ffc107; }
.pkg-badge.premium { background: #d4edda; color: #155724; border-color: #28a745; }
.pkg-badge.prime   { background: #fff3e0; color: #7a3300; border-color: #fd7e14; }

.user-search-result { cursor: pointer; padding: 8px 12px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px; }
.user-search-result:hover { background: #f8f9fa; }
.user-search-result img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
.search-results-list { max-height: 260px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; }

.migration-banner { background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; font-size: 13px; color: #856404; display: flex; align-items: center; gap: 10px; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>My Binary Tree</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('adminhome') }}">Home</a></li>
                        <li class="breadcrumb-item active">My Tree</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid binary-page-wrapper">

            @if(!$settings->migration_complete)
            <div class="migration-banner">
                <i class="fas fa-tools fa-lg"></i>
                <span><strong>Migration in progress:</strong> Click any vacant slot to place an existing team member into the binary tree. New user registration is temporarily disabled.</span>
            </div>
            @endif

            <div class="binary-topbar">
                @if($parentNode)
                    <button onclick="history.back()" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                @endif
                @if($currentNode && $currentNode->id !== $me->id)
                    <a href="{{ route('user.binary_tree') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-home"></i> My Root
                    </a>
                @endif
                <span class="text-muted small">Viewing: <strong>{{ $currentNode->name ?? '' }}</strong></span>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="tree-scroll-wrapper" id="treeWrapper">
                        <div id="treeScaler">
                            <div id="treeCanvas">
                                <svg id="treeSvg"></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Place User Modal --}}
<div class="modal fade" id="placeUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Place Member</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2">Placing in: <strong id="placementInfo">-</strong></p>

                <input type="hidden" id="placementParentId">
                <input type="hidden" id="placementPosition">

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" id="placeTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabExisting">
                            <i class="fas fa-search"></i> Place Existing Member
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabNewUser">
                            <i class="fas fa-user-plus"></i> Add New Member
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab 1: Place existing --}}
                    <div class="tab-pane fade show active" id="tabExisting">
                        <p class="text-info small"><i class="fas fa-info-circle"></i> Only unplaced members are shown.</p>
                        <input type="hidden" id="selectedUserId">
                        <input type="text" id="userSearchInput" class="form-control mb-1" placeholder="Search by name or ID…" autocomplete="off">
                        <div id="userSearchResults" class="search-results-list mb-2"></div>
                        <div id="userSelectedInfo" class="text-success font-weight-bold small mb-2" style="display:none;"></div>
                        <div id="placeSlotError" class="text-danger small mt-2" style="display:none;"></div>
                    </div>

                    {{-- Tab 2: Add new member --}}
                    <div class="tab-pane fade" id="tabNewUser">
                        <p class="text-warning small"><i class="fas fa-info-circle"></i> A new member will be registered and placed in this slot.</p>

                        {{-- Registration type --}}
                        <div class="d-flex align-items-center flex-wrap mb-3 p-2 bg-light rounded">
                            <strong class="mr-3 small">Registration type:</strong>
                            <div class="icheck-primary mr-3">
                                <input type="radio" id="regTypeWpan" name="reg_type" value="wpan" checked>
                                <label for="regTypeWpan">Without PAN Card</label>
                            </div>
                            <div class="icheck-primary mr-3">
                                <input type="radio" id="regTypeWithPan" name="reg_type" value="withpan">
                                <label for="regTypeWithPan">With PAN Card</label>
                            </div>
                            <div class="icheck-warning">
                                <input type="radio" id="regTypeTempTest" name="reg_type" value="temptest">
                                <label for="regTypeTempTest">Temp / Testing <span class="badge badge-warning ml-1" style="font-size:9px;vertical-align:middle;">DEV</span></label>
                            </div>
                        </div>

                        <div id="tempTestNotice" class="alert alert-warning py-2 mb-3 small" style="display:none;">
                            <i class="fas fa-flask mr-1"></i> <strong>Testing mode:</strong> Only PAN Card &amp; Sponsor ID needed — all other fields auto-filled with dummy data.
                        </div>

                        <form id="newUserForm">
                            <div class="row">
                                <div class="form-group col-md-6" id="fgName">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6" id="fgEmail">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6" id="fgPhone">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_no" class="form-control" maxlength="10" required>
                                </div>
                                <div class="form-group col-md-6" id="panCardField" style="display:none;">
                                    <label>PAN Card <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_card_no" id="panCardInput" class="form-control"
                                           oninput="this.value=this.value.toUpperCase()" placeholder="ABCDE1234F">
                                </div>
                                <div class="form-group col-md-6" id="fgPincode">
                                    <label>Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" maxlength="6" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sponsor ID <span class="text-danger">*</span></label>
                                    <input type="text" name="sponsor_id" id="userSponsorIdInput" class="form-control"
                                           value="{{ $me->connection }}" placeholder="Sponsor connection code" required autocomplete="off">
                                    <small id="userSponsorNamePreview" class="mt-1 d-block text-success" style="min-height:16px;"></small>
                                </div>
                                <div class="form-group col-md-6" id="fgPassword">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="newUserPassword" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePwd('newUserPassword',this)"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6" id="fgPasswordConfirm">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="newUserPasswordConfirm" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePwd('newUserPasswordConfirm',this)"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12" id="fgAddress">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnPlaceUser" disabled>
                    <i class="fas fa-check"></i> Place
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Package Modal (activate for any team member using logged-in user's own pins) --}}
<div class="modal fade" id="packageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updatePinForm" method="POST" action="{{ route('update_pin') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-box-open"></i> Activate Package</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pkgUserId" name="userid">
                    <p class="mb-2">Activating for: <strong id="pkgUserName">-</strong></p>

                    <div id="pkgCurrentSection" class="mb-3" style="display:none;">
                        <p class="font-weight-bold mb-1" style="font-size:13px;">Current Packages:</p>
                        <div id="pkgCurrentList"></div>
                    </div>

                    <div class="form-group">
                        <label>Select Package</label>
                        <select class="form-control" id="pkgPackageId" name="package_id" required>
                            <option value="">-- Choose Package --</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="pkgPinSection" style="display:none;">
                        <label>Available Pins</label>
                        <select class="form-control" id="pkgPinId" name="pin_id" required>
                            <option value="">-- Select package first --</option>
                        </select>
                    </div>
                    <div class="form-group" id="pkgProductSection" style="display:none;">
                        <label>Select Product</label>
                        <select class="form-control" id="pkgProductId" name="product_id" required>
                            <option value="">-- Select package first --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Activate</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
const IS_ADMIN   = false;
const MY_USER_ID = {{ $me->id }};
const RAW_TREE   = @json($binaryTree);
const CSRF       = '{{ csrf_token() }}';

const ROUTES = {
    userPackages:       '{{ route("admin.binary_tree.user_packages") }}',
    reload:             '{{ route("user.binary_tree") }}',
    transferUser:       '{{ route("admin.binary_tree.transfer_user") }}',
    searchUsers:        '{{ route("user.binary_tree.search_users") }}',
    checkSlots:         '{{ route("admin.binary_tree.check_slots") }}',
    legVolumeDetail:    '{{ route("admin.binary_tree.leg_volume_detail") }}',
    registerWithoutPan: '{{ route("register.store.wpan") }}',
    registerWithPan:    '{{ route("register.store") }}',
    sponsorLookup:      '{{ url("/get_user_name") }}',
};

const PKG_BADGE_CLASS = { basic_package: 'basic', premium_package: 'premium', prime_package: 'prime' };

// ── Layout constants ──────────────────────────────────────────────────────────
const NW = 408, NH = 620, VGAP = 160, MAX_LEVEL = 4;

function buildLayout(node, level, xStart, xEnd, isRoot, parentInfo) {
    if (!node || level > MAX_LEVEL) return [];
    const xMid = (xStart + xEnd) / 2;
    const y    = (level - 1) * (NH + VGAP);
    const hasUser = node && node.user;
    const entry = {
        x: xMid - NW / 2, y,
        user: hasUser ? node.user : null,
        isRoot,
        parent: parentInfo,
        vacantParentId: !hasUser ? (node.parent_id ?? null) : null,
        vacantPosition: !hasUser ? (node.position ?? null) : null,
    };
    const nodes = [entry];
    if (hasUser && level < MAX_LEVEL) {
        // When a slot is null from the server, create a vacant node placeholder so the slot renders
        const leftData  = node.left  ?? { user: null, parent_id: node.user.id, position: 'left',  left: null, right: null };
        const rightData = node.right ?? { user: null, parent_id: node.user.id, position: 'right', left: null, right: null };
        nodes.push(...buildLayout(leftData,  level+1, xStart, xMid, false, { id: node.user.id, side:'left' }));
        nodes.push(...buildLayout(rightData, level+1, xMid,  xEnd, false, { id: node.user.id, side:'right' }));
    }
    return nodes;
}

function reloadPage() { window.location.href = ROUTES.reload; }

function viewSubtree(e, userId) {
    e.stopPropagation();
    window.location.href = ROUTES.reload + '?node_id=' + userId;
}

// ── Package modal ─────────────────────────────────────────────────────────────
function openPackageModal(e, userId, userName) {
    e.stopPropagation();
    $('#pkgUserId').val(userId);
    $('#pkgUserName').text(userName);
    $('#pkgPackageId').val('');
    $('#pkgPinId').html('<option value="">-- Select package first --</option>');
    $('#pkgProductId').html('<option value="">-- Select package first --</option>');
    $('#pkgPinSection').hide();
    $('#pkgProductSection').hide();
    $('#pkgCurrentSection').hide();
    $('#pkgCurrentList').empty();

    // Show the target user's existing packages
    $.get(ROUTES.userPackages, { user_id: userId }, function (packages) {
        if (packages && packages.length) {
            const $list = $('#pkgCurrentList').empty();
            packages.forEach(function (p) {
                const cls = PKG_BADGE_CLASS[p.package_code] || 'other';
                $list.append('<span class="pkg-badge ' + cls + '"><i class="fas fa-box"></i> ' + p.name + ' <small style="opacity:.7;">— ₹' + p.amount + '</small></span>');
            });
            $('#pkgCurrentSection').show();
        }
    });

    $('#packageModal').modal('show');
}

$('#pkgPackageId').on('change', function () {
    const packageId = $(this).val();
    const userId    = $('#pkgUserId').val();
    if (!packageId || !userId) return;
    $('#pkgPinId').html('<option value="">Loading...</option>');
    $('#pkgProductId').html('<option value="">Loading...</option>');
    $('#pkgPinSection').show();
    $('#pkgProductSection').show();

    // Always fetch pins from the logged-in user (MY_USER_ID), not the target
    $.get('/get-available-pins', { package_id: packageId, user_id: MY_USER_ID }, function (res) {
        const $pins = $('#pkgPinId').empty().append('<option value="">-- Choose Pin --</option>');
        (res.pins || []).forEach(p => $pins.append('<option value="' + p.id + '">' + p.unique_id + '</option>'));
        if (!res.pins || !res.pins.length) $pins.append('<option value="">No pins available</option>');

        const $prods = $('#pkgProductId').empty().append('<option value="">-- Choose Product --</option>');
        (res.products || []).forEach(p => $prods.append('<option value="' + p.id + '">' + p.product_name + '</option>'));
    }).fail(function () { Swal.fire('Error', 'Failed to load pins.', 'error'); });
});

$('#updatePinForm').on('submit', function (e) {
    e.preventDefault();
    if (!$('#pkgPinId').val() || !$('#pkgProductId').val()) {
        Swal.fire('Warning', 'Please select a pin and product.', 'warning'); return;
    }
    $.ajax({
        url: $(this).attr('action'), type: 'POST', data: $(this).serialize(),
        success: function () {
            Swal.fire('Activated!', 'Package activated successfully.', 'success').then(reloadPage);
        },
        error: function () { Swal.fire('Error', 'Activation failed. Please try again.', 'error'); }
    });
});

// ── Place User modal ──────────────────────────────────────────────────────────
function openPlaceModal(parentId, position) {
    $('#placementParentId').val(parentId);
    $('#placementPosition').val(position);
    $('#placementInfo').text(position.charAt(0).toUpperCase() + position.slice(1) + ' slot');
    $('#selectedUserId').val('');
    $('#userSearchInput').val('');
    $('#userSearchResults').empty();
    $('#userSelectedInfo').hide();
    $('#placeSlotError').hide();
    $('#btnPlaceUser').prop('disabled', true).text('Place');
    // Reset to first tab
    $('#placeTabs a[href="#tabExisting"]').tab('show');
    $('#newUserForm')[0].reset();
    // Restore pre-filled sponsor and reset registration type UI
    $('#userSponsorIdInput').val('{{ $me->connection }}');
    $('#userSponsorNamePreview').text('');
    $('input[name="reg_type"][value="wpan"]').prop('checked', true);
    $('#panCardField').hide();
    $('#panCardInput').prop('required', false);
    $('#tempTestNotice').hide();
    $('#fgName,#fgEmail,#fgPhone,#fgPincode,#fgPassword,#fgPasswordConfirm,#fgAddress').show();
    $('#placeUserModal').modal('show');
}

// Enable Place button when switching to Add New Member tab
$('#placeTabs a[href="#tabNewUser"]').on('shown.bs.tab', function () {
    $('#btnPlaceUser').prop('disabled', false).html('<i class="fas fa-user-plus"></i> Add Member');
});
$('#placeTabs a[href="#tabExisting"]').on('shown.bs.tab', function () {
    const hasUser = !!$('#selectedUserId').val();
    $('#btnPlaceUser').prop('disabled', !hasUser).html('<i class="fas fa-check"></i> Place');
});

// Registration type toggle
const $normalFields = $('#fgName,#fgEmail,#fgPhone,#fgPincode,#fgPassword,#fgPasswordConfirm,#fgAddress');
$('input[name="reg_type"]').on('change', function () {
    const val      = $(this).val();
    const withPan  = (val === 'withpan');
    const tempTest = (val === 'temptest');
    $('#panCardField').toggle(withPan || tempTest);
    $('#panCardInput').prop('required', withPan || tempTest);
    $normalFields.toggle(!tempTest);
    $('#tempTestNotice').toggle(tempTest);
});

// Sponsor name preview
let sponsorDebounce;
$('#userSponsorIdInput').on('input', function () {
    clearTimeout(sponsorDebounce);
    const val = $(this).val().trim();
    if (!val) { $('#userSponsorNamePreview').text(''); return; }
    sponsorDebounce = setTimeout(function () {
        $.post(ROUTES.sponsorLookup, { user_name: val, _token: CSRF }, function (res) {
            if (res && res.name) {
                $('#userSponsorNamePreview').text('✓ ' + res.name).css('color', '#28a745');
            } else {
                $('#userSponsorNamePreview').text('Sponsor not found').css('color', '#dc3545');
            }
        }).fail(function () {
            $('#userSponsorNamePreview').text('');
        });
    }, 300);
});

function togglePwd(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

let searchDebounce;
$('#userSearchInput').on('input', function () {
    clearTimeout(searchDebounce);
    const q = $(this).val().trim();
    if (q.length < 2) { $('#userSearchResults').empty(); return; }
    searchDebounce = setTimeout(function () {
        $.get(ROUTES.searchUsers, { q }, function (data) {
            const $list = $('#userSearchResults').empty();
            if (!data.length) {
                $list.append('<div class="p-2 text-muted small">No unplaced members found.</div>');
                return;
            }
            data.forEach(function (u) {
                $('<div class="user-search-result">')
                    .html('<img src="' + u.image + '"><div><div style="font-size:13px;font-weight:600;">' + u.name + '</div><div style="font-size:11px;color:#888;">' + u.connection + '</div></div>')
                    .on('click', function () {
                        $('#selectedUserId').val(u.id);
                        $('#userSearchInput').val(u.name + ' — ' + u.connection);
                        $list.empty();
                        $('#userSelectedInfo').text('Selected: ' + u.name + ' (' + u.connection + ')').show();
                        $('#btnPlaceUser').prop('disabled', false);
                        $('#placeSlotError').hide();
                    })
                    .appendTo($list);
            });
        });
    }, 250);
});

$('#btnPlaceUser').on('click', function () {
    const $btn     = $(this);
    const parentId = $('#placementParentId').val();
    const position = $('#placementPosition').val();
    const activeTab = $('#placeTabs .nav-link.active').attr('href');

    if ($btn.prop('disabled')) return;

    if (activeTab === '#tabExisting') {
        // ── Transfer existing member ──────────────────────────────────
        const userId = $('#selectedUserId').val();
        if (!userId) { Swal.fire('Warning', 'Please select a team member first.', 'warning'); return; }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Placing…');

        $.ajax({
            url: ROUTES.transferUser,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            contentType: 'application/json',
            data: JSON.stringify({ user_id: userId, parent_id: parentId, position: position }),
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Placed!', res.message, 'success').then(function () {
                        $('#placeUserModal').modal('hide');
                        reloadPage();
                    });
                } else {
                    $('#placeSlotError').text(res.message).show();
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Place');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Place');
            }
        });

    } else {
        // ── Add new member ────────────────────────────────────────────
        const regType  = $('input[name="reg_type"]:checked').val();
        const usePan   = (regType === 'withpan');
        const tempTest = (regType === 'temptest');

        $('#panCardInput').prop('required', usePan || tempTest);

        // Auto-fill dummy data for temp/testing mode
        if (tempTest) {
            const ts = Date.now();
            const $f = $('#newUserForm');
            $f.find('[name="name"]').val('TestUser_' + ts);
            $f.find('[name="email"]').val('test_' + ts + '@test.com');
            $f.find('[name="phone_no"]').val('9999999999');
            $f.find('[name="pincode"]').val('560001');
            $f.find('[name="password"]').val('Test@1234');
            $f.find('[name="password_confirmation"]').val('Test@1234');
            $f.find('[name="address"]').val('Test Address, Bangalore');
        }

        const form = document.getElementById('newUserForm');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Registering…');

        const data = new FormData(form);
        data.append('parent_id', parentId);
        data.append('position', position);
        if (!usePan && !tempTest) data.delete('pan_card_no');

        const registerUrl = (usePan || tempTest) ? ROUTES.registerWithPan : ROUTES.registerWithoutPan;

        $.ajax({
            url: registerUrl,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            data: data,
            processData: false,
            contentType: false,
            success: function (res) {
                try { res = typeof res === 'string' ? JSON.parse(res) : res; } catch(e) {}
                if (res.status === 'success') {
                    $('#placeUserModal').modal('hide');
                    Swal.fire({
                        title: 'Member Added!',
                        html: '<div class="text-left mt-2">' +
                              '<p><strong>User ID:</strong> <span class="text-primary" style="font-size:1.1em;">' + res.connection + '</span></p>' +
                              '<p><strong>Password:</strong> <span class="text-danger" style="font-size:1.1em;">' + res.password + '</span></p>' +
                              '</div>',
                        icon: 'success',
                        confirmButtonText: 'Close',
                        allowOutsideClick: false
                    }).then(reloadPage);
                } else if (res.status === 'validation') {
                    let msg = '';
                    $.each(res.errors, function (k, v) { msg += v[0] + '<br>'; });
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: msg });
                    $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Add Member');
                } else {
                    Swal.fire('Error', res.message || 'Registration failed.', 'error');
                    $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Add Member');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                $btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Add Member');
            }
        });
    }
});

// ── Tree render ───────────────────────────────────────────────────────────────
const TOTAL_W  = Math.pow(2, MAX_LEVEL - 1) * NW * 1.2;
const allNodes = buildLayout(RAW_TREE, 1, 0, TOTAL_W, true, null);

const canvas   = document.getElementById('treeCanvas');
const svg      = document.getElementById('treeSvg');
canvas.style.width  = TOTAL_W + 'px';
canvas.style.height = (MAX_LEVEL * (NH + VGAP)) + 'px';
svg.setAttribute('width',  TOTAL_W);
svg.setAttribute('height', MAX_LEVEL * (NH + VGAP));

const byId = {};

allNodes.forEach(function (n) {
    const el = document.createElement('div');
    if (n.user) {
        const hasMore  = n.user.has_more ?? false;
        const pkgColor = n.user.package_color || null;
        el.className   = 'node-card' + (n.isRoot ? ' root-node' : '');
        const imgSrc   = n.user.user_image ? '/' + n.user.user_image : '/assets/dist/img/images.jpg';
        const imgStyle = pkgColor ? ' style="border-color:' + pkgColor + '!important;box-shadow:0 0 0 3px ' + pkgColor + '55;cursor:pointer;"' : ' style="cursor:pointer;"';
        const imgTag   = '<img src="' + imgSrc + '" onerror="this.src=\'/assets/dist/img/images.jpg\'" class="node-img"' + imgStyle + ' alt="user" onclick="viewSubtree(event,' + n.user.id + ')" title="View ' + (n.user.name||'').replace(/'/g,"&#39;").replace(/"/g,'&quot;') + '\'s tree">';
        const imgHtml  = hasMore
            ? '<div class="node-img-wrap">' + imgTag + '<div class="has-more-badge" onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-chevron-down"></i></div></div>'
            : imgTag;

        const safeName = (n.user.name || '').replace(/'/g, "\\'");
        const pkgBtn   = '<button class="node-action-btn btn-node-package" title="Activate package (using your pins)" onclick="openPackageModal(event,' + n.user.id + ',\'' + safeName + '\')"><i class="fas fa-box-open"></i></button>';

        el.innerHTML =
            imgHtml +
            '<div class="node-id">'  + (n.user.connection || '') + '</div>' +
            '<div class="node-name">' + (n.user.name || '') + '</div>' +
            '<div class="node-lr">' +
                '<div class="node-leg">' +
                    '<span class="leg-side">L: ' + (n.user.left_count ?? 0) + '</span>' +
                    '<span class="leg-basic leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'left\',\'basic_package\')">BSV: ₹' + (n.user.left_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '<span class="leg-premium leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'left\',\'premium_package\')">PSV: ₹' + (n.user.left_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                '</div>' +
                '<div class="node-leg">' +
                    '<span class="leg-side">R: ' + (n.user.right_count ?? 0) + '</span>' +
                    '<span class="leg-basic leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'right\',\'basic_package\')">BSV: ₹' + (n.user.right_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '<span class="leg-premium leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'right\',\'premium_package\')">PSV: ₹' + (n.user.right_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="node-actions">' +
                '<button class="node-action-btn btn-node-view" title="View subtree" onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-sitemap"></i></button>' +
                pkgBtn +
            '</div>';

        byId[n.user.id] = n;
    } else {
        const pid = n.vacantParentId, pos = n.vacantPosition;
        el.className = 'vacant-node';
        el.setAttribute('onclick', 'openPlaceModal(' + pid + ',\'' + pos + '\')');
        el.innerHTML =
            '<div class="vacant-circle"><i class="fas fa-plus"></i></div>' +
            '<div class="vacant-label">VACANT</div>' +
            '<div class="vacant-slot">' + (pos ? pos.charAt(0).toUpperCase() + pos.slice(1) + ' slot' : '') + '</div>' +
            '<div class="vacant-hint"><i class="fas fa-hand-pointer"></i> Tap to place member</div>';
    }
    el.style.left = n.x + 'px';
    el.style.top  = n.y + 'px';
    canvas.appendChild(el);
});

// Draw connectors
allNodes.forEach(function (p) {
    if (!p.user) return;
    allNodes.forEach(function (c) {
        if (!c.parent || c.parent.id !== p.user.id) return;
        const px = p.x + NW/2, py = p.y + NH;
        const cx = c.x + NW/2, cy = c.y;
        const line = document.createElementNS('http://www.w3.org/2000/svg','path');
        const my = (py + cy) / 2;
        line.setAttribute('d', 'M ' + px + ' ' + py + ' C ' + px + ' ' + my + ', ' + cx + ' ' + my + ', ' + cx + ' ' + cy);
        line.setAttribute('stroke', c.parent.side === 'left' ? '#007bff' : '#28a745');
        line.setAttribute('stroke-width', '3');
        line.setAttribute('fill', 'none');
        line.setAttribute('stroke-dasharray', '6,3');
        svg.appendChild(line);
    });
});

// Scale tree
function scaleTree() {
    const wrapper = document.getElementById('treeWrapper');
    const scale   = Math.min(1, (wrapper.clientWidth - 40) / TOTAL_W);
    document.getElementById('treeScaler').style.transform = 'scale(' + scale + ')';
    wrapper.style.height = Math.ceil(MAX_LEVEL * (NH + VGAP) * scale + 60) + 'px';
}
scaleTree();
$(window).on('resize', function () { setTimeout(scaleTree, 50); });

function showLegDetail(e, userId, side, packageCode) {
    e.stopPropagation();
    const label     = packageCode === 'basic_package' ? 'BSV (Basic)' : 'PSV (Premium)';
    const sideLabel = side === 'left' ? 'Left' : 'Right';
    $('#legDetailTitle').text(sideLabel + ' Leg — ' + label + ' Breakdown');
    $('#legDetailBody').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#legDetailModal').modal('show');

    $.get(ROUTES.legVolumeDetail, { user_id: userId, side: side, package_code: packageCode }, function (res) {
        if (!res.rows || !res.rows.length) {
            $('#legDetailBody').html('<p class="text-muted text-center py-3">No activations found in this leg.</p>');
            return;
        }
        let html = '<table class="table table-sm table-bordered">' +
            '<thead class="thead-dark"><tr>' +
            '<th>#</th><th>ID</th><th>Name</th><th>Package</th><th>Activated On</th><th class="text-right">BV</th>' +
            '</tr></thead><tbody>';
        res.rows.forEach(function (r, i) {
            html += '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td class="text-primary font-weight-bold">' + r.connection + '</td>' +
                '<td>' + r.name + '</td>' +
                '<td>' + r.package_name + '</td>' +
                '<td>' + (r.activated_at ? r.activated_at.substring(0, 10) : '') + '</td>' +
                '<td class="text-right font-weight-bold">₹' + Number(r.bv).toLocaleString('en-IN') + '</td>' +
                '</tr>';
        });
        html += '</tbody><tfoot><tr class="table-info font-weight-bold">' +
            '<td colspan="5" class="text-right">Total BV</td>' +
            '<td class="text-right">₹' + Number(res.total_bv).toLocaleString('en-IN') + '</td>' +
            '</tr></tfoot></table>';
        $('#legDetailBody').html(html);
    }).fail(function () {
        $('#legDetailBody').html('<p class="text-danger text-center">Failed to load data.</p>');
    });
}
</script>

{{-- Leg volume detail modal --}}
<div class="modal fade" id="legDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="legDetailTitle">Leg Volume Breakdown</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="legDetailBody"></div>
        </div>
    </div>
</div>

@endsection
