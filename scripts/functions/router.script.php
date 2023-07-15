<?php

/**
 * The Router class handles routing and dispatching of requests.
 */
class Router
{
    /**
     * Handles application routes.
     *
     * @param string $path The route path.
     * @param array $vars An associative array of variables to pass to the route view.
     * @return void
     */
    private static function app_route($path, $vars = [])
    {
        $routes = APP_ROUTES[REQUEST['method']];

        $flash_msg = null;
        if (isset($_SESSION['flash_msg'])) {
            $flash_msg = $_SESSION['flash_msg'];
            unset($_SESSION['flash_msg']);
        }

        // Include the header partial
        require_once APP_TEMPLATES . 'partials/header.php';

        if (array_key_exists($path, $routes)) {
            extract($vars, EXTR_SKIP);
            require APP_VIEW . $routes[$path][0];
        } else {
            require APP_ERROR_PAGES . APP_ROUTES['err']['404'];
        }

        // Include the footer partial
        require_once APP_TEMPLATES . 'partials/footer.php';
    }

    /**
     * Handles API routes.
     *
     * @param string $path The route path.
     * @return void
     */
    private static function api_route($path)
    {
        $routes = API_ROUTES[REQUEST['method']];
        if (array_key_exists($path, $routes)) {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            require API_VIEW . $routes[$path][0];
        } else require APP_ERROR_PAGES . API_ROUTES['err']['404'];
    }

    /**
     * Routes and dispatches the request.
     *
     * @param string $uri The request URI.
     * @param array $vars An associative array of variables to pass to the route view.
     * @return void
     */
    public static function route($uri, $vars = [])
    {
        $path = preg_replace('/' . preg_quote(APP_ROOT, '/') . '/', '', $uri, 1);
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
