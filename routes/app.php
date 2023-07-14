<?php

$APP_ERROR_ROUTES = [
    '404' => '404.php'
];

/****************
 * $APP_{method}_ROUTES = [
 *  '/' => ['index.php', 'root']
 *  '/route/' => ['file', 'name']
 * ]
 * 
 */

$APP_GET_ROUTES = [
    '/' => ['index.php', 'root'],
];

$APP_POST_ROUTES = [
    '/' => ['index.php', 'root'],
];


define('APP_ROUTES', [
    'err' => $APP_ERROR_ROUTES,
    'get' => $APP_GET_ROUTES,
    'post' => $APP_POST_ROUTES,
]);
