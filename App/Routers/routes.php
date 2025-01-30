<?php
use App\Controllers\DestinationController;
use App\Controllers\RoomController;
use App\Controllers\UserController;
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
// Users
$router->get('v1', '/users', UserController::class, 'index','owners');
$router->get('v1', '/users/{id}', UserController::class, 'get');
$router->post('v1', '/users', UserController::class, 'store',inaccessabilty: 'geust');
$router->put('v1', '/users/{id}', UserController::class, 'update',inaccessabilty: 'geust');
$router->delete('v1', '/users/{id}', UserController::class, 'destroy' ,'owners');
// weathers
$router->get('v1', '/weathers', WeatherController::class, 'index','owners');
$router->get('v1', '/weathers/{id}', WeatherController::class, 'get','owners');
$router->put('v1', '/weathers/{id}', WeatherController::class, 'update','owners');
$router->delete('v1', '/weathers/{id}', WeatherController::class, 'destroy','owners');
$router->post('v1', '/weathers', WeatherController::class, 'store','owners');
// Destentions
$router->get('v1', '/destinations', DestinationController::class, 'index','owners');
$router->get('v1', '/destinations/{id}', DestinationController::class, 'get','owners');
$router->post('v1', '/destinations', DestinationController::class, 'store','owners');
$router->put('v1', '/destinations/{id}', DestinationController::class, 'update','owners');
$router->delete('v1', '/destinations/{id}', DestinationController::class, 'destroy');
// Rooms
$router->get('v1', '/rooms', RoomController::class, 'index');
$router->get('v1', '/rooms/{id}', RoomController::class, 'get');
$router->post('v1', '/rooms', RoomController::class, 'store',inaccessabilty: 'geust');
$router->put('v1', '/rooms/{id}', RoomController::class, 'update',inaccessabilty: 'geust');
$router->delete('v1', '/rooms/{id}', RoomController::class, 'destroy' ,'owners');
$router->post('v1', '/room/like', RoomController::class, 'room_like');
$router->post('v1', '/room/reserve', RoomController::class, 'room_reserve');
//features
$router->post('v1', '/room/append_feature', RoomController::class, 'append_feature',inaccessabilty: 'geust');
$router->post('v1', '/room/feature', RoomController::class, 'add_feature',inaccessabilty: 'geust');