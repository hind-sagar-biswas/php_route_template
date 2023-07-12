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

$APP_POST_ROUTES = [];


define('APP_ROUTES', [
    'ERR' => $APP_ERROR_ROUTES,
    'GET' => $APP_GET_ROUTES,
    'POST' => $APP_POST_ROUTES,
]);
