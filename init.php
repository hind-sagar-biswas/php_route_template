<?php
session_start();

// Pre requires
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes/app.php';
require_once __DIR__ . '/routes/api.php';

// Loads
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

// Usage
$dotenv->safeLoad();

//// CONSTANTS
// Statics
define('NODE_MODULE', $_ENV['APP_URL'] . '/node_modules/');
define('APP_STATIC', $_ENV['APP_URL'] . '/assets/');
define('APP_IMAGES', APP_STATIC . 'images/');
define('APP_STYLES', APP_STATIC . 'css/');
define('APP_JS', APP_STATIC . 'js/');
// Directories
define('APP_BASE_DIR', __DIR__ . '/');
define('API_VIEW', __DIR__ . $_ENV['API_ROUTE_ROOT'] . '/');
define('APP_VIEW', __DIR__ . '/views/');
define('APP_ERROR_PAGES', __DIR__ . '/errors/');
define('APP_FUNCTION', __DIR__ . '/scripts/functions/');
define('APP_CLASS', __DIR__ . '/scripts/classes/');
define('APP_TEMPLATES', __DIR__ . '/templates/');
define('APP_COMPONENTS', APP_TEMPLATES . 'components/');


// Requires
require_once APP_FUNCTION . 'functions.script.php';
require_once APP_FUNCTION . 'router.script.php';
require_once APP_CLASS . 'db.class.php';