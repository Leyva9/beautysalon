<?php 

require_once './includes/app.php';

use MVC\Router;

$router = new Router();




// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->checkRoutes();