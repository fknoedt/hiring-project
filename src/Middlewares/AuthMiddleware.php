<?php
namespace App\Middlewares;

use App\Controllers\MainController;
use App\Exceptions\AuthException;
use App\Services\AuthService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

/**
 * Every request will go through this middleware for authentication purposes
 */
class AuthMiddleware
{
    /**
     * login page and endpoint
     */
    const LOGIN_PATH = '/login';

    /**
     * GET logout requests
     */
    const LOGOUT_PATH = '/logout';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AuthService
     * @var
     */
    protected $service;

    /**
     * AuthMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->service = new AuthService($container);
    }

    /**
     * Login Middleware: redirect to/from /login, process login
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {

        // always try to return the user to the page he was before login
        $urlTo = $_GET['url'] ?? '/';

        // get Response object
        $response = $handler->handle($request);

        // user is logged in: update his time online and check if the request is to the /login page
        if (AuthService::isLoggedIn()) {

            // update the database
            $this->service->updateUserTime();

            // user shouldn't be in the /login page while logged in
            if ($request->getUri()->getPath() == self::LOGIN_PATH) {

                // logged in ajax request (shouldn't happen)
                if($this->service->isAjaxRequest()) {
                    $data = ['message' => 'Redirect', 'urlTo' => $urlTo];
                    $code = 302; // redirect
                    $this->respondJson($data, $code);
                } else {
                    $redirectTo = '/';
                    return $response->withRedirect($redirectTo,302); // redirect to /
                }

            }

            // logout request
            if ($request->getUri()->getPath() == self::LOGOUT_PATH) {
                $this->service->logout();
                $redirectTo = '/';
                return $response->withRedirect($redirectTo,302); // redirect to /
            }

        }
        // user not logged in: redirect / process login
        else {

            // /login page or POST XHR
            if ($request->getUri()->getPath() == self::LOGIN_PATH) {

                if ($request->getMethod() == 'POST') {

                    // get inputted credentials
                    $creds = $request->getParsedBody();

                    // if login fails, an exception will be thrown
                    try {
                        $this->service->login($creds);
                        $data = ['message' => 'Login Successful', 'urlTo' => $urlTo];
                        $code = 200;
                    }
                    catch(AuthException $e) {
                        $data = ['message' => 'Invalid Login'];
                        $code = $e->getCode();
                    }
                    catch(\Exception $e) {
                        $data = ['message' => 'Internal Error', 'error' => $e->getMessage(), 'file:line' => $e->getFile() . ':' . $e->getLine()];
                        $code = 500;
                    }

                    $this->respondJson($data, $code);

                }

            } else {

                // if not in the login page (the POST request is also to /login): redirect to /login
                if ($request->getUri()->getPath() != self::LOGIN_PATH) {

                    // Ajax requests should always be logged in or to the /login endpoint, so let's block it
                    if($this->service->isAjaxRequest()) {
                        // throw new AuthException("Not implemented", 501);
                        $data = ['message' => 'Redirect', 'urlTo' => self::LOGIN_PATH];
                        $code = 403; // unautorized
                        $this->respondJson($data, $code);
                    } else {
                        $redirectTo = self::LOGIN_PATH . '?urlTo=' . urlencode($request->getUri()->getPath());
                        return $response->withRedirect($redirectTo,302); // redirect to /login?urlTo=/attemptedUrl?query=string
                    }

                }

            }

        }

        return $response;
    }

    /**
     * Dump JSON and halt
     * @param $data
     * @param $code
     */
    protected function respondJson(array $data, int $code): void
    {
        // will send raw response (Response object should be returned but time was short to troubleshoot)
        $httpStatusCodes = MainController::HTTP_STATUS;
        // default code is 500
        if (! isset($httpStatusCodes[$code])) {
            $code = 500;
        }

        // get the according phrase
        $reasonPhrase = $httpStatusCodes[$code];

        header("Content-Type: application/json");
        header("HTTP/1.1 {$code} {$reasonPhrase}");
        echo json_encode($data);
        exit;
    }
}