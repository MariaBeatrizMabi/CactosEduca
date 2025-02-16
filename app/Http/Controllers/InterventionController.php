<?php

namespace App\Http\Controllers;

use App\Http\Requests\Intervention\StudentInterventionRequest;
use App\Services\Intervention\InterventionService;
use App\Services\Intervention\InterventionFetchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; 


class InterventionController extends Controller
{
    protected $interventionService;
    protected $interventionFetchService;

    public function __construct(InterventionService $interventionService, InterventionFetchService $interventionFetchService)
    {
        $this->interventionService = $interventionService;
        $this->interventionFetchService = $interventionFetchService;
    }

    public function saveStudentInterventions(StudentInterventionRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();    
            $savedData = $this->interventionService->saveInterventionsExam(
                $validated['exam_id'],
                $validated['selectedInterventions']
            );  
    
            return response()->json([
                'data' => $savedData,
            ]);
            } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }   

    public function getInterventions($writing, $studentId, $pollId): JsonResponse
    {
        try {
            $interventions = $this->interventionFetchService->getInterventionWritingTypeExam($writing, $studentId, $pollId);

            return response()->json(['interventions' => $interventions]);
    
        } catch (\Exception $e) {
           dd($e);
        }
    }

    public function getExamId($studentId, $pollId): JsonResponse
    {

        $examId = $this->interventionService->getExamIdStudent($studentId, $pollId);
        return response()->json(['exam_id' => $examId, 'poll_id' => $pollId]);
    }

}
