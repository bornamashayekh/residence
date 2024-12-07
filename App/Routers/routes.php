<?php
use App\Controllers\DestinationController;
use App\Controllers\RoomController;
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
$router->get('v1', '/weathers', WeatherController::class, 'index',['support','admin']);
$router->get('v1', '/weathers/{id}', WeatherController::class, 'get',['support','admin']);
$router->put('v1', '/weathers/{id}', WeatherController::class, 'update',['support','admin']);
$router->delete('v1', '/weathers/{id}', WeatherController::class, 'destroy',['support','admin']);
$router->post('v1', '/weathers', WeatherController::class, 'store',['support','admin']);
// Destentions
$router->get('v1', '/destinations', DestinationController::class, 'index',['support','admin']);
$router->get('v1', '/destinations/{id}', DestinationController::class, 'get',['support','admin']);
$router->post('v1', '/destinations', DestinationController::class, 'store',['support','admin']);
$router->put('v1', '/destinations/{id}', DestinationController::class, 'update',['support','admin']);
$router->delete('v1', '/destinations/{id}', DestinationController::class, 'destroy');
// Rooms
$router->get('v1', '/rooms', RoomController::class, 'index',['host','support','admin']);
$router->post('v1', '/rooms', RoomController::class, 'store',['host','support','admin']);