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
.node-img { width: 132px; height: 132px; border-radius: 50%; object-fit: cover; border: 4px solid #007bff; margin-bottom: 10px; }
.root-node .node-img { border-color: #fd7e14; }
.node-img-wrap { position: relative; display: inline-block; }
.has-more-badge { position: absolute; top: 50%; right: -14px; transform: translateY(-50%); background: #dc3545; color: #fff; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 13px; cursor: pointer; box-shadow: 0 2px 6px rgba(220,53,69,.5); z-index: 5; }
.node-id   { font-size: 43px; font-weight: 700; color: #333; margin-bottom: 2px; }
.node-name { font-size: 43px; color: #555; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.node-lr   { font-size: 34px; color: #555; margin-bottom: 6px; display: flex; justify-content: center; gap: 18px; }
.node-lr .leg-side { font-weight: 700; font-size: 36px; margin-bottom: 2px; }
.node-lr .leg-basic   { color: #856404; background: #fff3cd; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.node-lr .leg-premium { color: #155724; background: #d4edda; border-radius: 4px; padding: 2px 10px; font-size: 32px; }
.node-leg    { display: flex; flex-direction: column; align-items: center; gap: 3px; }

.node-action-btn { width: 72px; height: 72px; border-radius: 10px; border: none; font-size: 36px; cursor: pointer; transition: opacity .2s, transform .2s; }
.node-action-btn:hover { opacity: .8; transform: scale(1.1); }
.btn-node-view    { background: #e8f4ff; color: #007bff; }
.btn-node-package { background: #eafaf1; color: #28a745; }
.node-actions { display: flex; justify-content: center; gap: 10px; margin-top: 8px; }

.pkg-basic   .node-img { border-color: #ffc107 !important; box-shadow: 0 0 0 3px #ffc10755; }
.pkg-premium .node-img { border-color: #28a745 !important; box-shadow: 0 0 0 3px #28a74555; }
.pkg-prime   .node-img { border-color: #fd7e14 !important; box-shadow: 0 0 0 3px #fd7e1455; }

.vacant-node { position: absolute; width: 348px; background: #fff8f0; border: 2px dashed #fd7e14; border-radius: 14px; padding: 20px 10px; text-align: center; z-index: 2; }
.vacant-circle { width: 108px; height: 108px; border-radius: 50%; background: #fff3e0; border: 2px dashed #fd7e14; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 40px; color: #fd7e14; }
.vacant-label { font-size: 40px; font-weight: 700; color: #fd7e14; }
.vacant-slot  { font-size: 34px; color: #aaa; margin-top: 4px; }

.pkg-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin: 2px; border: 1px solid; }
.pkg-badge.basic   { background: #fff9e6; color: #856404; border-color: #ffc107; }
.pkg-badge.premium { background: #d4edda; color: #155724; border-color: #28a745; }
.pkg-badge.prime   { background: #fff3e0; color: #7a3300; border-color: #fd7e14; }
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

{{-- Package Modal (user: own pins only) --}}
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

const ROUTES = {
    userPackages: '{{ route("admin.binary_tree.user_packages") }}',
    reload:       '{{ route("user.binary_tree") }}',
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
        nodes.push(...buildLayout(node.left,  level+1, xStart, xMid, false, { id: node.user.id, side:'left' }));
        nodes.push(...buildLayout(node.right, level+1, xMid,  xEnd, false, { id: node.user.id, side:'right' }));
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
    // Users can only activate for themselves
    if (userId != MY_USER_ID) {
        Swal.fire('Not allowed', 'You can only activate a package for yourself.', 'warning');
        return;
    }
    $('#pkgUserId').val(userId);
    $('#pkgUserName').text(userName);
    $('#pkgPackageId').val('');
    $('#pkgPinId').html('<option value="">-- Select package first --</option>');
    $('#pkgProductId').html('<option value="">-- Select package first --</option>');
    $('#pkgPinSection').hide();
    $('#pkgProductSection').hide();
    $('#pkgCurrentSection').hide();
    $('#pkgCurrentList').empty();

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

    $.get('/get-available-pins', { package_id: packageId, user_id: userId }, function (res) {
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
        success: function (res) {
            try { res = typeof res === 'string' ? JSON.parse(res) : res; } catch(e) {}
            Swal.fire('Activated!', 'Package activated successfully.', 'success').then(reloadPage);
        },
        error: function () { Swal.fire('Error', 'Activation failed. Please try again.', 'error'); }
    });
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
        const pkgClass = n.user.package_type ? ' pkg-' + n.user.package_type : '';
        el.className   = 'node-card' + (n.isRoot ? ' root-node' : '') + pkgClass;
        const imgSrc   = n.user.user_image ? '/' + n.user.user_image : '/assets/dist/img/images.jpg';
        const imgTag   = '<img src="' + imgSrc + '" onerror="this.src=\'/assets/dist/img/images.jpg\'" class="node-img" alt="user">';
        const imgHtml  = hasMore
            ? '<div class="node-img-wrap">' + imgTag + '<div class="has-more-badge" onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-chevron-down"></i></div></div>'
            : imgTag;

        // Only show package button on own node
        const pkgBtn = (n.user.id == MY_USER_ID)
            ? '<button class="node-action-btn btn-node-package" title="Activate package" onclick="openPackageModal(event,' + n.user.id + ',\'' + (n.user.name||'').replace(/'/g,"\\'") + '\')"><i class="fas fa-box-open"></i></button>'
            : '';

        el.innerHTML =
            imgHtml +
            '<div class="node-id">'  + (n.user.connection || '') + '</div>' +
            '<div class="node-name">' + (n.user.name || '') + '</div>' +
            '<div class="node-lr">' +
                '<div class="node-leg">' +
                    '<span class="leg-side">L: ' + (n.user.left_count ?? 0) + '</span>' +
                    '<span class="leg-basic">BSV: ₹' + (n.user.left_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '<span class="leg-premium">PSV: ₹' + (n.user.left_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                '</div>' +
                '<div class="node-leg">' +
                    '<span class="leg-side">R: ' + (n.user.right_count ?? 0) + '</span>' +
                    '<span class="leg-basic">BSV: ₹' + (n.user.right_basic_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                    '<span class="leg-premium">PSV: ₹' + (n.user.right_premium_vol ?? 0).toLocaleString('en-IN') + '</span>' +
                '</div>' +
            '</div>' +
            (pkgBtn ? '<div class="node-actions">' +
                '<button class="node-action-btn btn-node-view" title="View subtree" onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-sitemap"></i></button>' +
                pkgBtn +
            '</div>' : '<div class="node-actions"><button class="node-action-btn btn-node-view" title="View subtree" onclick="viewSubtree(event,' + n.user.id + ')"><i class="fas fa-sitemap"></i></button></div>');

        byId[n.user.id] = n;
    } else {
        el.className = 'vacant-node';
        el.innerHTML = '<div class="vacant-circle"><i class="fas fa-plus"></i></div><div class="vacant-label">VACANT</div><div class="vacant-slot">' + (n.vacantPosition ? n.vacantPosition.charAt(0).toUpperCase() + n.vacantPosition.slice(1) + ' slot' : '') + '</div>';
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
</script>
@endsection
