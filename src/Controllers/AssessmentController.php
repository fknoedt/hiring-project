<?php

namespace App\Controllers;

use App\Exceptions\AuthException;
use App\Services\AssessmentService;
use App\Services\AuthService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AssessmentController extends MainController
{
    /**
     * Save an assessment related to the user
     * @endpoint POST /assessment
     * @param $request
     * @param Response $response
     * @return ResponseInterface
     * @throws AuthException
     */
    public function saveUserAssessment($request, Response $response): ResponseInterface
    {
        // var_dump(debug_backtrace());

        $input = $request->getParsedBody();

        $assessmentId = $input['assessmentId'] ?? null;

        // validate input
        if (! is_numeric($assessmentId)) {
            return $response->withStatus(422)->withJson(['message' => 'Choose an Assessment ' . $assessmentId]); // unprocessable entity
        }

        $userId = AuthService::getUserId();

        if (! $userId) {
            throw new AuthException(__METHOD__ . ": user not logged in");
        }

        $service = new AssessmentService($this->container);
        $service->saveUserAssessment($userId, $assessmentId);

        return $response->withJson(['message' => "Assessment choice saved"]);
    }

    /**
     * Save an assessment related to the user
     * @endpoint POST /assessment
     * @param Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function page($request, Response $response): ResponseInterface
    {
        return $response->withJson(['now save to the db' => true]);
    }
}