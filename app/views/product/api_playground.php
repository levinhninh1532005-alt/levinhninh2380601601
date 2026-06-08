<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Playground — MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', monospace; }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px; min-height: 100vh; background: #1e293b;
            border-right: 1px solid #334155; padding: 0;
        }
        .sidebar-header {
            background: linear-gradient(135deg,#6366f1,#4f46e5);
            padding: 20px; font-size: 1.1rem; font-weight: 700; color: #fff;
        }
        .nav-group-title {
            font-size: .7rem; letter-spacing: .12em; color: #64748b;
            text-transform: uppercase; padding: 16px 20px 4px;
        }
        .nav-item button {
            width: 100%; background: none; border: none; text-align: left;
            padding: 10px 20px; color: #94a3b8; font-size: .875rem;
            cursor: pointer; transition: .2s; border-left: 3px solid transparent;
        }
        .nav-item button:hover  { background: #334155; color: #e2e8f0; }
        .nav-item button.active { background: #334155; color: #fff; border-left-color: #6366f1; }
        .badge-method {
            display: inline-block; font-size: .65rem; font-weight: 700;
            padding: 2px 7px; border-radius: 4px; margin-right: 8px;
        }
        .badge-GET    { background:#10b981; color:#fff; }
        .badge-POST   { background:#f59e0b; color:#000; }
        .badge-PUT    { background:#3b82f6; color:#fff; }
        .badge-DELETE { background:#ef4444; color:#fff; }

        /* ── Main ── */
        .main { flex: 1; padding: 32px; max-width: 900px; }
        .endpoint-header {
            display: flex; align-items: center; gap: 12px; margin-bottom: 24px;
        }
        .method-badge {
            font-size: .9rem; font-weight: 700; padding: 6px 14px;
            border-radius: 6px; letter-spacing: .05em;
        }
        .url-display {
            background: #1e293b; border: 1px solid #334155; border-radius: 8px;
            padding: 10px 16px; font-family: monospace; font-size: .875rem;
            color: #94a3b8; flex: 1;
        }

        /* ── Params / Body panels ── */
        .panel { background: #1e293b; border: 1px solid #334155; border-radius: 12px; margin-bottom: 20px; }
        .panel-title { padding: 14px 20px; font-size: .8rem; letter-spacing: .1em;
                       text-transform: uppercase; color: #64748b; border-bottom: 1px solid #334155; }
        .panel-body { padding: 20px; }
        .form-control, .form-select {
            background: #0f172a !important; border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.25) !important;
        }
        .form-label { color: #94a3b8; font-size: .8rem; margin-bottom: 4px; }

        /* ── Response ── */
        .response-panel { background: #020617; border: 1px solid #334155;
                          border-radius: 12px; overflow: hidden; }
        .response-header { padding: 12px 20px; background: #1e293b;
                           border-bottom: 1px solid #334155; display: flex;
                           align-items: center; gap: 12px; }
        .status-badge { font-size: .8rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .status-2xx { background: #064e3b; color: #6ee7b7; }
        .status-4xx { background: #7f1d1d; color: #fca5a5; }
        .status-5xx { background: #4c1d95; color: #c4b5fd; }
        .response-time { font-size: .75rem; color: #64748b; }
        pre#responseBody {
            margin: 0; padding: 24px; font-size: .82rem; line-height: 1.6;
            color: #a5f3fc; max-height: 480px; overflow-y: auto;
            white-space: pre-wrap; word-break: break-word;
        }
        .btn-send {
            background: linear-gradient(135deg,#6366f1,#4f46e5);
            border: none; color: #fff; padding: 12px 32px;
            border-radius: 8px; font-weight: 600; font-size: .95rem;
            cursor: pointer; transition: opacity .2s;
        }
        .btn-send:hover   { opacity: .85; }
        .btn-send:active  { opacity: .7; }
        .btn-send:disabled { opacity: .5; cursor: not-allowed; }
        .spinner { display: none; }
        .spinner.show { display: inline-block; }
    </style>
</head>
<body class="d-flex" style="min-height:100vh;">

<!-- ════════════════════ SIDEBAR ════════════════════ -->
<div class="sidebar d-flex flex-column">
    <div class="sidebar-header">
        <i class="fa-solid fa-bolt me-2"></i> API Playground
        <div style="font-size:.75rem;font-weight:400;margin-top:4px;opacity:.8;">MyStore RESTful API</div>
    </div>

    <div class="nav-group-title">Sản phẩm</div>
    <div class="nav-item">
        <button class="active" onclick="loadEndpoint('get-products')">
            <span class="badge-method badge-GET">GET</span> Danh sách SP
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('get-product')">
            <span class="badge-method badge-GET">GET</span> Chi tiết SP
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('search-product')">
            <span class="badge-method badge-GET">GET</span> Tìm kiếm SP
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('post-product')">
            <span class="badge-method badge-POST">POST</span> Thêm SP
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('put-product')">
            <span class="badge-method badge-PUT">PUT</span> Cập nhật SP
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('delete-product')">
            <span class="badge-method badge-DELETE">DELETE</span> Xóa SP
        </button>
    </div>

    <div class="nav-group-title">Danh mục</div>
    <div class="nav-item">
        <button onclick="loadEndpoint('get-categories')">
            <span class="badge-method badge-GET">GET</span> Danh sách DM
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('post-category')">
            <span class="badge-method badge-POST">POST</span> Thêm DM
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('put-category')">
            <span class="badge-method badge-PUT">PUT</span> Cập nhật DM
        </button>
    </div>
    <div class="nav-item">
        <button onclick="loadEndpoint('delete-category')">
            <span class="badge-method badge-DELETE">DELETE</span> Xóa DM
        </button>
    </div>

    <!-- Shortcuts -->
    <div class="mt-auto p-3 text-center" style="border-top:1px solid #334155;">
        <a href="/project1/Product/list" class="btn btn-sm btn-outline-secondary w-100">
            <i class="fa-solid fa-arrow-left me-1"></i> Về trang sản phẩm
        </a>
    </div>
</div>

<!-- ════════════════════ MAIN ════════════════════ -->
<div class="main">

    <!-- Endpoint header -->
    <div class="endpoint-header">
        <span id="methodBadge" class="method-badge badge-GET" style="background:#10b981;color:#fff;">GET</span>
        <div class="url-display" id="urlDisplay">/project1/ProductApi/index</div>
    </div>

    <!-- Description -->
    <p id="endpointDesc" class="text-secondary mb-4" style="font-size:.875rem;">
        Lấy danh sách tất cả sản phẩm. Hỗ trợ lọc theo keyword và category_id.
    </p>

    <!-- Params panel -->
    <div class="panel" id="paramsPanel">
        <div class="panel-title"><i class="fa-solid fa-filter me-2"></i>Query Parameters</div>
        <div class="panel-body" id="paramsBody">
            <!-- Điền động bởi JS -->
        </div>
    </div>

    <!-- Body panel -->
    <div class="panel" id="bodyPanel" style="display:none;">
        <div class="panel-title"><i class="fa-solid fa-code me-2"></i>Request Body (JSON)</div>
        <div class="panel-body">
            <textarea id="requestBody" class="form-control" rows="8"
                      style="font-family:monospace;font-size:.82rem;"></textarea>
        </div>
    </div>

    <!-- Send button -->
    <div class="mb-4">
        <button class="btn-send" id="btnSend" onclick="sendRequest()">
            <span class="spinner spinner-border spinner-border-sm me-2" id="spinner"></span>
            <i class="fa-solid fa-paper-plane me-2"></i> Gửi Request
        </button>
    </div>

    <!-- Response -->
    <div class="response-panel">
        <div class="response-header">
            <span class="response-time"><i class="fa-solid fa-clock me-1"></i>Chờ response...</span>
            <span id="statusBadge" class="status-badge ms-auto" style="display:none;"></span>
            <span id="timeBadge" class="response-time" style="display:none;"></span>
        </div>
        <pre id="responseBody" style="color:#64748b; font-style:italic;">// Response sẽ hiển thị ở đây sau khi gửi request.</pre>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// ════════════════════════════════════════════════════════════
//  Cấu hình endpoints
// ════════════════════════════════════════════════════════════
const BASE = '/project1';

const ENDPOINTS = {
    'get-products': {
        method: 'GET',
        url: BASE + '/ProductApi/index',
        desc: 'Lấy danh sách tất cả sản phẩm. Hỗ trợ lọc và phân trang.',
        params: [
            { name: 'keyword',     label: 'Từ khoá tìm kiếm', placeholder: 'VD: laptop',  default: '' },
            { name: 'category_id', label: 'ID danh mục',       placeholder: 'VD: 1',        default: '' },
            { name: 'page',        label: 'Trang',             placeholder: '1',             default: '1' },
            { name: 'limit',       label: 'Số SP / trang',     placeholder: '10',            default: '10' },
        ],
        body: null,
    },
    'get-product': {
        method: 'GET',
        url: BASE + '/ProductApi/show/{id}',
        desc: 'Lấy chi tiết 1 sản phẩm theo ID.',
        params: [
            { name: 'id', label: 'ID sản phẩm', placeholder: 'VD: 1', default: '1', urlParam: true },
        ],
        body: null,
    },
    'search-product': {
        method: 'GET',
        url: BASE + '/ProductApi/search',
        desc: 'Tìm kiếm sản phẩm theo từ khoá trong tên và mô tả.',
        params: [
            { name: 'keyword', label: 'Từ khoá *', placeholder: 'VD: điện thoại', default: 'laptop' },
        ],
        body: null,
    },
    'post-product': {
        method: 'POST',
        url: BASE + '/ProductApi/store',
        desc: 'Tạo sản phẩm mới. Gửi JSON body với các trường bắt buộc.',
        params: [],
        body: JSON.stringify({
            name: 'Sản phẩm mới',
            description: 'Mô tả sản phẩm mới được thêm qua API',
            price: 1500000,
            category_id: 1,
            image: ''
        }, null, 2),
    },
    'put-product': {
        method: 'PUT',
        url: BASE + '/ProductApi/update/{id}',
        desc: 'Cập nhật sản phẩm. Chỉ truyền các trường cần sửa (partial update).',
        params: [
            { name: 'id', label: 'ID sản phẩm *', placeholder: 'VD: 1', default: '1', urlParam: true },
        ],
        body: JSON.stringify({
            name: 'Tên đã cập nhật',
            price: 2000000
        }, null, 2),
    },
    'delete-product': {
        method: 'DELETE',
        url: BASE + '/ProductApi/destroy/{id}',
        desc: 'Xoá sản phẩm theo ID. Thao tác này không thể hoàn tác.',
        params: [
            { name: 'id', label: 'ID sản phẩm *', placeholder: 'VD: 5', default: '', urlParam: true },
        ],
        body: null,
    },
    'get-categories': {
        method: 'GET',
        url: BASE + '/CategoryApi/index',
        desc: 'Lấy danh sách tất cả danh mục sản phẩm.',
        params: [],
        body: null,
    },
    'post-category': {
        method: 'POST',
        url: BASE + '/CategoryApi/store',
        desc: 'Tạo danh mục mới. Tên danh mục phải là duy nhất.',
        params: [],
        body: JSON.stringify({ name: 'Danh mục mới', description: 'Mô tả danh mục' }, null, 2),
    },
    'put-category': {
        method: 'PUT',
        url: BASE + '/CategoryApi/update/{id}',
        desc: 'Cập nhật thông tin danh mục theo ID.',
        params: [
            { name: 'id', label: 'ID danh mục *', placeholder: 'VD: 1', default: '1', urlParam: true },
        ],
        body: JSON.stringify({ name: 'Tên DM mới', description: 'Mô tả cập nhật' }, null, 2),
    },
    'delete-category': {
        method: 'DELETE',
        url: BASE + '/CategoryApi/destroy/{id}',
        desc: 'Xoá danh mục theo ID.',
        params: [
            { name: 'id', label: 'ID danh mục *', placeholder: 'VD: 3', default: '', urlParam: true },
        ],
        body: null,
    },
};

// ════════════════════════════════════════════════════════════
//  State
// ════════════════════════════════════════════════════════════
let currentKey = 'get-products';

function loadEndpoint(key) {
    currentKey = key;
    const ep = ENDPOINTS[key];

    // Active nav
    $('nav-item button, .nav-item button').removeClass('active');
    $(`[onclick="loadEndpoint('${key}')"]`).addClass('active');

    // Method badge
    const methodColors = { GET:'#10b981', POST:'#f59e0b', PUT:'#3b82f6', DELETE:'#ef4444' };
    const methodText   = { GET:'#fff',    POST:'#000',    PUT:'#fff',    DELETE:'#fff' };
    $('#methodBadge')
        .text(ep.method)
        .css({ background: methodColors[ep.method], color: methodText[ep.method] });

    // URL display
    $('#urlDisplay').text(ep.url);

    // Description
    $('#endpointDesc').text(ep.desc);

    // Params
    const $paramsBody = $('#paramsBody').empty();
    if (ep.params.length > 0) {
        $('#paramsPanel').show();
        ep.params.forEach(p => {
            $paramsBody.append(`
                <div class="mb-3">
                    <label class="form-label">${p.label}
                        ${p.urlParam ? '<span class="badge bg-secondary ms-1" style="font-size:.65rem;">URL Param</span>' : ''}
                    </label>
                    <input type="text" class="form-control" id="param_${p.name}"
                           name="${p.name}" placeholder="${p.placeholder}"
                           value="${p.default}" ${p.urlParam ? 'data-url-param="true"' : ''}>
                </div>`);
        });
    } else {
        $('#paramsPanel').hide();
    }

    // Body
    if (ep.body !== null) {
        $('#bodyPanel').show();
        $('#requestBody').val(ep.body);
    } else {
        $('#bodyPanel').hide();
        $('#requestBody').val('');
    }

    // Reset response
    $('#responseBody').text('// Response sẽ hiển thị ở đây sau khi gửi request.').css('color','#64748b');
    $('#statusBadge, #timeBadge').hide();
    $('span.response-time:first').text('Chờ response...');
}

// ════════════════════════════════════════════════════════════
//  Gửi Request (jQuery AJAX)
// ════════════════════════════════════════════════════════════
function sendRequest() {
    const ep = ENDPOINTS[currentKey];
    let url  = ep.url;

    // Thay URL params
    ep.params.filter(p => p.urlParam).forEach(p => {
        const val = $(`#param_${p.name}`).val().trim();
        url = url.replace(`{${p.name}}`, encodeURIComponent(val));
    });

    // Query string
    const queryParams = {};
    ep.params.filter(p => !p.urlParam).forEach(p => {
        const val = $(`#param_${p.name}`).val().trim();
        if (val) queryParams[p.name] = val;
    });

    let ajaxOpts = {
        url: url,
        method: ep.method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        dataType: 'text',
    };

    if (['GET','DELETE'].includes(ep.method) && Object.keys(queryParams).length > 0) {
        ajaxOpts.data = queryParams;
    }

    if (['POST','PUT'].includes(ep.method)) {
        const bodyRaw = $('#requestBody').val().trim();
        if (bodyRaw) {
            try { ajaxOpts.data = JSON.stringify(JSON.parse(bodyRaw)); }
            catch (e) { showError('JSON không hợp lệ: ' + e.message); return; }
        }
    }

    // For PUT/DELETE via jQuery, thêm _method override nếu cần
    // (server của chúng ta đã xử lý HEAD/OPTIONS, jQuery gửi thẳng PUT/DELETE)
    const t0 = Date.now();
    $('#btnSend').prop('disabled', true);
    $('#spinner').addClass('show');

    $.ajax(ajaxOpts)
        .always(function(data, textStatus, jqXHR) {
            const elapsed   = Date.now() - t0;
            const status    = jqXHR.status || (typeof data === 'object' ? data.status : 0);
            const bodyText  = typeof data === 'string' ? data : (jqXHR.responseText || '');

            let pretty = bodyText;
            try { pretty = JSON.stringify(JSON.parse(bodyText), null, 2); } catch(e) {}

            $('#responseBody').text(pretty).css('color', status < 300 ? '#a5f3fc' : '#fca5a5');

            let badgeClass = 'status-2xx';
            if (status >= 400 && status < 500) badgeClass = 'status-4xx';
            if (status >= 500)                  badgeClass = 'status-5xx';

            $('#statusBadge').text('HTTP ' + status).attr('class', 'status-badge ms-auto ' + badgeClass).show();
            $('#timeBadge').text(elapsed + ' ms').show();
            $('span.response-time:first').text('Response nhận được:');

            $('#btnSend').prop('disabled', false);
            $('#spinner').removeClass('show');
        });
}

function showError(msg) {
    $('#responseBody').text('// Lỗi phía client: ' + msg).css('color','#fca5a5');
    $('#btnSend').prop('disabled', false);
    $('#spinner').removeClass('show');
}

// ════════════════════════════════════════════════════════════
//  Init
// ════════════════════════════════════════════════════════════
loadEndpoint('get-products');
</script>
</body>
</html>
