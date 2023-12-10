<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\TakeExamService;
use Exception;
use Illuminate\Support\Facades\Auth;

class TakeExamController extends Controller
{
    protected $takeExamService;

    public function __construct(TakeExamService $takeExamService) {
        $this->takeExamService = $takeExamService;
    }

    public function listHistoryExamByUser()
    {
        try {
            $exam = $this->takeExamService->listHistoryExamByUser();

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function reviewExamById($id)
    {
        try {
            $exam = $this->takeExamService->reviewExamById($id);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //teacher
    public function listExamsHasBeenDoneByUser(Request $request)
    {
        try {
            $exam_id = $request['exam_id'];
            $exam = $this->takeExamService->listExamsHasBeenDoneByUser($exam_id);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function commentExam(Request $request) 
    {
        try {
            $input = $request->only(['take_exam_id','comment']);
            $teacher_id = Auth::id();
            $exams = $this->takeExamService->commentExam($teacher_id, $input);
            return response()->json(['data'=> $exams], 200); 

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateCommentExam($id, Request $request) 
    {
        try {
            $input = $request->only(['take_exam_id','comment']);
            $teacher_id = Auth::id();
            $exams = $this->takeExamService->updateCommentExam($teacher_id, $id, $input);
            return response()->json(['data'=> $exams], 200); 

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function deleteCommentExam($id) 
    {
        try {
            $exams = $this->takeExamService->deleteCommentExam($id);
            return response()->json(['data'=> $exams], 200); 

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
