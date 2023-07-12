<?php

require_once __DIR__ . '/init.php';

$request_uri = $_SERVER['REQUEST_URI'];
$parsed_uri = parse_url($request_uri);
$req_path = $parsed_uri['path'];
$req_query = isset($parsed_uri['query']) ? $parsed_uri['query'] : null;

Router::route($req_path);