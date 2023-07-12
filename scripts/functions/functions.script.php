<?php

function dd($var, $die = true)
{
    echo '<pre style="background: #111111; border-radius: 3px; color: #efefef; padding: 10px;">';
    echo '$_DIE_DUMPING: ';
    var_dump($var);
    echo '</pre>';

    if ($die) exit();
}

function component($name, $params = []) {
    try {
        extract($params, EXTR_PREFIX_ALL, '_');
        require APP_COMPONENTS . $name . '.php';
    } catch (\Throwable $th) {
        throw $th;
    }
}

function findKeyByNeedle($arr, $needle)
{
    foreach ($arr as $key => $value) {
        if ($value[1] === $needle) {
            return $key;
        }
    }
    return '/404'; // Needle not found
}

function enroute($name) {
    $parts = explode(':', $name);
    $method = strtoupper($parts[0]);

    array_shift($parts);
    $needle = implode(':', $parts);
    $heystack = APP_ROUTES[$method];

    return $_ENV['APP_ROUTE_ROOT'] . findKeyByNeedle($heystack, $needle);
}

function asset($file) {
    return APP_STATIC . $file;
}
function image($file) {
    return APP_IMAGES . $file;
}