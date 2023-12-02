<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\ExerciseService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    protected $exerciseService;

    public function __construct(ExerciseService $exerciseService) {
        $this->exerciseService = $exerciseService;
    }

    public function listExercise(Request $request)
    {
        try {
            $inputs = $request->all();
            $exercise = $this->exerciseService->list($inputs);

            return response()->json(['data'=> $exercise], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createExercise(Request $request)
    {
        try {
            $user_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $inputs = $request->all();
            $exercise = $this->exerciseService->create($inputs, $user_id);

            return response()->json(['data'=> $exercise], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exercise = $this->exerciseService->show($id, $teacher_id);
            if($exercise) {
                return response()->json(['data'=> $exercise,'status'=>1,'message'=>'get success'], 200);
            } else {
                return response()->json(['error' => 'get Exercise failed']);
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
            $exercise = $this->exerciseService->update($id, $inputs, $teacher_id);

            if($exercise) {
                return response()->json(['data'=> $exercise,'status'=>1,'message'=>'update success'], 200);
            } else {
                return response()->json(['error' => 'update Exercise failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exercise = $this->exerciseService->delete($id, $teacher_id);

            if($exercise) {
                return response()->json(['data'=> $exercise,'status'=>1,'message'=>'delete success'], 200);
            } else {
                return response()->json(['error' => 'delete Exercise failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function activeExercise($id)
    {
        try {
            $teacher_id = Auth::user()->role == User::TEACHER ? Auth::user()->id : null;
            $exercise = $this->exerciseService->activeExercise($id, $teacher_id);

            if($exercise) {
                return response()->json(['data'=> $exercise,'status'=>1,'message'=>'active success'], 200);
            } else {
                return response()->json(['error' => 'active Exercise failed']);
            }
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //web
    public function listExercisesByCategory(Request $request)
    {
        try {
            $category_slug = $request['category_slug'];
            $exercise = $this->exerciseService->listExercisesByCategory($category_slug);

            return response()->json(['data'=> $exercise], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getExerciseBySlug(Request $request)
    {
        try {
            $slug = $request['slug'];
            $exercise = $this->exerciseService->getExerciseBySlug($slug);

            return response()->json(['data'=> $exercise], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function submitExercise(Request $request)
    {
        try {
            $slug = $request['slug'];
            $take_Exercise = $request['take_exercise'];
            $exercise = $this->exerciseService->submitExercise($slug, $take_Exercise);

            return response()->json(['data'=> $exercise], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function listExercisesByTeacher()
    {
        try {
            if(Auth::user()->role == User::TEACHER) {
                $exercises = $this->exerciseService->listExercisesByTeacher(Auth::user()->id);
                return response()->json(['data'=> $exercises], 200);
            } else {
                 return response()->json(['error' => 'you are not permission']);
            }

        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
