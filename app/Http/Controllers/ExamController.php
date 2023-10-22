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

    public function show($id)
    {
        try {
            $exam = $this->examService->show($id);

            return response()->json(['data'=> $exam,'status'=>1,'message'=>'get success'], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $inputs = $request->all();
            $exam = $this->examService->update($id, $inputs);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function delete($id)
    {
        try {
            $exam = $this->examService->delete($id);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
