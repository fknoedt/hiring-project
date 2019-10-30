<?php
namespace App\Services;

use App\Models\AssessmentModel;
use App\Models\UserAssessmentModel;

/**
 * Class AssessmentService
 * @package App\Services
 */
class AssessmentService extends MainService
{
    /**
     * Retrieve list of Assessment objects
     */
    public function getList()
    {
        return AssessmentModel::get();
    }

    /**
     * Retrieve list of Areas => Assessment objects
     */
    public function getGroupedList(): array
    {
        $list = [];
        foreach (AssessmentModel::with('area')->get() as $assessment) {
            $list[$assessment->area->name][] = $assessment;
        }
        return $list;
    }

    /**
     * Return UserAssessmentModel for the given $userId
     * @param $userId
     * @return UserAssessmentModel
     */
    public function getUserAssessment($userId)
    {
        return UserAssessmentModel::where('userId', $userId)->first();
    }


    /**
     * Create or update a record for the user / assessmentId
     * @param $userId
     * @param $assessmentId
     * @return UserAssessmentModel
     */
    public function saveUserAssessment($userId, $assessmentId): UserAssessmentModel
    {
        $match = [
            'userId' => $userId
        ];

        $update = [
            'assessmentId' => $assessmentId,
            'created_or_updated_at' => date('Y-m-d H:i:s')
        ];

        $userAssessment = UserAssessmentModel::updateOrCreate($match, $update);

        return $userAssessment;
    }
}