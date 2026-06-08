<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'app/helpers/auth_helper.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = isset($url[0]) && $url[0] != ''
    ? ucfirst($url[0]) . 'Controller'
    : 'DefaultController';

$action = isset($url[1]) && $url[1] != ''
    ? $url[1]
    : 'index';

// -------------------------------------------------------
// ĐỊNH TUYẾN API: /project1/api/{resource}/{id?}
// -------------------------------------------------------
if (strtolower($url[0]) === 'api') {
    header('Content-Type: application/json');

    $resource = $url[1] ?? '';
    if (empty($resource)) {
        http_response_code(404);
        echo json_encode(['message' => 'API resource not specified']);
        exit;
    }

    $apiControllerName = ucfirst(strtolower($resource)) . 'ApiController';
    $apiControllerFile = 'app/controllers/' . $apiControllerName . '.php';

    if (!file_exists($apiControllerFile)) {
        http_response_code(404);
        echo json_encode(['message' => 'API controller not found: ' . $apiControllerName]);
        exit;
    }

    // Đọc body JSON một lần, lưu vào GLOBALS để controller dùng lại
    $bodyRaw = file_get_contents('php://input');
    $GLOBALS['_API_BODY'] = $bodyRaw ? (json_decode($bodyRaw, true) ?? []) : [];

    // Lấy id từ URL, fallback sang body
    $id = isset($url[2]) && $url[2] != '' ? $url[2] : null;
    if (!$id && !empty($GLOBALS['_API_BODY']['id'])) {
        $id = $GLOBALS['_API_BODY']['id'];
    }

    $method = $_SERVER['REQUEST_METHOD'];

    // Hỗ trợ method spoofing: POST + _method=PUT/DELETE (cần cho FormData upload file)
    if ($method === 'POST') {
        $override = $_POST['_method'] ?? $GLOBALS['_API_BODY']['_method'] ?? '';
        if (in_array(strtoupper($override), ['PUT', 'DELETE'])) {
            $method = strtoupper($override);
        }
    }

    switch ($method) {
        case 'GET':
            $apiAction = $id ? 'show' : 'index';
            break;
        case 'POST':
            $apiAction = 'store';
            break;
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'Missing id — dùng PUT /api/product/{id}']);
                exit;
            }
            $apiAction = 'update';
            break;
        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'Missing id — dùng DELETE /api/product/{id}']);
                exit;
            }
            $apiAction = 'destroy';
            break;
        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            exit;
    }

    require_once $apiControllerFile;
    $controller = new $apiControllerName();

    if (method_exists($controller, $apiAction)) {
        if (in_array($apiAction, ['show', 'update', 'destroy'])) {
            $controller->$apiAction($id);
        } else {
            $controller->$apiAction();
        }
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'API action not found']);
    }
    exit;
}

// -------------------------------------------------------
// ĐỊNH TUYẾN THÔNG THƯỜNG
// -------------------------------------------------------
$controllerFile = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    die('<h2>404 - Không tìm thấy trang: ' . htmlspecialchars($controllerName) . '</h2>');
}

require_once $controllerFile;
$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    die('<h2>404 - Không tìm thấy hành động: ' . htmlspecialchars($action) . '</h2>');
}

call_user_func_array([$controller, $action], array_slice($url, 2));
?>
