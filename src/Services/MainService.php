<?php
namespace App\Services;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class MainService
{
    /**
     * DI Container
     * @var
     */
    protected $container;

    /**
     * Make the container available for every (Sub) Controller instance
     * MainController constructor.
     * @param ContainerInterface $ci
     */
    public function __construct(ContainerInterface $ci)
    {
        $this->container = $ci;
    }
}