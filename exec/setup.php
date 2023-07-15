<?php

function get_input($prompt, $default = null)
{
    $default_show = ($default) ? "[$default]" : '';
    $input = readline("$prompt $default_show: ");
    return (trim($input) == "") ? $default : $input;
}

function print_line($text, int $multiplier = 1)
{
    $output_text = '';
    for ($i = 0; $i < $multiplier; $i++) {
        $output_text = $output_text . $text;
    }
    echo $output_text . PHP_EOL;
}


$length = 60;

print_line('=', $length);
print_line(str_pad('| Welcome to PHP ROUTE TEMPLATE!', $length - 1) . '|');
print_line('=', $length);
print_line(str_pad('|-> Author:', 15) . str_pad('Hind Sagar Biswas ', $length - 16, ' ', STR_PAD_LEFT) . '|');
print_line(str_pad('|-> Github:', 15) . str_pad('https://github.com/hind-sagar-biswas/ ', $length - 16, ' ', STR_PAD_LEFT) . '|');
print_line('=', $length);
print_line('');

$consent = strtolower(get_input('Do you want to run setup? [Y/n] ', ''));
if ($consent == 'no' || $consent == 'n') {
    exit('Cancelling the setup!');
}
echo 'Running the setup...' . PHP_EOL . PHP_EOL;


// Take inputs
$vars = [
    'APP_NAME' => get_input('App Name', 'PHP Route Template'),
    'APP_ROOT' => get_input('App root', '/php_route_template'),
    'APP_URL' => get_input('App URL', 'localhost:8888'),
    'DATABASE_HOST' => get_input('Database host', 'localhost'),
    'DATABASE_DATABASE' => get_input('Database database', 'php_route_template'),
    'DATABASE_USERNAME' => get_input('Database username', 'root'),
    'DATABASE_PASSWORD' => get_input('Database password'),
];

// Get template from .env.example
$file = __DIR__ . "/../.env.example";
$fp = fopen($file, "r");
$template = fread($fp, filesize($file));
fclose($fp);

foreach ($vars as $key => $value) {
    $template = str_replace("{{{$key}}}", $value, $template);
}

// Set to .env
$file = __DIR__ . "/../.env";
$fp = fopen($file, "w");
fwrite($fp, $template);
fclose($fp);
// Set to .htaccess
$file = __DIR__ . "/../.htaccess";
$fp = fopen($file, "w");
fwrite($fp, "RewriteEngine On
RewriteBase " . $vars['APP_ROOT'] . "/

RewriteCond %{DOCUMENT_ROOT}/assets/$1 -f
RewriteRule ^(.*)$ assets/$1 [L]

RewriteCond %{DOCUMENT_ROOT}/node_modules/$1 -f
RewriteRule ^(.*)$ node_modules/$1 [L]

RewriteCond %{THE_REQUEST} \s/assets/ [NC,OR]
RewriteCond %{THE_REQUEST} \s/node_modules/ [NC,OR]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L,QSA]");
fclose($fp);

// Set variable values

// Install require packages
shell_exec('composer install && composer update && npm install');
