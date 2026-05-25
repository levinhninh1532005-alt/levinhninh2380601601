<?php

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'app/models/ProductModel.php';

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

// DEBUG
// die("controller=$controllerName - action=$action");

// KIỂM TRA CONTROLLER
if (!file_exists('app/controllers/' . $controllerName . '.php')) {

    die('Controller not found');
}

require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

// KIỂM TRA ACTION
if (!method_exists($controller, $action)) {

    die('Action not found');
}

// GỌI ACTION
call_user_func_array(
    [$controller, $action],
    array_slice($url, 2)
);
?>