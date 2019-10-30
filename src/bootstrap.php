<?php

// load and register environment variables from .env file
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

// DD helper
require '../vendor/larapack/dd/src/helper.php';

// Eloquent / DB helpers

/**
 * start query logging (see getQueryLog)
 */
function enableQueryLog(): void
{
    global $container;
    $container->get('db')->getConnection()->enableQueryLog();
}

/**
 * get logged queries
 * @return mixed
 */
function getQueryLog(): array
{
    global $container;
    return $container->get('db')->getConnection()->getQueryLog();
}