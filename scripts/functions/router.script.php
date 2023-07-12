<?php

class Router
{
    private static function app_route($path, $vars = [])
    {
        $routes = APP_ROUTES[$_SERVER['REQUEST_METHOD']];

        require_once APP_TEMPLATES . 'partials/header.php';

        if (array_key_exists($path, $routes)) {
            extract($vars, EXTR_SKIP);
            require APP_VIEW . $routes[$path][0];
        } else require APP_ERROR_PAGES . APP_ROUTES['ERR']['404'];

        require_once APP_TEMPLATES . 'partials/footer.php';
    }
    private static function api_route($path)
    {
        $routes = API_ROUTES[$_SERVER['REQUEST_METHOD']];
        if (array_key_exists($path, $routes)) {
            header("Content-Type: application/json");
            require API_VIEW . $routes[$path][0];
        } else require APP_ERROR_PAGES . API_ROUTES['ERR']['404'];
    }

    public static function route($uri, $vars = [])
    {
        $path = preg_replace('/' . preg_quote($_ENV['APP_ROUTE_ROOT'], '/') . '/', '', $uri, 1);

        $parts = explode('/', $path);

        if ($parts[1] == 'api') {
            array_shift($parts);
            array_shift($parts);
            $path = '/' . implode('/', $parts);
            Router::api_route($path);
            return;
        }
        Router::app_route($path, $vars);
    }
}