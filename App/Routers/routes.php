<?php
use App\Controllers\DestinationController;
use App\Routers\Router as Router;
use App\Middlewares\AuthMiddleware;

// use Controllers

use App\Controllers\AuthController;
use App\Controllers\WeatherController;


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
$router->get('v1', '/weathers', WeatherController::class, 'index');
$router->get('v1', '/weathers/{id}', WeatherController::class, 'get');
$router->put('v1', '/weathers/{id}', WeatherController::class, 'update');
$router->delete('v1', '/weathers/{id}', WeatherController::class, 'destroy');
$router->post('v1', '/weathers', WeatherController::class, 'store');
// Destentions
$router->get('v1', '/destinations', DestinationController::class, 'index');
$router->get('v1', '/destinations/{id}', DestinationController::class, 'get');
$router->post('v1', '/destinations', DestinationController::class, 'store');
$router->put('v1', '/destinations/{id}', DestinationController::class, 'update');
$router->delete('v1', '/destinations/{id}', DestinationController::class, 'destroy');