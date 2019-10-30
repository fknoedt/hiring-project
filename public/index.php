<?php
use App\Middlewares\AuthMiddleware;
use \DI\Bridge\Slim\Bridge;

// let's be strict
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

// session has to be started after the classes definition so it can hold their instances
session_start();

// initialize components
require '../src/bootstrap.php';

$settings = require '../src/settings.php';

// Set DI Container
$container = require '../src/container.php';

// set PHP-DI container through slim-bridge (instead of AppFactory::create())
$app = Bridge::create($container);

// Bind Routing Middleware
$app->addRoutingMiddleware();

// Bind Error Middleware
$app->addErrorMiddleware(true, true, true);
error_reporting(E_ALL);

// Bind Authentication Middleware
$app->add(AuthMiddleware::class);

// routes definition
require '../src/routes.php';

$app->run();