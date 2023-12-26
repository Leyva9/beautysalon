<?php 

require_once './includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\PagesController;

$router = new Router();

// index
$router->get('/', [PagesController::class, 'index']);

// login
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get('/register', [LoginController::class, 'register']);
$router->post('/register', [LoginController::class, 'register']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->checkRoutes();