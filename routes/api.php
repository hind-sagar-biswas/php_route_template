<?php

$API_ERROR_ROUTES = [
    '404' => '404.php'
];

/****************
 * $API_{method}_ROUTES = [
 *  '/' => ['index.php', 'root']
 *  '/route/' => ['file', 'name']
 * ]
 * 
 */
$API_GET_ROUTES = [
    '/' => ['index.php', 'root']
];

$API_POST_ROUTES = [];


define('API_ROUTES', [
    'ERR' => $API_ERROR_ROUTES,
    'GET' => $API_GET_ROUTES,
    'POST' => $API_POST_ROUTES,
]);
