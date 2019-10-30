<?php
use DI\Container;
use Slim\Views\Twig;

$container = new Container();

// $settings was defined on settings.php (called on index.php)
$container->set('settings', $settings);

$container->set('view', function() {
    return new Twig('../view', ['cache' => false]);
});

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->set('db', function() use ($capsule) {
    return $capsule;
});

// Service factory for the ORM

return $container;