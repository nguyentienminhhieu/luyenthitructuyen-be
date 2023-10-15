<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\ExamService;
use Exception;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService) {
        $this->examService = $examService;
    }

    public function createExam(Request $request)
    {
        try {
            $inputs = $request->all();
            $exam = $this->examService->create($inputs);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
