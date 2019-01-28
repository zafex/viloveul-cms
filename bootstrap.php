<?php

error_reporting(-1);

ini_set('display_errors', 'On');

defined('VILOVEUL_WORKDIR') or define('VILOVEUL_WORKDIR', __DIR__);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Request-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Request-Method: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(1);
}

// require composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// load dot env variable
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

// initialize application object
$app = new Viloveul\Kernel\Application(
    // initialize container with several components
    Viloveul\Container\ContainerFactory::instance([
        App\Component\SlugCreation::class => App\Component\SlugCreation::class,
        App\Component\Privilege::class => App\Component\Privilege::class,
        App\Component\Setting::class => App\Component\Setting::class,
    ]),
    // load file configuration
    Viloveul\Config\ConfigFactory::load(__DIR__ . '/config/main.php')
);

/**
 * Load all routes
 */
$app->uses(function (Viloveul\Router\Contracts\Collection $router) {
    foreach (glob(__DIR__ . '/routes/*.php') as $file) {
        require $file;
    }
});

/**
 * Load all middlewares
 */
$app->uses(function (Viloveul\Kernel\Contracts\Middleware $middleware) {
    foreach (glob(__DIR__ . '/middlewares/*.php') as $file) {
        require $file;
    }
});

return $app;
