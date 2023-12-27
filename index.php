<?php 

require_once './includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\PagesController;

$router = new Router();

// ** login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// ** password recovery
$router->get('/forget', [LoginController::class, 'forget']);
$router->post('/forget', [LoginController::class, 'forget']);
$router->get('/recovery', [LoginController::class, 'recovery']);
$router->post('/recovery', [LoginController::class, 'recovery']);

// ** create new account
$router->get('/register', [LoginController::class, 'register']);
$router->post('/register', [LoginController::class, 'register']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->checkRoutes();