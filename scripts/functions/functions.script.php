<?php

/**
 * Functions.script contains all required functions for the project
 * 
 */

/**
 * Dump and die function for debugging purposes.
 *
 * @param mixed $var The variable to dump.
 * @param bool $die Flag to indicate whether to exit after dumping.
 * @return void
 */
function dd($var, $die = true)
{
    echo '<pre style="background: #111111; border-radius: 3px; color: #efefef; padding: 10px;">';
    echo '$_DIE_DUMPING: ';
    var_dump($var);
    echo '</pre>';

    if ($die) exit();
}

/**
 * Renders a component view file with the given parameters.
 *
 * @param string $name The name of the component view file.
 * @param array $params An associative array of parameters to extract.
 * @throws Throwable If an error occurs while rendering the component.
 * @return void
 */
function component($name, $params = [])
{
    try {
        extract($params, EXTR_PREFIX_ALL, '_');
        require APP_COMPONENTS . $name . '.php';
    } catch (\Throwable $th) {
        throw $th;
    }
}

/**
 * Finds the key of the associative array whose second element matches the needle.
 *
 * @param array $arr The associative array to search in.
 * @param mixed $needle The value to search for.
 * @return mixed The key of the array element if found, otherwise '/404'.
 */
function findKeyByNeedle($arr, $needle)
{
    foreach ($arr as $key => $value) {
        if ($value[1] === $needle) {
            return $key;
        }
    }
    return '/404'; // Needle not found
}

/**
 * Flattens an associative array into a string of key-value pairs.
 *
 * @param array $array The associative array to flatten.
 * @param bool $sql for SQL query, else for url query (Default: true).
 * @return string The flattened string of key-value pairs.
 */
function flatenAssocArray($array, $sql = true)
{
    $pairs = ($sql) 
                ? array_map(function ($key, $value) {
        return "{$key} = '{$value}'";
    }, array_keys($array), $array) 
                : array_map(function ($key, $value) {
        return "{$key}={$value}";
    }, array_keys($array), $array);

    return ($sql) ? implode(', ', $pairs) : implode('&', $pairs);
}

/**
 * Sets cookie to the root.
 * 
 * @param string $name The name of the cookie
 * @param string $value The value of the cookie
 * @param int $expiry days for expiry (default: 30 days)
 * @return void
 */
function set_cookie(string $name, $value, int $expiry = 30): void
{
    setcookie($name, $value, $expiry, APP_ROOT . '/');
}

/**
 * Constructs the URL route based on the given name.
 *
 * @param string $name The name of the route.
 * @return string The constructed URL route.
 */
function enroute($name)
{
    $parts = explode(':', $name);
    $method = $parts[0];

    array_shift($parts);
    $needle = implode(':', $parts);
    $heystack = APP_ROUTES[$method];

    return APP_ROOT . findKeyByNeedle($heystack, $needle);
}

/**
 * Generates the URL for an asset file.
 *
 * @param string $file The file path of the asset.
 * @return string The generated asset URL.
 */
function asset($file)
{
    return APP_STATIC . $file;
}

/**
 * Generates the URL for an image file.
 *
 * @param string $file The file path of the image.
 * @return string The generated image URL.
 */
function image($file)
{
    return APP_IMAGES . $file;
}


function redirect($name, array $vars = [], $message = null)
{
    $target = enroute('get:' . $name);
    $query = (!empty($vars)) ? '?' . flatenAssocArray($vars, false) : '';

    $route = $target . $query;

    if ($message) $_SESSION['flash_msg'] = $message;
    header('Location: ' . $route);
}