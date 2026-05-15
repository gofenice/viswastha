@extends('Admin.admin_header')
@section('title', 'VISHWASTHA | Binary Tree Migration')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<style>
.binary-page-wrapper {
    padding: 20px;
}

/* ── Top bar ── */
.binary-topbar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.migration-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}
.migration-badge.pending  { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
.migration-badge.complete { background: #d4edda; color: #155724; border: 1px solid #28a745; }

/* ── Tree container ── */
.tree-scroll-wrapper {
    overflow: hidden;
    padding: 30px 20px 20px;
    background: #f8f9fa;
    border-radius: 0 0 8px 8px;
    transition: height .2s ease;
}

#treeScaler {
    transform-origin: top center;
    display: flex;
    justify-content: center;
}

#treeCanvas {
    position: relative;
    flex-shrink: 0;
}

#treeSvg {
    position: absolute;
    top: 0; left: 0;
    pointer-events: none;
    overflow: visible;
}

/* ── Node card ── */
.node-card {
    position: absolute;
    width: 348px;
    background: #fff;
    border: 2px solid #007bff;
    border-radius: 14px;
    padding: 12px 10px 10px;
    box-shadow: 0 2px 10px rgba(0,123,255,.15);
    cursor: pointer;
    transition: box-shadow .2s, transform .2s;
    text-align: center;
    z-index: 2;
}
.node-card:hover { box-shadow: 0 5px 18px rgba(0,123,255,.3); transform: translateY(-2px); }
.node-card.root-node { border-color: #fd7e14; box-shadow: 0 2px 12px rgba(253,126,20,.25); }

.node-img {
    width: 132px;
    height: 132px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #adb5bd;
    margin-bottom: 10px;
}
.root-node .node-img { border-color: #fd7e14; }

/* Multi-color package ring */
.pkg-ring {
    display: inline-block;
    border-radius: 50%;
    padding: 8px;
    margin-bottom: 10px;
    line-height: 0;
}
.pkg-ring .node-img {
    border: none !important;
    box-shadow: none !important;
    margin-bottom: 0;
}

.node-id   { font-size: 36px; color: #007bff; font-weight: 700; margin-bottom: 6px; }
.node-name { font-size: 43px; color: #222; font-weight: 600; margin-bottom: 12px; word-break: break-word; line-height: 1.35; }
.node-lr   { font-size: 34px; color: #555; margin-bottom: 6px; display: flex; justify-content: center; gap: 18px; }
.node-lr .leg-side { font-weight: 700; font-size: 36px; margin-bottom: 2px; }
.node-lr .leg-basic   { color: #856404; background: #fff3cd; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.node-lr .leg-premium { color: #155724; background: #d4edda; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.node-leg    { display: flex; flex-direction: column; align-items: center; gap: 3px; }
.leg-clickable { cursor: pointer; text-decoration: underline dotted; transition: opacity .15s; }
.leg-clickable:hover { opacity: .75; }

/* ── Node action icons ── */
.node-actions {
    display: flex;
    justify-content: center;
    gap: 6px;
    border-top: 1px solid #f0f0f0;
    padding-top: 7px;
    margin-top: 2px;
}
.node-action-btn {
    width: 72px;
    height: 72px;
    border-radius: 12px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    cursor: pointer;
    transition: opacity .15s, transform .15s;
}
.node-action-btn:hover { opacity: .8; transform: scale(1.1); }
.btn-node-view    { background: #e8f4ff; color: #007bff; }
.btn-node-move    { background: #fff8e1; color: #fd7e14; }
.btn-node-delete  { background: #fdecea; color: #dc3545; }
.btn-node-package { background: #eafaf1; color: #28a745; }

/* ── Has-more badge (right side of profile image) ── */
.node-img-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 10px;
}
.has-more-badge {
    position: absolute;
    right: -24px;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #dc3545;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(220,53,69,.5);
    transition: transform .15s, box-shadow .15s;
    z-index: 4;
}
.has-more-badge:hover { transform: translateY(-50%) scale(1.2); box-shadow: 0 5px 16px rgba(220,53,69,.7); }

/* ── Vacant node ── */
.vacant-node {
    position: absolute;
    width: 348px;
    background: #fff8f0;
    border: 2px dashed #fd7e14;
    border-radius: 14px;
    padding: 14px 10px;
    cursor: pointer;
    transition: background .2s, transform .2s;
    text-align: center;
    z-index: 2;
}
.vacant-node:hover { background: #fff0dc; transform: translateY(-2px); }
.vacant-circle {
    width: 108px;
    height: 108px;
    border-radius: 50%;
    border: 3px solid #fd7e14;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fd7e14;
    font-size: 43px;
}
.vacant-label { font-size: 38px; font-weight: 700; color: #fd7e14; margin-bottom: 6px; }
.vacant-slot  { font-size: 31px; color: #aaa; }

/* ── No-root placeholder ── */
.no-root-box {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}
.no-root-box i { font-size: 60px; color: #dee2e6; margin-bottom: 16px; }

/* ── Current package badges in modal ── */
.pkg-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin: 3px 3px 3px 0;
}
.pkg-badge.basic   { background: #fff8e1; color: #856404; border: 1px solid #ffc107; }
.pkg-badge.premium { background: #d4edda; color: #155724; border: 1px solid #28a745; }
.pkg-badge.prime   { background: #fff3e0; color: #7a3300; border: 1px solid #fd7e14; }
.pkg-badge.other   { background: #e9ecef; color: #495057; border: 1px solid #adb5bd; }

/* ── Account type badges on tree nodes ── */
.acct-badge {
    display: inline-block;
    font-size: 30px !important;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 10px;
    margin: 3px 0 4px;
    letter-spacing: 0.3px;
}
.acct-mother    { background: #d4edda; color: #155724; border: 1px solid #28a745; }
.acct-privilege { background: #cce5ff; color: #004085; border: 1px solid #004085; }
.acct-child     { background: #e2e3e5; color: #383d41; border: 1px solid #6c757d; }
.acct-nopan     { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }

/* ── Search input ── */
#transferSearch { margin-bottom: 10px; }
.user-search-result {
    cursor: pointer;
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.user-search-result:hover { background: #f8f9fa; }
.user-search-result img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
.search-results-list { max-height: 280px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; }

/* ── Topbar quick search ── */
.topbar-search-wrap {
    position: relative;
    margin-left: auto;
}
.topbar-search-wrap input {
    width: 240px;
    border-radius: 20px;
    padding: 5px 14px 5px 36px;
    border: 1px solid #ced4da;
    font-size: 13px;
    outline: none;
}
.topbar-search-wrap input:focus { border-color: #80bdff; box-shadow: 0 0 0 2px rgba(0,123,255,.2); }
.topbar-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 13px;
    pointer-events: none;
}
.topbar-search-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    right: 0;
    width: 300px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,.12);
    z-index: 9999;
    max-height: 320px;
    overflow-y: auto;
    display: none;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Binary Tree <small class="text-muted">— Admin Migration</small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Binary Tree</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid binary-page-wrapper">

            {{-- ── Top action bar ── --}}
            <div class="binary-topbar">
                <span class="migration-badge {{ $settings->migration_complete ? 'complete' : 'pending' }}">
                    <i class="fas {{ $settings->migration_complete ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $settings->migration_complete ? 'Migration Complete' : 'Migration In Progress' }}
                </span>

                {{-- Back navigation --}}
                @if(!$isGlobalRoot && $currentNode)
                    <button onclick="history.back()" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                @endif

                @if($currentNode && !$isGlobalRoot)
                    <a href="{{ route('admin.binary_tree') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-home"></i> Global Root
                    </a>
                @endif

                @if(!$settings->migration_complete)
                    @if(!$settings->root_user_id)
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#setRootModal">
                            <i class="fas fa-sitemap"></i> Set Root User
                        </button>
                    @else
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#setRootModal">
                            <i class="fas fa-edit"></i> Change Root
                        </button>
                    @endif

                    @if($settings->root_user_id)
                        <button class="btn btn-success btn-sm" id="btnCompleteMigration">
                            <i class="fas fa-flag-checkered"></i> Mark Migration Complete
                        </button>
                    @endif
                @endif

                @if($currentNode)
                    <span class="text-muted small" style="font-size:12px;">
                        Viewing: <strong>{{ $currentNode->name }}</strong> ({{ $currentNode->connection }})
                    </span>
                @endif

                {{-- Quick search --}}
                @if($settings->root_user_id)
                <div class="topbar-search-wrap {{ $currentNode ? '' : 'ml-auto' }}">
                    <i class="fas fa-search topbar-search-icon"></i>
                    <input type="text" id="treeQuickSearch" placeholder="Search by ID or name…" autocomplete="off">
                    <div id="treeQuickSearchDropdown" class="topbar-search-dropdown"></div>
                </div>
                @endif
            </div>

            {{-- ── Tree panel ── --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="tree-scroll-wrapper">
                        @if(!$settings->root_user_id)
                            <div class="no-root-box">
                                <div><i class="fas fa-sitemap"></i></div>
                                <h4>No Root User Set</h4>
                                <p>Click <strong>"Set Root User"</strong> above to begin building the binary tree.</p>
                            </div>
                        @else
                            <div id="treeScaler">
                                <div id="treeCanvas">
                                    <svg id="treeSvg"></svg>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- ══════════════════════════════════════════════════════
     Modal: Set Root User
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="setRootModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-sitemap"></i> Set Root User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Search and select the user who will be the <strong>top node</strong> of the binary tree.</p>
                <input type="text" id="rootSearch" class="form-control mb-2" placeholder="Search by name or ID...">
                <div id="rootSearchResults" class="search-results-list"></div>
                <input type="hidden" id="selectedRootUserId">
                <div id="rootSelectedInfo" class="mt-2 text-success font-weight-bold" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-warning" id="btnSetRoot">Set as Root</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     Modal: Place User (Transfer or New)
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="placeUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Place User</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Placing in: <strong id="placementInfo">-</strong>
                </p>
                <input type="hidden" id="placementParentId">
                <input type="hidden" id="placementPosition">

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" id="placeTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tabTransfer">
                            <i class="fas fa-exchange-alt"></i> Transfer Existing User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tabNewUser">
                            <i class="fas fa-user-plus"></i> Add New User
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab 1: Transfer existing user --}}
                    <div class="tab-pane fade show active" id="tabTransfer">
                        <p class="text-info small"><i class="fas fa-info-circle"></i> No points will be calculated for transferred users.</p>
                        <input type="text" id="transferSearch" class="form-control" placeholder="Search by name or ID...">
                        <div id="transferSearchResults" class="search-results-list mt-1"></div>
                        <input type="hidden" id="selectedTransferUserId">
                        <div id="transferSelectedInfo" class="mt-2 text-success font-weight-bold" style="display:none;"></div>
                    </div>

                    {{-- Tab 2: Add new user --}}
                    <div class="tab-pane fade" id="tabNewUser">
                        <p class="text-warning small"><i class="fas fa-info-circle"></i> Points will be calculated for new users.</p>

                        {{-- Registration type radio --}}
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                            <strong class="mr-3 small">Registration type:</strong>
                            <div class="icheck-primary mr-3">
                                <input type="radio" id="regTypeWpan" name="reg_type" value="wpan" checked>
                                <label for="regTypeWpan">Without PAN Card</label>
                            </div>
                            <div class="icheck-primary">
                                <input type="radio" id="regTypeWithPan" name="reg_type" value="withpan">
                                <label for="regTypeWithPan">With PAN Card</label>
                            </div>
                        </div>

                        <form id="newUserForm">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_no" class="form-control" maxlength="10" required>
                                </div>

                                {{-- PAN Card field — shown only for "With PAN" --}}
                                <div class="form-group col-md-6" id="panCardField" style="display:none;">
                                    <label>PAN Card <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_card_no" id="panCardInput" class="form-control"
                                           oninput="this.value=this.value.toUpperCase()" placeholder="ABCDE1234F">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control" maxlength="6" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sponsor ID <span class="text-danger">*</span></label>
                                    <input type="text" name="sponsor_id" id="sponsorIdInput" class="form-control" placeholder="Sponsor connection code" required autocomplete="off">
                                    <small id="sponsorNamePreview" class="mt-1 d-block" style="min-height:18px;"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="regPassword" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePwd('regPassword', this)"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePwd('regPasswordConfirm', this)"><i class="fas fa-eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btnPlaceUser">Place User</button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     Modal: Activate Package
════════════════════════════════════════════════════════ --}}
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
                    <input type="hidden" id="pkgNodeId" name="node_id">

                    <p class="mb-2">Activating for: <strong id="pkgUserName">-</strong></p>

                    {{-- PAN Card step (shown when user has no PAN and no package) --}}
                    <div id="panStepSection" style="display:none;">
                        <div class="alert alert-warning py-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            This user has no PAN card. Please assign one before activating a package.
                        </div>
                        <div class="form-group mb-1">
                            <label>PAN Card Number <span class="text-danger">*</span></label>
                            <input type="text" id="panStepInput" class="form-control text-uppercase"
                                   placeholder="e.g. ABCDE1234F" maxlength="10"
                                   oninput="this.value=this.value.toUpperCase()">
                            <small id="panStepMsg" class="mt-1 d-block"></small>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="panStepVerifyBtn">
                            <i class="fas fa-check"></i> Verify &amp; Continue
                        </button>
                    </div>

                    {{-- Normal package/pin section (hidden until PAN step complete) --}}
                    <div id="panStepDone" style="display:none;">
                        <div class="alert alert-success py-2 mb-2">
                            <i class="fas fa-check-circle"></i> PAN assigned — <strong id="panStepAssignedLabel"></strong>
                        </div>
                    </div>

                    <div id="pkgMainSection">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    {{-- Admin: choose whose pin to use --}}
                    <div class="form-group">
                        <label>Use pin from <span class="text-danger">*</span></label>
                        <select class="form-control" id="pkgPinOwnerDropdown">
                            <option value="">-- Select pin owner --</option>
                            @foreach($allUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->connection }} — {{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Current packages --}}
                    <div id="pkgCurrentSection" class="mb-3" style="display:none;">
                        <p class="font-weight-bold mb-1" style="font-size:13px;">Current Packages:</p>
                        <div id="pkgCurrentList"></div>
                    </div>

                    <div class="form-group" id="pkgPackageSection" @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') style="display:none;" @endif>
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
                    </div>{{-- /pkgMainSection --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Activate</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     Modal: Move User
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="moveUserModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-arrows-alt"></i> Move User</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-1">Moving: <strong id="moveUserName">-</strong></p>
                <input type="hidden" id="moveUserId">

                <hr>
                <p class="font-weight-bold mb-1">Select target parent:</p>
                <input type="text" id="moveTargetSearch" class="form-control mb-1" placeholder="Search by name or ID...">
                <div id="moveTargetResults" class="search-results-list mb-2"></div>
                <input type="hidden" id="moveTargetId">
                <div id="moveTargetInfo" class="text-success font-weight-bold small mb-2" style="display:none;"></div>

                <div id="moveSlotSection" style="display:none;">
                    <p class="font-weight-bold mb-1">Select position:</p>
                    <div class="d-flex gap-3" id="moveSlotOptions"></div>
                </div>

                <div id="moveSlotError" class="text-danger small mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-warning" id="btnConfirmMove" disabled>
                    <i class="fas fa-arrows-alt"></i> Move
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
// ── Select2 for user dropdown in package modal ────────────────────────────────
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
$(function () {
    $('#pkgPinOwnerDropdown').select2({
        theme: 'bootstrap4',
        placeholder: '-- Search by name or ID --',
        allowClear: true,
        dropdownParent: $('#packageModal'),
        width: '100%',
    });
});
@endif

// ── Tree data from server ─────────────────────────────────────────────────────
const RAW_TREE = @json($binaryTree);

// ── Tree layout constants ─────────────────────────────────────────────────────
const NW        = 408;  // node width
const NH        = 620;  // node height
const VGAP      = 160;  // vertical gap between levels
const HGAP      = 40;   // min horizontal gap between nodes
const MAX_LEVEL = 4;    // maximum levels to display

// ── Build a flat list of positioned nodes from the tree ───────────────────────
function buildLayout(node, level, slotX, slotWidth, isRoot, parentUserId) {
    if (!node && !parentUserId) return [];
    const nodes = [];
    const x = slotX + slotWidth / 2 - NW / 2;
    const y = (level - 1) * (NH + VGAP);

    if (!node) {
        // Shouldn't happen at root; handled below
        return nodes;
    }

    const hasUser = node.user != null;
    nodes.push({
        level,
        x, y,
        slotX, slotWidth,
        user: hasUser ? node.user : null,
        parentId: hasUser ? node.user.id : null,
        isRoot,
        // for vacant:
        vacantParentId: !hasUser ? (node.parent_id ?? null) : null,
        vacantPosition: !hasUser ? (node.position  ?? null) : null,
    });

    // Only recurse into children if we haven't reached the display limit
    if (hasUser && level < MAX_LEVEL) {
        const halfSlot = slotWidth / 2;
        const leftData  = node.left  ?? { user: null, parent_id: node.user.id, position: 'left',  left: null, right: null };
        const rightData = node.right ?? { user: null, parent_id: node.user.id, position: 'right', left: null, right: null };
        nodes.push(...buildLayout(leftData,  level + 1, slotX,            halfSlot, false, node.user.id));
        nodes.push(...buildLayout(rightData, level + 1, slotX + halfSlot, halfSlot, false, node.user.id));
    }

    return nodes;
}

// ── Draw elbow connector line in SVG ─────────────────────────────────────────
function drawConnector(svg, px, py, cx, cy) {
    const parentBottomX = px + NW / 2;
    const parentBottomY = py + NH;
    const childTopX     = cx + NW / 2;
    const childTopY     = cy;
    const midY = parentBottomY + VGAP / 2;

    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    const d = `M ${parentBottomX} ${parentBottomY}
               L ${parentBottomX} ${midY}
               L ${childTopX}     ${midY}
               L ${childTopX}     ${childTopY}`;
    path.setAttribute('d', d);
    path.setAttribute('stroke', '#dc3545');
    path.setAttribute('stroke-width', '2');
    path.setAttribute('fill', 'none');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');
    svg.appendChild(path);
}

// ── Dashed downward stub below leaf nodes ─────────────────────────────────────
function drawStub(svg, nx, ny) {
    const x = nx + NW / 2;
    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    line.setAttribute('x1', x);
    line.setAttribute('y1', ny + NH);
    line.setAttribute('x2', x);
    line.setAttribute('y2', ny + NH + 24);
    line.setAttribute('stroke', '#bbb');
    line.setAttribute('stroke-width', '2');
    line.setAttribute('stroke-dasharray', '4,4');
    svg.appendChild(line);
}

// ── Render tree ───────────────────────────────────────────────────────────────
function renderBinaryTree() {
    if (!RAW_TREE) return;

    const LEAVES    = Math.pow(2, MAX_LEVEL - 1);   // 8
    const SLOT_W    = NW + HGAP;
    const TOTAL_W   = LEAVES * SLOT_W;
    const TOTAL_H   = MAX_LEVEL * (NH + VGAP) + 70; // extra for has-more arrows

    const canvas = document.getElementById('treeCanvas');
    const svg    = document.getElementById('treeSvg');
    canvas.style.width  = TOTAL_W + 'px';
    canvas.style.height = TOTAL_H + 'px';
    svg.setAttribute('width',  TOTAL_W);
    svg.setAttribute('height', TOTAL_H);

    const allNodes = buildLayout(RAW_TREE, 1, 0, TOTAL_W, true, null);

    // Index nodes by level+position for connector lookup
    const byId = {};

    allNodes.forEach(function (n) {
        const el = document.createElement('div');
        if (n.user) {
            const hasMore  = n.user.has_more ?? false;
            const colors = (n.user.package_colors && n.user.package_colors.length)
                ? n.user.package_colors
                : (n.user.package_color ? [n.user.package_color] : []);
            el.className = 'node-card' + (n.isRoot ? ' root-node' : '');
            const imgSrc = n.user.user_image
                ? '/' + n.user.user_image
                : '/assets/dist/img/images.jpg';
            const imgEl = '<img src="' + imgSrc + '" onerror="this.src=\'/assets/dist/img/images.jpg\'" class="node-img" style="cursor:pointer;" alt="user" onclick="viewSubtree(event,' + n.user.id + ')" title="View ' + (n.user.name||'').replace(/'/g,"&#39;").replace(/"/g,'&quot;') + '\'s tree">';
            let imgTag;
            if (colors.length === 0) {
                imgTag = imgEl;
            } else if (colors.length === 1) {
                imgTag = '<div class="pkg-ring" style="background:' + colors[0] + ';cursor:pointer;">' + imgEl + '</div>';
            } else {
                const stops = colors.map(function(c, i) {
                    const s = (i / colors.length * 100).toFixed(1) + '%';
                    const e = ((i + 1) / colors.length * 100).toFixed(1) + '%';
                    return c + ' ' + s + ' ' + e;
                }).join(', ');
                imgTag = '<div class="pkg-ring" style="background:conic-gradient(' + stops + ');cursor:pointer;">' + imgEl + '</div>';
            }
            const imgHtml = hasMore
                ? '<div class="node-img-wrap">' + imgTag + '<div class="has-more-badge" onclick="viewSubtree(event,' + n.user.id + ')" title="Has children — click to view"><i class="fas fa-chevron-down"></i></div></div>'
                : imgTag;
            const motherId = n.user.mother_id ?? 1;
            const hasPan   = n.user.pan_card_no && n.user.pan_card_no.trim() !== '' && n.user.pan_card_no.trim().toUpperCase() !== 'STORE';
            const acctBadge = !hasPan
                ? '<span class="acct-badge acct-nopan">No PAN Card</span>'
                : (motherId == 1
                    ? '<span class="acct-badge acct-mother">Mother ID</span>'
                    : (motherId == 2
                        ? '<span class="acct-badge acct-privilege">Privilege 1</span>'
                        : (motherId == 3
                            ? '<span class="acct-badge acct-privilege">Privilege 2</span>'
                            : '<span class="acct-badge acct-child">Child ID</span>')));
            el.innerHTML =
                imgHtml +
                '<div class="node-id">'  + (n.user.connection || '') + '</div>' +
                '<div class="node-name">' + (n.user.name || '')       + '</div>' +
                acctBadge +
                '<div class="node-lr">' +
                    '<div class="node-leg">' +
                        '<span class="leg-side">L: ' + (n.user.left_count ?? 0) + '</span>' +
                        '<span class="leg-basic leg-clickable"  onclick="showLegDetail(event,' + n.user.id + ',\'left\',\'basic_package\')">BSV: ₹' + (n.user.left_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                        '<span class="leg-premium leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'left\',\'premium_package\')">PSV: ₹' + (n.user.left_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '</div>' +
                    '<div class="node-leg">' +
                        '<span class="leg-side">R: ' + (n.user.right_count ?? 0) + '</span>' +
                        '<span class="leg-basic leg-clickable"  onclick="showLegDetail(event,' + n.user.id + ',\'right\',\'basic_package\')">BSV: ₹' + (n.user.right_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                        '<span class="leg-premium leg-clickable" onclick="showLegDetail(event,' + n.user.id + ',\'right\',\'premium_package\')">PSV: ₹' + (n.user.right_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="node-actions">' +
                    '<button class="node-action-btn btn-node-view"    title="View subtree"      onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-sitemap"></i></button>' +
                    '<button class="node-action-btn btn-node-package" title="Activate package"  onclick="openPackageModal(event,' + n.user.id + ',\'' + (n.user.name||'').replace(/'/g,"\\'") + '\',' + (hasPan ? 'true' : 'false') + ',' + (n.user.package_type ? 'true' : 'false') + ')"><i class="fas fa-box-open"></i></button>' +
                    '<button class="node-action-btn btn-node-move"    title="Move user"         onclick="moveNode(event,' + n.user.id + ')"><i class="fas fa-arrows-alt"></i></button>' +
                    '<button class="node-action-btn btn-node-delete"  title="Remove from tree"  onclick="deleteNode(event,' + n.user.id + ',\'' + (n.user.name||'').replace(/'/g,"\\'") + '\')"><i class="fas fa-trash-alt"></i></button>' +
                '</div>';
            el.style.cursor = 'default';
            byId[n.user.id] = n;

            el.style.left = n.x + 'px';
            el.style.top  = n.y + 'px';
            canvas.appendChild(el);
        } else {
            el.className = 'vacant-node';
            const pid = n.vacantParentId, pos = n.vacantPosition;
            el.innerHTML =
                '<div class="vacant-circle" data-toggle="modal" data-target="#placeUserModal" onclick="setPlacementTarget(' + pid + ',\'' + pos + '\')"><i class="fas fa-plus"></i></div>' +
                '<div class="vacant-label">VACANT</div>' +
                '<div class="vacant-slot">' + (pos ? pos.charAt(0).toUpperCase() + pos.slice(1) + ' slot' : '') + '</div>' +
                '<button class="btn btn-xs btn-warning mt-1 btn-quick-user" style="font-size:32px;padding:4px 10px;" onclick="quickTestUser(event,' + pid + ',\'' + pos + '\')"><i class="fas fa-bolt"></i> Quick</button>';
            el.style.left = n.x + 'px';
            el.style.top  = n.y + 'px';
            canvas.appendChild(el);
        }
    });

    // Draw connectors: find each node's children in the layout
    allNodes.forEach(function (parent) {
        if (!parent.user) return;
        allNodes.forEach(function (child) {
            const childParentId = child.user
                ? (child.user.parent_id ?? null)
                : child.vacantParentId;
            if (String(childParentId) === String(parent.user.id) && child.level === parent.level + 1) {
                drawConnector(svg, parent.x, parent.y, child.x, child.y);
            }
        });
    });
}

function scaleTree() {
    const wrapper = document.querySelector('.tree-scroll-wrapper');
    const scaler  = document.getElementById('treeScaler');
    const canvas  = document.getElementById('treeCanvas');
    if (!scaler || !canvas) return;

    // Reset so we measure natural size
    scaler.style.transform = '';
    wrapper.style.height   = '';

    const treeW  = canvas.offsetWidth;
    const treeH  = canvas.offsetHeight;
    const availW = wrapper.clientWidth - 40;

    // Scale based on width only — let height grow naturally
    const scale  = Math.min(availW / treeW, 1);

    if (scale >= 1) {
        wrapper.style.height = (treeH + 60) + 'px';
        return;
    }

    scaler.style.transform       = 'scale(' + scale + ')';
    scaler.style.transformOrigin = 'top center';
    wrapper.style.height         = (treeH * scale + 40) + 'px';
}

$(document).ready(function () {
    renderBinaryTree();
    // Small delay so DOM has rendered before measuring
    setTimeout(scaleTree, 50);
});

$(window).on('resize', function () { setTimeout(scaleTree, 50); });

// ── ROUTES ────────────────────────────────────────────────────────────────────
const ROUTES = {
    setRoot:            '{{ route("admin.binary_tree.set_root") }}',
    transferUser:       '{{ route("admin.binary_tree.transfer_user") }}',
    removeUser:         '{{ route("admin.binary_tree.remove_user") }}',
    searchUsers:        '{{ route("admin.binary_tree.search_users") }}',
    completeMig:        '{{ route("admin.binary_tree.complete_migration") }}',
    registerWithPan:    '{{ route("register.store") }}',
    registerWithoutPan: '{{ route("register.store.wpan") }}',
    reload:             '{{ route("admin.binary_tree") }}',
    checkSlots:         '{{ route("admin.binary_tree.check_slots") }}',
    moveUser:           '{{ route("admin.binary_tree.move_user") }}',
    quickUser:          '{{ route("admin.binary_tree.quick_user") }}',
    pinOwners:          '{{ route("admin.binary_tree.pin_owners") }}',
    userPackages:       '{{ route("admin.binary_tree.user_packages") }}',
    legVolumeDetail:    '{{ route("admin.binary_tree.leg_volume_detail") }}',
};
const CSRF = '{{ csrf_token() }}';

// ── Helpers ──────────────────────────────────────────────────────────────────
function postJSON(url, data, cb) {
    $.ajax({
        url,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: cb,
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
        }
    });
}

function reloadPage() { window.location.href = window.location.href; }

// ── Live search (reusable) ────────────────────────────────────────────────────
function liveSearch(inputId, resultsId, hiddenId, infoId, onSelect) {
    $('#' + inputId).on('input', function () {
        const q = $(this).val().trim();
        if (q.length < 2) { $('#' + resultsId).empty(); return; }
        $.get(ROUTES.searchUsers, { q }, function (data) {
            const $list = $('#' + resultsId).empty();
            if (!data.length) {
                $list.append('<div class="p-2 text-muted small">No users found.</div>');
                return;
            }
            data.forEach(function (u) {
                $('<div class="user-search-result">')
                    .html('<img src="'+u.image+'"><div><div style="font-size:13px;font-weight:600;">'+u.name+'</div><div style="font-size:11px;color:#888;">'+u.connection+'</div></div>')
                    .on('click', function () {
                        $('#' + hiddenId).val(u.id);
                        $('#' + inputId).val(u.name + ' — ' + u.connection);
                        $list.empty();
                        onSelect && onSelect(u);
                        $('#' + infoId).text('Selected: ' + u.name).show();
                    })
                    .appendTo($list);
            });
        });
    });
}

// ── Root search ───────────────────────────────────────────────────────────────
liveSearch('rootSearch', 'rootSearchResults', 'selectedRootUserId', 'rootSelectedInfo');

// ── Topbar quick search (tree users only) ─────────────────────────────────────
(function () {
    let debounce;
    $('#treeQuickSearch').on('input', function () {
        clearTimeout(debounce);
        const q = $(this).val().trim();
        const $drop = $('#treeQuickSearchDropdown');
        if (q.length < 2) { $drop.hide().empty(); return; }
        debounce = setTimeout(function () {
            $.get(ROUTES.searchUsers, { q, tree_only: 1 }, function (data) {
                $drop.empty();
                if (!data.length) {
                    $drop.html('<div class="p-2 text-muted small">No tree users found.</div>').show();
                    return;
                }
                data.forEach(function (u) {
                    $('<div class="user-search-result">')
                        .html('<img src="' + u.image + '"><div><div style="font-size:13px;font-weight:600;">' + u.name + '</div><div style="font-size:11px;color:#888;">' + u.connection + '</div></div>')
                        .on('click', function () {
                            window.location.href = ROUTES.reload + '?node_id=' + u.id;
                        })
                        .appendTo($drop);
                });
                $drop.show();
            });
        }, 250);
    });

    // Close dropdown on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.topbar-search-wrap').length) {
            $('#treeQuickSearchDropdown').hide();
        }
    });
})();

$('#btnSetRoot').on('click', function () {
    const userId = $('#selectedRootUserId').val();
    if (!userId) { Swal.fire('Warning', 'Please select a user first.', 'warning'); return; }
    postJSON(ROUTES.setRoot, { user_id: userId }, function (res) {
        if (res.status === 'success') {
            Swal.fire('Done', res.message, 'success').then(reloadPage);
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
});

// ── Quick Test User ────────────────────────────────────────────────────────────
function quickTestUser(e, parentId, position) {
    e.stopPropagation();
    Swal.fire({
        title: 'Create test user?',
        html: position.charAt(0).toUpperCase() + position.slice(1) + ' slot under parent ID ' + parentId,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Generate',
        confirmButtonColor: '#fd7e14',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        postJSON(ROUTES.quickUser, { parent_id: parentId, position: position }, function (res) {
            if (res.status === 'success') {
                Swal.fire('Done!', 'Created: <b>' + res.connection + '</b><br>Password: <code>' + res.password + '</code>', 'success').then(reloadPage);
            } else {
                Swal.fire('Error', res.message || 'Failed', 'error');
            }
        });
    });
}

// ── Placement ─────────────────────────────────────────────────────────────────
function setPlacementTarget(parentId, position) {
    $('#placementParentId').val(parentId);
    $('#placementPosition').val(position);
    $('#placementInfo').text('Parent ID: ' + parentId + ' — ' + position.charAt(0).toUpperCase() + position.slice(1) + ' slot');
    $('#selectedTransferUserId').val('');
    $('#transferSearch').val('');
    $('#transferSearchResults').empty();
    $('#transferSelectedInfo').hide();
    $('#newUserForm')[0].reset();
    // Reset to without-PAN mode
    $('#regTypeWpan').prop('checked', true);
    $('#panCardField').hide();
    $('#panCardInput').prop('required', false);
    // Reset submit button in case a previous request left it disabled
    $('#btnPlaceUser').prop('disabled', false).text('Place User');
}

liveSearch('transferSearch', 'transferSearchResults', 'selectedTransferUserId', 'transferSelectedInfo');

$('#btnPlaceUser').on('click', function () {
    const $btn = $(this);
    if ($btn.prop('disabled')) return;

    const activeTab = $('#placeTabs .nav-link.active').attr('href');
    const parentId  = $('#placementParentId').val();
    const position  = $('#placementPosition').val();

    const enableBtn = () => $btn.prop('disabled', false).text('Place User');
    const disableBtn = () => $btn.prop('disabled', true).text('Processing...');

    if (activeTab === '#tabTransfer') {
        const userId = $('#selectedTransferUserId').val();
        if (!userId) { Swal.fire('Warning', 'Please select a user to transfer.', 'warning'); return; }
        disableBtn();
        postJSON(ROUTES.transferUser, { user_id: userId, parent_id: parentId, position: position }, function (res) {
            if (res.status === 'success') {
                Swal.fire('Placed!', res.message, 'success').then(function () {
                    $('#placeUserModal').modal('hide');
                    reloadPage();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
                enableBtn();
            }
        });
    } else {
        // New user — submit via existing register route (with or without PAN)
        const form = document.getElementById('newUserForm');
        const regType = $('input[name="reg_type"]:checked').val();
        const usePan  = (regType === 'withpan');

        // Toggle PAN required before validity check
        $('#panCardInput').prop('required', usePan);
        if (!form.checkValidity()) { form.reportValidity(); return; }

        disableBtn();

        const data = new FormData(form);
        data.append('parent_id', parentId);
        data.append('position', position);
        // Remove pan_card_no from FormData if without-pan (backend sets it to STORE)
        if (!usePan) data.delete('pan_card_no');

        const url = usePan ? ROUTES.registerWithPan : ROUTES.registerWithoutPan;

        $.ajax({
            url: url,
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
                        title: 'User Created!',
                        html:
                            '<div class="text-left mt-2">' +
                            '<p><strong>User ID:</strong> <span class="text-primary" style="font-size:1.1em;">' + res.connection + '</span></p>' +
                            '<p><strong>Password:</strong> <span class="text-danger" style="font-size:1.1em;">' + res.password + '</span></p>' +
                            '</div>',
                        icon: 'success',
                        confirmButtonText: 'Close',
                        allowOutsideClick: false
                    }).then(function () {
                        reloadPage();
                    });
                } else if (res.status === 'validation') {
                    let msg = '';
                    $.each(res.errors, function (k, v) { msg += v[0] + '<br>'; });
                    Swal.fire({ icon: 'error', title: 'Validation', html: msg });
                    enableBtn();
                } else {
                    Swal.fire('Error', res.message || 'Registration failed.', 'error');
                    enableBtn();
                }
            },
            error: function () {
                Swal.fire('Error', 'Registration request failed.', 'error');
                enableBtn();
            }
        });
    }
});

// ── Node action: view subtree ─────────────────────────────────────────────────
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function viewSubtree(e, userId) {
    e.stopPropagation();
    window.location.href = ROUTES.reload + '?node_id=' + userId;
}

// ── Node action: move ─────────────────────────────────────────────────────────
function moveNode(e, userId) {
    e.stopPropagation();

    // Find user name from rendered nodes
    const allCards = document.querySelectorAll('.node-card');
    let userName = 'User #' + userId;
    allCards.forEach(function (card) {
        const nameEl = card.querySelector('.node-name');
        // Match by button onclick attribute
        const btn = card.querySelector('.btn-node-move');
        if (btn && btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(',' + userId + ')')) {
            if (nameEl) userName = nameEl.textContent.trim();
        }
    });

    // Reset modal state
    $('#moveUserId').val(userId);
    $('#moveUserName').text(userName);
    $('#moveTargetSearch').val('');
    $('#moveTargetResults').empty();
    $('#moveTargetId').val('');
    $('#moveTargetInfo').hide();
    $('#moveSlotSection').hide();
    $('#moveSlotOptions').empty();
    $('#moveSlotError').hide();
    $('#btnConfirmMove').prop('disabled', true);

    $('#moveUserModal').modal('show');
}

// Live search for move target
$('#moveTargetSearch').on('input', function () {
    const q = $(this).val().trim();
    if (q.length < 2) { $('#moveTargetResults').empty(); return; }
    $.get(ROUTES.searchUsers, { q }, function (data) {
        const movingId = $('#moveUserId').val();
        const $list = $('#moveTargetResults').empty();
        if (!data.length) {
            $list.append('<div class="p-2 text-muted small">No users found.</div>');
            return;
        }
        data.forEach(function (u) {
            if (String(u.id) === String(movingId)) return; // skip self
            $('<div class="user-search-result">')
                .html('<img src="' + u.image + '"><div><div style="font-size:13px;font-weight:600;">' + u.name + '</div><div style="font-size:11px;color:#888;">' + u.connection + '</div></div>')
                .on('click', function () {
                    $('#moveTargetId').val(u.id);
                    $('#moveTargetSearch').val(u.name + ' — ' + u.connection);
                    $list.empty();
                    $('#moveTargetInfo').text('Selected: ' + u.name).show();
                    loadMoveSlots(u.id);
                })
                .appendTo($list);
        });
    });
});

function loadMoveSlots(targetId) {
    const movingId = $('#moveUserId').val();
    $('#moveSlotSection').hide();
    $('#moveSlotOptions').empty();
    $('#moveSlotError').hide();
    $('#btnConfirmMove').prop('disabled', true);

    $.get(ROUTES.checkSlots, { target_id: targetId, moving_id: movingId }, function (res) {
        if (res.error) {
            $('#moveSlotError').text(res.error).show();
            return;
        }

        const leftAvail  = res.left;
        const rightAvail = res.right;

        if (!leftAvail && !rightAvail) {
            $('#moveSlotError').text('No positions available under this user. Both left and right slots are occupied.').show();
            return;
        }

        const $opts = $('#moveSlotOptions').empty();

        function slotBtn(side, available) {
            const $wrap = $('<div class="mr-3 mb-2">');
            const $radio = $('<input type="radio" name="move_position" style="margin-right:6px;">')
                .val(side)
                .prop('disabled', !available)
                .prop('id', 'movePos_' + side);
            const $label = $('<label style="font-size:15px;cursor:' + (available ? 'pointer' : 'not-allowed') + ';color:' + (available ? '#222' : '#aaa') + ';">')
                .attr('for', 'movePos_' + side)
                .text(side.charAt(0).toUpperCase() + side.slice(1) + ' — ' + (available ? 'Available' : 'Not available'));
            $wrap.append($radio).append($label);
            $opts.append($wrap);
        }

        slotBtn('left',  leftAvail);
        slotBtn('right', rightAvail);

        $('#moveSlotSection').show();

        $('input[name="move_position"]').on('change', function () {
            $('#btnConfirmMove').prop('disabled', false);
        });
    }).fail(function (xhr) {
        const msg = xhr.responseJSON?.error || 'Failed to check slots.';
        $('#moveSlotError').text(msg).show();
    });
}

$('#btnConfirmMove').on('click', function () {
    const userId   = $('#moveUserId').val();
    const targetId = $('#moveTargetId').val();
    const position = $('input[name="move_position"]:checked').val();

    if (!targetId || !position) {
        Swal.fire('Warning', 'Please select a target user and position.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Move user?',
        text: 'The user and their entire subtree will be moved to the selected position.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, move',
        confirmButtonColor: '#fd7e14',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        postJSON(ROUTES.moveUser, { user_id: userId, target_parent_id: targetId, position: position }, function (res) {
            if (res.status === 'success') {
                Swal.fire('Moved!', res.message, 'success').then(function () {
                    $('#moveUserModal').modal('hide');
                    reloadPage();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

// ── Node action: delete ───────────────────────────────────────────────────────
function deleteNode(e, userId, userName) {
    e.stopPropagation();
    Swal.fire({
        title: 'Remove "' + userName + '" from tree?',
        text: 'Their children will become unplaced. This cannot be undone automatically.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove',
        confirmButtonColor: '#dc3545',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        postJSON(ROUTES.removeUser, { user_id: userId }, function (res) {
            if (res.status === 'success') {
                Swal.fire('Removed', res.message, 'success').then(reloadPage);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
}

// ── Package activation ────────────────────────────────────────────────────────
const PKG_BADGE_CLASS = {
    basic_package:   'basic',
    premium_package: 'premium',
    prime_package:   'prime',
};

const IS_ADMIN = {{ (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') ? 'true' : 'false' }};

function resetPackageModal() {
    $('#pkgUserId').val('');
    $('#pkgUserName').text('-');
    $('#pkgPackageId').val('');
    $('#pkgPinId').html('<option value="">-- Select package first --</option>');
    $('#pkgProductId').html('<option value="">-- Select package first --</option>');
    $('#pkgCurrentSection').hide();
    $('#pkgCurrentList').empty();
    $('#pkgPinSection').hide();
    $('#pkgProductSection').hide();
    $('#pkgPackageSection').hide();
    if (IS_ADMIN) {
        if ($('#pkgPinOwnerDropdown').data('select2')) {
            $('#pkgPinOwnerDropdown').val('').trigger('change');
        } else {
            $('#pkgPinOwnerDropdown').val('');
        }
    }
}

function loadUserPackageBadges(userId) {
    $.get(ROUTES.userPackages, { user_id: userId }, function (packages) {
        if (packages && packages.length) {
            const $list = $('#pkgCurrentList').empty();
            packages.forEach(function (p) {
                const cls = PKG_BADGE_CLASS[p.package_code] || 'other';
                $list.append(
                    '<span class="pkg-badge ' + cls + '">' +
                        '<i class="fas fa-box"></i> ' + p.name +
                        ' <small style="opacity:.7;">— ₹' + p.amount + ' · ' + p.activated_on + '</small>' +
                    '</span>'
                );
            });
            $('#pkgCurrentSection').show();
        } else {
            $('#pkgCurrentSection').hide();
        }
    });
}

function openPackageModal(e, userId, userName, hasPan, hasPackage) {
    e.stopPropagation();
    resetPackageModal();

    hasPan      = hasPan      ?? true;
    hasPackage  = hasPackage  ?? true;

    $('#pkgUserId').val(userId);
    $('#pkgUserName').text(userName);

    const currentNodeId = new URLSearchParams(window.location.search).get('node_id');
    $('#pkgNodeId').val(currentNodeId || '');

    $('#panStepSection').hide();
    $('#pkgMainSection').show();
    $('#pkgPackageSection').show();
    loadUserPackageBadges(userId);
    if (IS_ADMIN) loadPinOwners(userId);
    $('#packageModal .btn-success').prop('disabled', false);

    $('#packageModal').modal('show');
}

function loadPinOwners(userId) {
    $('#pkgPinOwnerDropdown').html('<option value="">Loading...</option>').prop('disabled', true);
    $.get(ROUTES.pinOwners, { user_id: userId }, function (owners) {
        const $dd = $('#pkgPinOwnerDropdown').empty().prop('disabled', false);
        $dd.append('<option value="">-- Select pin owner --</option>');
        owners.forEach(function (o) {
            $dd.append('<option value="' + o.id + '">' + o.label + '</option>');
        });
        $dd.trigger('change');
    });
}

// PAN verify button
$('#panStepVerifyBtn').on('click', function () {
    const pan    = $('#panStepInput').val().trim().toUpperCase();
    const userId = $('#pkgUserId').val();
    const name   = $('#pkgUserName').text().trim();

    if (!/^[A-Z]{5}[0-9]{4}[A-Z]$/.test(pan)) {
        $('#panStepMsg').text('Invalid PAN format. Must be like ABCDE1234F.').removeClass('text-success').addClass('text-danger');
        return;
    }

    $('#panStepVerifyBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');

    $.post('{{ route('admin.user.assign_pan') }}', {
        _token: '{{ csrf_token() }}',
        user_id: userId,
        pan_card_no: pan,
        name: name
    })
    .done(function (res) {
        if (res.status === 'success') {
            $('#panStepMsg').text('').removeClass('text-danger');
            $('#panStepSection').hide();
            $('#panStepDone').show();
            $('#panStepAssignedLabel').text(res.label + ' — PAN: ' + pan);
            $('#pkgMainSection').show();
            $('#pkgPackageSection').show();
            loadUserPackageBadges(userId);
            if (IS_ADMIN) loadPinOwners(userId);
            $('#packageModal .btn-success').prop('disabled', false);
        } else {
            $('#panStepMsg').text(res.message).removeClass('text-success').addClass('text-danger');
        }
    })
    .fail(function () {
        $('#panStepMsg').text('Server error. Try again.').addClass('text-danger');
    })
    .always(function () {
        $('#panStepVerifyBtn').prop('disabled', false).html('<i class="fas fa-check"></i> Verify & Continue');
    });
});

// Admin: pin owner dropdown — only controls which pins load, NOT the target user
$('#pkgPinOwnerDropdown').off('change').on('change', function () {
    const pinOwnerId = $(this).val();
    $('#pkgPinId').html('<option value="">-- Select package first --</option>');
    // Re-trigger package change to reload pins for new owner
    const packageId = $('#pkgPackageId').val();
    if (pinOwnerId && packageId) {
        $('#pkgPackageId').trigger('change');
    }
});

$('#pkgPackageId').on('change', function () {
    const packageId  = $(this).val();
    const targetId   = $('#pkgUserId').val();
    // Admin uses pin owner dropdown; regular user uses themselves
    const pinOwnerId = IS_ADMIN ? ($('#pkgPinOwnerDropdown').val() || targetId) : targetId;

    if (!packageId || !targetId) return;

    $('#pkgPinId').html('<option value="">Loading...</option>');
    $('#pkgProductId').html('<option value="">Loading...</option>');
    $('#pkgPinSection').show();
    $('#pkgProductSection').show();

    $.get('/get-available-pins', { package_id: packageId, user_id: pinOwnerId, target_user_id: targetId }, function (res) {
        const $pins = $('#pkgPinId').empty().append('<option value="">-- Choose Pin --</option>');
        if (res.pins && res.pins.length) {
            res.pins.forEach(function (p) {
                $pins.append('<option value="' + p.id + '">' + p.unique_id + '</option>');
            });
        } else {
            $pins.append('<option value="">No pins available</option>');
        }

        const $prods = $('#pkgProductId').empty().append('<option value="">-- Choose Product --</option>');
        if (res.products && res.products.length) {
            res.products.forEach(function (p) {
                $prods.append('<option value="' + p.id + '">' + p.product_name + '</option>');
            });
        } else {
            $prods.append('<option value="">No products available</option>');
        }
    }).fail(function () {
        Swal.fire('Error', 'Failed to load pins. Please try again.', 'error');
    });
});

// ── PAN card radio toggle ─────────────────────────────────────────────────────
$('input[name="reg_type"]').on('change', function () {
    const withPan = $(this).val() === 'withpan';
    $('#panCardField').toggle(withPan);
    $('#panCardInput').prop('required', withPan);
});

// ── Complete migration ────────────────────────────────────────────────────────
$('#btnCompleteMigration').on('click', function () {
    Swal.fire({
        title: 'Mark migration complete?',
        text: 'This will open new user registrations and show binary tree dashboards to users.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, complete',
        confirmButtonColor: '#28a745',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        postJSON(ROUTES.completeMig, {}, function (res) {
            if (res.status === 'success') {
                Swal.fire('Done!', res.message, 'success').then(reloadPage);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

// ── Leg Volume Detail Popup (admin only) ─────────────────────────────────────
function showLegDetail(e, userId, side, packageCode) {
    e.stopPropagation();
    const label = packageCode === 'basic_package' ? 'BSV (Basic)' : 'PSV (Premium)';
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
            '<thead class="thead-light"><tr>' +
            '<th>#</th><th>ID</th><th>Name</th><th>Package</th><th>Activated On</th><th class="text-right">BV</th>' +
            '</tr></thead><tbody>';
        res.rows.forEach(function (r, i) {
            html += '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td><span class="text-primary font-weight-bold">' + r.connection + '</span></td>' +
                '<td>' + r.name + '</td>' +
                '<td>' + r.package_name + '</td>' +
                '<td>' + (r.activated_at ? r.activated_at.substring(0, 10) : '') + '</td>' +
                '<td class="text-right font-weight-bold">₹' + Number(r.bv).toLocaleString('en-IN') + '</td>' +
                '</tr>';
        });
        html += '</tbody><tfoot><tr class="table-info">' +
            '<td colspan="5" class="text-right font-weight-bold">Total BV</td>' +
            '<td class="text-right font-weight-bold">₹' + Number(res.total_bv).toLocaleString('en-IN') + '</td>' +
            '</tr></tfoot></table>';
        $('#legDetailBody').html(html);
    }).fail(function () {
        $('#legDetailBody').html('<p class="text-danger text-center">Failed to load data.</p>');
    });
}

// Sponsor ID live name preview
(function () {
    var timer;
    $(document).on('input', '#sponsorIdInput', function () {
        var val = $.trim($(this).val()).toUpperCase();
        var $preview = $('#sponsorNamePreview');
        clearTimeout(timer);
        if (!val) { $preview.text(''); return; }
        timer = setTimeout(function () {
            $.post('{{ route("get_user_name") }}', { userId: val, _token: '{{ csrf_token() }}' })
                .done(function (res) {
                    $preview.html('<span class="text-success"><i class="fas fa-check-circle mr-1"></i>' + res.name + '</span>');
                })
                .fail(function () {
                    $preview.html('<span class="text-danger"><i class="fas fa-times-circle mr-1"></i>User not found</span>');
                });
        }, 400);
    });
})();
</script>

{{-- Leg Volume Detail Modal --}}
<div class="modal fade" id="legDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="legDetailTitle">Leg Volume Breakdown</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="legDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
