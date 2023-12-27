<?php
    namespace Controllers;
    use MVC\Router;

    class LoginController {

        public static function login(Router $router) {
            debugger("Desde login");
            $router->render('auth/login.php', []);
        }

        public static function logout(Router $router) {
            debugger("Desde logout");
            $router->render('auth/login.php', []);
        }

        public static function register(Router $router) {
            debugger("Desde register");
            $router->render('auth/register.php', []);
        }

        public static function forget(Router $router) {
            debugger("Desde forget");
            $router->render('auth/login.php', []);
        }

        public static function recovery(Router $router) {
            debugger("Desde recovery");
            $router->render('auth/recovery.php', []);
        }
    }