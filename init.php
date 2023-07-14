<?php
session_start();

// Pre requires
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes/app.php';
require_once __DIR__ . '/routes/api.php';

// Load Env Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

//// CONSTANTS
// App Info
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_DEBUG', $_ENV['APP_DEBUG']);
define('APP_URL', $_ENV['APP_URL']);
define('APP_ROOT', $_ENV['APP_ROUTE_ROOT']);
define('APP_API_ROOT', $_ENV['API_ROUTE_ROOT']);
// Static files link
define('NODE_MODULE', APP_URL . '/node_modules/');
define('APP_STATIC', APP_URL . '/assets/');
define('APP_IMAGES', APP_STATIC . 'images/');
define('APP_STYLES', APP_STATIC . 'css/');
define('APP_JS', APP_STATIC . 'js/');
// Directories
define('APP_BASE_DIR', __DIR__ . '/');
define('API_VIEW', __DIR__ . APP_API_ROOT . '/');
define('APP_VIEW', __DIR__ . '/views/');
define('APP_ERROR_PAGES', __DIR__ . '/errors/');
define('APP_FUNCTION', __DIR__ . '/scripts/functions/');
define('APP_CLASS', __DIR__ . '/scripts/classes/');
define('APP_TEMPLATES', __DIR__ . '/templates/');
define('APP_COMPONENTS', APP_TEMPLATES . 'components/');
// Routes
define('APP_ROUTES', [
    'err' => $APP_ERROR_ROUTES,
    'get' => $APP_GET_ROUTES,
    'post' => $APP_POST_ROUTES,
]);
define('API_ROUTES', [
    'err' => $API_ERROR_ROUTES,
    'get' => $API_GET_ROUTES,
    'post' => $API_POST_ROUTES,
]);


// Parse Request
$request_uri = $_SERVER['REQUEST_URI'];
$parsed_uri = parse_url($request_uri);
$req_path = $parsed_uri['path'];
$req_query_string = isset($parsed_uri['query']) ? $parsed_uri['query'] : null;
parse_str($req_query_string, $req_query);

define('REQUEST', [
    'route' => preg_replace('/' . preg_quote(APP_ROOT, '/') . '/', '', $req_path, 1),
    'method' => strtolower($_SERVER['REQUEST_METHOD']),
    'query' => $req_query,
    'request' => ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET
]);

// Requires
require_once APP_FUNCTION . 'functions.script.php';
require_once APP_FUNCTION . 'router.script.php';
require_once APP_CLASS . 'db.class.php';