<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Controllers\NavController;
use App\Controllers\AssessmentController;

$app->any('/login', function (Request $request, Response $response) use ($app) {
    $container = $app->getContainer();
    return $container->get('view')->render($response, 'login.html.twig');
});

$app->get('/', [NavController::class, 'listAssessments']);

$app->post('/assessment', [AssessmentController::class, 'saveUserAssessment']);

// process logout GET request
$app->get('/logout', function (Request $request, Response $response) use ($app) {
    // AuthMiddleware will redirect to /
    return $response;
});
