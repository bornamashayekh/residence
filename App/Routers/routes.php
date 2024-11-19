<?php
use App\Routers\Router as Router;
use App\Middlewares\AuthMiddleware;

// use Controllers

use App\Controllers\AuthController;
use App\Controllers\WeathersController;


// ایجاد یک نمونه از میدلور
$authMiddleware = new AuthMiddleware();
$request = getTokenFromRequest();

$response = $authMiddleware->handle($request);

// print_r(getallheaders());

$router = new Router();

// Define routes
$router->post('v1','/login', AuthController::class, 'login');
$router->post('v1','/register', AuthController::class, 'register');
$router->post('v1','/verify', AuthController::class, 'verify');
// weathers
$router->get('v1', '/weathers', WeathersController::class, 'index');
$router->post('v1', '/weathers', WeathersController::class, 'store');