<?php

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'app/helpers/auth_helper.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// CONTROLLER
$controllerName = isset($url[0]) && $url[0] != ''
    ? ucfirst($url[0]) . 'Controller'
    : 'DefaultController';

// ACTION
$action = isset($url[1]) && $url[1] != ''
    ? $url[1]
    : 'index';

// KIỂM TRA CONTROLLER
$controllerFile = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    die('<h2>404 - Không tìm thấy trang: ' . htmlspecialchars($controllerName) . '</h2>');
}

require_once $controllerFile;

$controller = new $controllerName();

// KIỂM TRA ACTION
if (!method_exists($controller, $action)) {
    http_response_code(404);
    die('<h2>404 - Không tìm thấy hành động: ' . htmlspecialchars($action) . '</h2>');
}

// GỌI ACTION
call_user_func_array(
    [$controller, $action],
    array_slice($url, 2)
);
?>
