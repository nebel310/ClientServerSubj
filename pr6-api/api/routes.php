<?php
require_once __DIR__ . '/../controllers/UserController.php';

$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/api/v1';
$path = parse_url($requestUri, PHP_URL_PATH);

if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Endpoint not found.']);
    return;
}

$method = $_SERVER['REQUEST_METHOD'];
$pathParts = array_values(array_filter(explode('/', $path)));

if ($pathParts === ['register'] && $method === 'POST') {
    UserController::register();
    return;
}

if ($pathParts === ['login'] && $method === 'POST') {
    UserController::login();
    return;
}

if ($pathParts === ['users'] && $method === 'GET') {
    UserController::getUsers();
    return;
}

if (count($pathParts) === 2 && $pathParts[0] === 'users' && is_numeric($pathParts[1])) {
    $id = (int) $pathParts[1];
    switch ($method) {
        case 'GET':
            UserController::getUser($id);
            break;
        case 'PUT':
        case 'PATCH':
            UserController::updatePassword($id);
            break;
        case 'DELETE':
            UserController::deleteUser($id);
            break;
        default:
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    }
    return;
}

http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Endpoint not found.']);