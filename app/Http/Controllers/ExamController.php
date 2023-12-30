<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\ExamService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService) {
        $this->examService = $examService;
    }

    public function listExam(Request $request)
    {
        try {
            $inputs = $request->all();
            $exam = $this->examService->list($inputs);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createExam(Request $request)
    {
        try {
            $user_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $inputs = $request->all();
            $exam = $this->examService->create($inputs, $user_id);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exam = $this->examService->show($id, $teacher_id);
            if($exam) {
                return response()->json(['data'=> $exam,'status'=>1,'message'=>'get success'], 200);
            } else {
                return response()->json(['error' => 'get exam failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $inputs = $request->all();
            $exam = $this->examService->update($id, $inputs, $teacher_id);

            if($exam) {
                return response()->json(['data'=> $exam,'status'=>1,'message'=>'update success'], 200);
            } else {
                return response()->json(['error' => 'update exam failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exam = $this->examService->delete($id, $teacher_id);

            if($exam) {
                return response()->json(['data'=> $exam,'status'=>1,'message'=>'delete success'], 200);
            } else {
                return response()->json(['error' => 'delete exam failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function activeExam($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exam = $this->examService->activeExam($id, $teacher_id);

            if($exam) {
                return response()->json(['data'=> $exam,'status'=>1,'message'=>'active success'], 200);
            } else {
                return response()->json(['error' => 'active exam failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //web
    public function listExams(Request $request)
    {
        try {
            $inputs = $request->all();
            $exam = $this->examService->listExams($inputs);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function listExamsHasUser(Request $request)
    {
        try {
            $inputs = $request->all();
            $exam = $this->examService->listExamsHasUser($inputs);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getExamBySlug(Request $request)
    {
        try {
            $slug = $request['slug'];
            $exam = $this->examService->getExamBySlug($slug);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function submitExam(Request $request)
    {
        try {
            $slug = $request['slug'];
            $take_exam = $request['take_exam'];
            $exam = $this->examService->submitExam($slug, $take_exam);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function listExamsByTeacher(Request $request)
    {
        try {
            $inputs = $request->all();
            if(Auth::user()->role == User::TEACHER) {
                $exams = $this->examService->listExamsByTeacher(Auth::user()->id, $inputs);
                return response()->json(['data'=> $exams], 200);
            } else {
                 return response()->json(['error' => 'you are not permission']);
            }

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
