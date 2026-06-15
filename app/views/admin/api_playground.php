<?php include 'app/views/shares/header.php'; ?>

<style>
/* ── Admin API Playground ── */
#api-pg-wrap {
    display: flex;
    min-height: calc(100vh - 70px);
    background: #0f172a;
    color: #e2e8f0;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
#api-sidebar {
    width: 255px;
    min-width: 255px;
    background: #1e293b;
    border-right: 1px solid #334155;
    overflow-y: auto;
}
.api-sidebar-header {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    padding: 18px 20px;
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 10;
}
.api-nav-group {
    font-size: .65rem;
    letter-spacing: .13em;
    color: #475569;
    text-transform: uppercase;
    padding: 14px 20px 4px;
}
.api-nav-btn {
    width: 100%;
    background: none;
    border: none;
    border-left: 3px solid transparent;
    text-align: left;
    padding: 9px 20px;
    color: #94a3b8;
    font-size: .84rem;
    cursor: pointer;
    transition: .15s;
    display: block;
}
.api-nav-btn:hover  { background: #334155; color: #e2e8f0; }
.api-nav-btn.active { background: #253347; color: #fff; border-left-color: #6366f1; }
.bm { display:inline-block; font-size:.6rem; font-weight:700; padding:2px 6px; border-radius:4px; margin-right:7px; vertical-align: middle; }
.bm-GET    { background:#10b981; color:#fff; }
.bm-POST   { background:#f59e0b; color:#000; }
.bm-PUT    { background:#3b82f6; color:#fff; }
.bm-DELETE { background:#ef4444; color:#fff; }

/* Main */
#api-main {
    flex: 1;
    padding: 26px 36px 40px;
    max-width: 900px;
    overflow-y: auto;
}
.ep-header { display:flex; align-items:center; gap:12px; margin-bottom:10px; }
.ep-method {
    font-size: .86rem; font-weight: 700; padding: 6px 14px;
    border-radius: 6px; letter-spacing: .05em; white-space: nowrap;
}
.ep-url {
    background: #1e293b; border: 1px solid #334155; border-radius: 8px;
    padding: 9px 16px; font-family: monospace; font-size: .84rem;
    color: #94a3b8; flex: 1; word-break: break-all;
}
.ep-desc { font-size: .84rem; color: #64748b; margin-bottom: 18px; }

/* Panels */
.api-panel { background: #1e293b; border: 1px solid #334155; border-radius: 10px; margin-bottom: 16px; }
.api-panel-title {
    padding: 11px 18px; font-size: .7rem; letter-spacing: .1em;
    text-transform: uppercase; color: #64748b; border-bottom: 1px solid #334155;
    display: flex; align-items: center; justify-content: space-between;
}
.api-panel-body { padding: 18px; }
.api-panel .form-control,
.api-panel .form-select,
.api-panel input[type="file"] {
    background: #0f172a !important; border-color: #334155 !important;
    color: #e2e8f0 !important;
}
.api-panel .form-control:focus,
.api-panel .form-select:focus {
    border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.2) !important;
}
.api-panel .form-label { color: #94a3b8; font-size: .78rem; margin-bottom: 4px; }

/* Body mode tabs */
.body-mode-tabs { display: flex; gap: 6px; }
.body-mode-tab {
    font-size: .68rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
    border: 1px solid #334155; background: none; color: #64748b; cursor: pointer; transition: .15s;
}
.body-mode-tab.active { background: #6366f1; border-color: #6366f1; color: #fff; }

/* Image upload zone */
#api-img-zone {
    border: 2px dashed #334155; border-radius: 8px; padding: 20px;
    text-align: center; color: #64748b; cursor: pointer;
    transition: border-color .2s, background .2s;
    margin-bottom: 12px;
}
#api-img-zone:hover, #api-img-zone.dragover { border-color: #6366f1; background: rgba(99,102,241,.06); color: #a5b4fc; }
#api-img-zone input[type="file"] { display: none; }
#api-img-preview { display: none; position: relative; }
#api-img-preview img { max-width: 160px; max-height: 160px; border-radius: 8px; object-fit: cover; border: 2px solid #334155; }
.img-remove-btn {
    position: absolute; top: -8px; right: -8px; width: 22px; height: 22px;
    background: #ef4444; border: none; border-radius: 50%; color: #fff;
    font-size: .65rem; cursor: pointer; line-height: 22px; text-align: center;
}

/* Field rows */
.field-row { display: grid; grid-template-columns: 130px 1fr; gap: 10px; align-items: start; margin-bottom: 12px; }
.field-label { color: #94a3b8; font-size: .78rem; padding-top: 8px; }
.field-badge {
    font-size: .58rem; font-weight: 700; padding: 1px 5px; border-radius: 3px;
    background: #1e3a5f; color: #60a5fa; margin-left: 4px; vertical-align: middle;
}
.field-badge.req { background: #4c1414; color: #f87171; }

/* Response */
.res-panel { background: #020617; border: 1px solid #334155; border-radius: 10px; overflow: hidden; }
.res-header {
    padding: 10px 18px; background: #1e293b; border-bottom: 1px solid #334155;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.res-status { font-size: .76rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
.s2xx { background:#064e3b; color:#6ee7b7; }
.s4xx { background:#7f1d1d; color:#fca5a5; }
.s5xx { background:#4c1d95; color:#c4b5fd; }
.res-time-lbl { font-size: .74rem; color: #64748b; }
#api-response-body {
    margin: 0; padding: 22px; font-size: .82rem; line-height: 1.65;
    color: #a5f3fc; max-height: 420px; overflow-y: auto;
    white-space: pre-wrap; word-break: break-word; font-family: monospace;
}

/* Response image preview */
#api-res-image { display:none; padding: 0 22px 22px; }
#api-res-image img { max-width: 200px; border-radius: 8px; border: 2px solid #334155; }

/* Send button */
.btn-api-send {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none; color: #fff; padding: 11px 28px;
    border-radius: 8px; font-weight: 600; font-size: .9rem;
    cursor: pointer; transition: opacity .15s; margin-bottom: 16px;
    display: inline-flex; align-items: center; gap: 8px;
}
.btn-api-send:hover   { opacity: .88; }
.btn-api-send:disabled { opacity: .45; cursor: not-allowed; }
.api-spin { display:none; }
.api-spin.show { display:inline-block; }
</style>

<div id="api-pg-wrap">

    <!-- ════ SIDEBAR ════ -->
    <div id="api-sidebar">
        <div class="api-sidebar-header">
            <i class="fa-solid fa-bolt me-2"></i> API Playground
            <div style="font-size:.7rem;font-weight:400;margin-top:3px;opacity:.75;">MyStore RESTful API</div>
        </div>

        <div class="api-nav-group">Sản phẩm</div>
        <button class="api-nav-btn active" onclick="apiLoadEp('get-products')">
            <span class="bm bm-GET">GET</span> Danh sách SP
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('get-product')">
            <span class="bm bm-GET">GET</span> Chi tiết SP
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('post-product')">
            <span class="bm bm-POST">POST</span> Thêm SP (+ ảnh)
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('put-product')">
            <span class="bm bm-PUT">PUT</span> Cập nhật SP (+ ảnh)
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('delete-product')">
            <span class="bm bm-DELETE">DELETE</span> Xóa SP
        </button>

        <div class="api-nav-group">Danh mục</div>
        <button class="api-nav-btn" onclick="apiLoadEp('get-categories')">
            <span class="bm bm-GET">GET</span> Danh sách DM
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('post-category')">
            <span class="bm bm-POST">POST</span> Thêm DM
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('put-category')">
            <span class="bm bm-PUT">PUT</span> Cập nhật DM
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('delete-category')">
            <span class="bm bm-DELETE">DELETE</span> Xóa DM
        </button>

        <div class="api-nav-group">Tài khoản</div>
        <button class="api-nav-btn" onclick="apiLoadEp('post-login')">
            <span class="bm bm-POST">POST</span> Đăng nhập
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('get-me')">
            <span class="bm bm-GET">GET</span> Thông tin tài khoản
        </button>
        <button class="api-nav-btn" onclick="apiLoadEp('delete-logout')">
            <span class="bm bm-DELETE">DELETE</span> Đăng xuất
        </button>
    </div>

    <!-- ════ MAIN ════ -->
    <div id="api-main">

        <!-- Endpoint header -->
        <div class="ep-header">
            <span id="api-method-badge" class="ep-method" style="background:#10b981;color:#fff;">GET</span>
            <div class="ep-url" id="api-url-display">/project1/api/product</div>
        </div>
        <p id="api-ep-desc" class="ep-desc">Lấy danh sách tất cả sản phẩm kèm tên danh mục.</p>

        <!-- URL Params panel -->
        <div class="api-panel" id="api-params-panel" style="display:none;">
            <div class="api-panel-title"><i class="fa-solid fa-link me-2"></i>URL Parameters</div>
            <div class="api-panel-body" id="api-params-body"></div>
        </div>

        <!-- Headers panel: Authorization Bearer token -->
        <div class="api-panel" id="api-headers-panel" style="display:none;">
            <div class="api-panel-title">
                <span><i class="fa-solid fa-key me-2"></i>Headers</span>
                <span style="font-size:.65rem;color:#94a3b8;">Token nhận được sau khi đăng nhập (POST /api/auth)</span>
            </div>
            <div class="api-panel-body">
                <div class="field-row">
                    <div class="field-label">Authorization <span class="field-badge">Bearer</span></div>
                    <input type="text" class="form-control form-control-sm" id="api-header-auth"
                           placeholder="Dán token nhận được từ Đăng nhập, hoặc bỏ trống để dùng session/cookie">
                </div>
            </div>
        </div>

        <!-- Body panel: JSON mode -->
        <div class="api-panel" id="api-body-json-panel" style="display:none;">
            <div class="api-panel-title">
                <span><i class="fa-solid fa-code me-2"></i>Request Body (JSON)</span>
            </div>
            <div class="api-panel-body">
                <textarea id="api-request-body" class="form-control" rows="8"
                    style="font-family:monospace;font-size:.82rem;"></textarea>
            </div>
        </div>

        <!-- Body panel: Form-Data mode (với upload ảnh) -->
        <div class="api-panel" id="api-body-form-panel" style="display:none;">
            <div class="api-panel-title">
                <span><i class="fa-solid fa-table-cells me-2"></i>Request Body (multipart/form-data)</span>
                <span style="font-size:.65rem;color:#4ade80;"><i class="fa-solid fa-image me-1"></i>Hỗ trợ upload ảnh</span>
            </div>
            <div class="api-panel-body" id="api-form-fields">
                <!-- Điền động -->
            </div>
        </div>

        <!-- Send -->
        <button class="btn-api-send" id="api-btn-send" onclick="apiSend()">
            <span class="spinner-border spinner-border-sm api-spin" id="api-spinner"></span>
            <i class="fa-solid fa-paper-plane"></i> Gửi Request
        </button>

        <!-- Response -->
        <div class="res-panel">
            <div class="res-header">
                <span class="res-time-lbl" id="api-res-label"><i class="fa-regular fa-clock me-1"></i>Chờ response...</span>
                <span id="api-status-badge" class="res-status ms-auto" style="display:none;"></span>
                <span id="api-time-badge" class="res-time-lbl ms-2" style="display:none;"></span>
            </div>
            <pre id="api-response-body" style="color:#475569;font-style:italic;">// Response sẽ hiển thị ở đây sau khi gửi request.</pre>
            <div id="api-res-image"></div>
        </div>

    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
(function ($) {
    'use strict';

    var BASE = '/project1';

    /* ════════════════════════════════
       Cấu hình endpoints
       mode: 'none' | 'json' | 'form'
    ════════════════════════════════ */
    var ENDPOINTS = {

        'get-products': {
            method: 'GET', mode: 'none',
            url: BASE + '/api/product',
            desc: 'Lấy danh sách tất cả sản phẩm kèm tên danh mục.',
            urlParams: []
        },
        'get-product': {
            method: 'GET', mode: 'none',
            url: BASE + '/api/product/{id}',
            desc: 'Lấy chi tiết 1 sản phẩm theo ID.',
            urlParams: [
                { name: 'id', label: 'ID sản phẩm', placeholder: 'VD: 1', def: '1' }
            ]
        },

        /* POST product — form-data với file ảnh */
        'post-product': {
            method: 'POST', mode: 'form',
            url: BASE + '/api/product',
            desc: 'Tạo sản phẩm mới. Hỗ trợ upload ảnh trực tiếp (multipart/form-data).',
            urlParams: [],
            formFields: [
                { name: 'name',        label: 'Tên sản phẩm', type: 'text',   required: true,  def: '' },
                { name: 'description', label: 'Mô tả',         type: 'text',   required: true,  def: '' },
                { name: 'price',       label: 'Giá',           type: 'number', required: true,  def: '' },
                { name: 'category_id', label: 'ID danh mục',   type: 'number', required: false, def: '' },
                { name: 'image',       label: 'Ảnh sản phẩm',  type: 'file',   required: false, def: '' }
            ]
        },

        /* PUT product — form-data với file ảnh */
        'put-product': {
            method: 'PUT', mode: 'form',
            url: BASE + '/api/product/{id}',
            desc: 'Cập nhật sản phẩm. Hỗ trợ thay ảnh. Không chọn ảnh → giữ ảnh cũ.',
            urlParams: [
                { name: 'id', label: 'ID sản phẩm', placeholder: 'VD: 1', def: '1' }
            ],
            formFields: [
                { name: 'name',        label: 'Tên sản phẩm', type: 'text',   required: false, def: '' },
                { name: 'description', label: 'Mô tả',         type: 'text',   required: false, def: '' },
                { name: 'price',       label: 'Giá',           type: 'number', required: false, def: '' },
                { name: 'category_id', label: 'ID danh mục',   type: 'number', required: false, def: '' },
                { name: 'image',       label: 'Ảnh mới',       type: 'file',   required: false, def: '' }
            ]
        },

        'delete-product': {
            method: 'DELETE', mode: 'none',
            url: BASE + '/api/product/{id}',
            desc: 'Xóa sản phẩm theo ID. Thao tác không thể hoàn tác.',
            urlParams: [
                { name: 'id', label: 'ID sản phẩm', placeholder: 'VD: 5', def: '' }
            ]
        },

        'get-categories': {
            method: 'GET', mode: 'none',
            url: BASE + '/api/category',
            desc: 'Lấy danh sách tất cả danh mục sản phẩm.',
            urlParams: []
        },
        'post-category': {
            method: 'POST', mode: 'json',
            url: BASE + '/api/category',
            desc: 'Tạo danh mục mới.',
            urlParams: [],
            jsonBody: JSON.stringify({ name: 'Danh mục mới', description: 'Mô tả danh mục' }, null, 2)
        },
        'put-category': {
            method: 'PUT', mode: 'json',
            url: BASE + '/api/category/{id}',
            desc: 'Cập nhật danh mục theo ID.',
            urlParams: [
                { name: 'id', label: 'ID danh mục', placeholder: 'VD: 1', def: '1' }
            ],
            jsonBody: JSON.stringify({ name: 'Tên DM cập nhật',description: 'Mô tả danh mục' }, null, 2)
        },
        'delete-category': {
            method: 'DELETE', mode: 'none',
            url: BASE + '/api/category/{id}',
            desc: 'Xóa danh mục theo ID.',
            urlParams: [
                { name: 'id', label: 'ID danh mục', placeholder: 'VD: 3', def: '' }
            ]
        },

        'post-login': {
            method: 'POST', mode: 'json',
            url: BASE + '/api/auth',
            desc: 'Đăng nhập bằng username (hoặc email) + password. Trả về "token" (Bearer) và thông tin user. Dán "token" vào ô Authorization ở các endpoint Tài khoản khác để test.',
            urlParams: [],
            needsAuth: false,
            jsonBody: JSON.stringify({ username: 'admin', password: 'password' }, null, 2)
        },
        'get-me': {
            method: 'GET', mode: 'none',
            url: BASE + '/api/auth',
            desc: 'Lấy thông tin tài khoản đang đăng nhập. Cần header Authorization: Bearer <token> (hoặc session/cookie sau khi đăng nhập).',
            urlParams: [],
            needsAuth: true
        },
        'delete-logout': {
            method: 'DELETE', mode: 'none',
            url: BASE + '/api/auth/logout',
            desc: 'Đăng xuất - huỷ token hiện tại và xoá session.',
            urlParams: [],
            needsAuth: true
        }
    };

    var currentKey = 'get-products';
    var methodColors = { GET:'#10b981', POST:'#f59e0b', PUT:'#3b82f6', DELETE:'#ef4444' };
    var methodTextC  = { GET:'#fff',    POST:'#000',    PUT:'#fff',    DELETE:'#fff' };

    /* ── Load endpoint ── */
    window.apiLoadEp = function (key) {
        currentKey = key;
        var ep = ENDPOINTS[key];

        // Nav active
        $('.api-nav-btn').removeClass('active');
        $('[onclick="apiLoadEp(\'' + key + '\')"]').addClass('active');

        // Badge + URL + desc
        $('#api-method-badge').text(ep.method)
            .css({ background: methodColors[ep.method], color: methodTextC[ep.method] });
        $('#api-url-display').text(ep.url);
        $('#api-ep-desc').text(ep.desc);

        // URL params
        var $pb = $('#api-params-body').empty();
        if (ep.urlParams && ep.urlParams.length > 0) {
            $('#api-params-panel').show();
            $.each(ep.urlParams, function (i, p) {
                $pb.append(
                    '<div class="field-row">' +
                        '<div class="field-label">' + p.label + ' <span class="field-badge">URL</span></div>' +
                        '<input type="text" class="form-control form-control-sm" id="apip_' + p.name + '" ' +
                               'placeholder="' + p.placeholder + '" value="' + p.def + '">' +
                    '</div>'
                );
            });
        } else {
            $('#api-params-panel').hide();
        }

        // Headers panel (Authorization Bearer)
        if (ep.needsAuth) {
            $('#api-headers-panel').show();
        } else {
            $('#api-headers-panel').hide();
        }

        // Hide all body panels
        $('#api-body-json-panel, #api-body-form-panel').hide();

        if (ep.mode === 'json') {
            $('#api-body-json-panel').show();
            $('#api-request-body').val(ep.jsonBody || '');
        } else if (ep.mode === 'form') {
            $('#api-body-form-panel').show();
            renderFormFields(ep.formFields || []);
        }

        // Reset response
        resetResponse();
    };

    /* ── Render form-data fields ── */
    function renderFormFields(fields) {
        var $container = $('#api-form-fields').empty();

        $.each(fields, function (i, f) {
            if (f.type === 'file') {
                // Zone kéo thả ảnh
                $container.append(
                    '<div class="field-row" style="align-items:start;">' +
                        '<div class="field-label">' + f.label + ' <span class="field-badge">file</span></div>' +
                        '<div>' +
                            '<div id="api-img-zone" onclick="document.getElementById(\'api-img-input\').click()">' +
                                '<i class="fa-solid fa-cloud-arrow-up fa-lg mb-2 d-block"></i>' +
                                '<div style="font-size:.82rem;">Nhấn hoặc kéo ảnh vào đây</div>' +
                                '<div style="font-size:.72rem;margin-top:4px;">JPG, PNG, GIF, WEBP — tối đa 5MB</div>' +
                                '<input type="file" id="api-img-input" accept="image/*">' +
                            '</div>' +
                            '<div id="api-img-preview">' +
                                '<img id="api-img-thumb" src="" alt="preview">' +
                                '<button class="img-remove-btn" onclick="apiRemoveImage(event)" title="Bỏ ảnh">✕</button>' +
                                '<div style="font-size:.74rem;color:#4ade80;margin-top:6px;" id="api-img-name"></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );
                // Bind events
                $('#api-img-input').on('change', function () {
                    apiHandleImageFile(this.files[0]);
                });
                // Drag & drop
                $('#api-img-zone').on('dragover', function (e) {
                    e.preventDefault();
                    $(this).addClass('dragover');
                }).on('dragleave', function () {
                    $(this).removeClass('dragover');
                }).on('drop', function (e) {
                    e.preventDefault();
                    $(this).removeClass('dragover');
                    var file = e.originalEvent.dataTransfer.files[0];
                    if (file) apiHandleImageFile(file);
                });
            } else {
                var reqBadge = f.required
                    ? '<span class="field-badge req">bắt buộc</span>'
                    : '<span class="field-badge">tùy chọn</span>';
                $container.append(
                    '<div class="field-row">' +
                        '<div class="field-label">' + f.label + ' ' + reqBadge + '</div>' +
                        '<input type="' + f.type + '" class="form-control form-control-sm" ' +
                               'id="apif_' + f.name + '" placeholder="' + f.name + '" value="' + f.def + '">' +
                    '</div>'
                );
            }
        });
    }

    /* ── Xử lý chọn ảnh ── */
    window.apiHandleImageFile = function (file) {
        if (!file) return;
        var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowed.includes(file.type)) {
            alert('Chỉ chấp nhận file ảnh JPG, PNG, GIF, WEBP');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('File ảnh tối đa 5MB');
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#api-img-thumb').attr('src', e.target.result);
            $('#api-img-name').text(file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
            $('#api-img-zone').hide();
            $('#api-img-preview').show();
        };
        reader.readAsDataURL(file);
    };

    window.apiRemoveImage = function (e) {
        e.stopPropagation();
        $('#api-img-input').val('');
        $('#api-img-thumb').attr('src', '');
        $('#api-img-preview').hide();
        $('#api-img-zone').show();
    };

    /* ── Gửi request ── */
    window.apiSend = function () {
        var ep  = ENDPOINTS[currentKey];
        var url = ep.url;

        // Thay URL params
        if (ep.urlParams) {
            $.each(ep.urlParams, function (i, p) {
                var val = $.trim($('#apip_' + p.name).val());
                url = url.replace('{' + p.name + '}', encodeURIComponent(val));
            });
        }

        var t0 = Date.now();
        $('#api-btn-send').prop('disabled', true);
        $('#api-spinner').addClass('show');
        resetResponse();

        // Header Authorization (Bearer token) nếu có nhập
        var headers = {};
        var authVal = $.trim($('#api-header-auth').val());
        if (authVal) {
            headers['Authorization'] = /^bearer\s/i.test(authVal) ? authVal : 'Bearer ' + authVal;
        }

        if (ep.mode === 'form') {
            // ── FormData (hỗ trợ file) ──
            var fd = new FormData();

            // Với PUT: thêm _method spoofing
            if (ep.method === 'PUT') {
                fd.append('_method', 'PUT');
            }

            $.each(ep.formFields || [], function (i, f) {
                if (f.type === 'file') {
                    var fileInput = document.getElementById('api-img-input');
                    if (fileInput && fileInput.files && fileInput.files.length > 0) {
                        fd.append('image', fileInput.files[0]);
                    }
                } else {
                    var val = $('#apif_' + f.name).val();
                    if (val !== '') fd.append(f.name, val);
                }
            });

            $.ajax({
                url:         url,
                method:      ep.method === 'PUT' ? 'POST' : ep.method,
                data:        fd,
                processData: false,
                contentType: false,
                dataType:    'text',
                headers:     headers,
                complete:    function (jqXHR) { handleResponse(jqXHR, Date.now() - t0); }
            });

        } else if (ep.mode === 'json') {
            // ── JSON body ──
            var raw = $.trim($('#api-request-body').val());
            var bodyData = null;
            if (raw) {
                try { bodyData = JSON.stringify(JSON.parse(raw)); }
                catch (e) {
                    showClientError('JSON không hợp lệ: ' + e.message);
                    return;
                }
            }
            $.ajax({
                url:         url,
                method:      ep.method,
                contentType: 'application/json',
                data:        bodyData,
                dataType:    'text',
                headers:     headers,
                complete:    function (jqXHR) { handleResponse(jqXHR, Date.now() - t0); }
            });

        } else {
            // ── GET / DELETE — không có body ──
            $.ajax({
                url:      url,
                method:   ep.method,
                dataType: 'text',
                headers:  headers,
                complete: function (jqXHR) { handleResponse(jqXHR, Date.now() - t0); }
            });
        }
    };

    /* ── Hiển thị response ── */
    function handleResponse(jqXHR, elapsed) {
        var code     = jqXHR.status;
        var bodyText = jqXHR.responseText || '';

        // Format JSON
        var pretty = bodyText;
        try { pretty = JSON.stringify(JSON.parse(bodyText), null, 2); } catch (e) {}

        $('#api-response-body')
            .text(pretty)
            .css('color', code < 300 ? '#a5f3fc' : '#fca5a5');

        // Nếu response trả về token (đăng nhập), tự điền vào ô Authorization
        try {
            var parsedAuth = JSON.parse(bodyText);
            if (parsedAuth && parsedAuth.token && code < 300) {
                $('#api-header-auth').val(parsedAuth.token);
            }
        } catch (e) {}

        // Nếu response có image path, hiển thị preview
        try {
            var parsed = JSON.parse(bodyText);
            // Khi thành công show ảnh nếu có (GET chi tiết)
            if (parsed && parsed.image && code < 300) {
                $('#api-res-image')
                    .html('<div style="font-size:.72rem;color:#64748b;margin-bottom:6px;"><i class="fa-solid fa-image me-1"></i>Preview ảnh sản phẩm:</div>' +
                          '<img src="' + BASE + '/' + parsed.image + '" alt="product" ' +
                               'onerror="$(this).hide()">')
                    .show();
            } else {
                $('#api-res-image').hide();
            }
        } catch(e) { $('#api-res-image').hide(); }

        var cls = 's2xx';
        if (code >= 400 && code < 500) cls = 's4xx';
        if (code >= 500) cls = 's5xx';

        $('#api-status-badge').text('HTTP ' + code).attr('class', 'res-status ms-auto ' + cls).show();
        $('#api-time-badge').text(elapsed + ' ms').show();
        $('#api-res-label').html('<i class="fa-solid fa-check me-1" style="color:#4ade80;"></i>Response:');

        $('#api-btn-send').prop('disabled', false);
        $('#api-spinner').removeClass('show');
    }

    function resetResponse() {
        $('#api-response-body').text('// Response sẽ hiển thị ở đây sau khi gửi request.').css('color', '#475569');
        $('#api-status-badge, #api-time-badge').hide();
        $('#api-res-label').html('<i class="fa-regular fa-clock me-1"></i>Chờ response...');
        $('#api-res-image').hide();
    }

    function showClientError(msg) {
        $('#api-response-body').text('// Lỗi phía client: ' + msg).css('color', '#fca5a5');
        $('#api-btn-send').prop('disabled', false);
        $('#api-spinner').removeClass('show');
    }

    // Init
    apiLoadEp('get-products');

})(jQuery);
</script>
