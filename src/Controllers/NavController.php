<?php
namespace App\Controllers;

use App\Models\AreaModel;
use App\Models\UserModel;
use App\Services\AssessmentService;
use App\Services\AuthService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class NavController extends MainController
{
    /**
     * Display user's assessment <html> template
     * @endpoint GET /
     * @param Response $response
     * @return ResponseInterface
     */
    public function listAssessments($request, Response $response): ResponseInterface
    {
        $service = new AssessmentService($this->container);

        // assessments, grouped by area, to feed the main select
        $assessments = $service->getGroupedList();

        $userId = AuthService::getUserId();
        $lastAssessment = $service->getUserAssessment($userId);

        $data = [
            'assessmentList' => $assessments,
            'savedAssessment' => '?',
            'lastAssessment' => $lastAssessment
        ];

        return $this->container->get('view')->render($response, 'assessment.html.twig', $this->tplData($data));
    }
}