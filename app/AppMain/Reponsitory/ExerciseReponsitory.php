<?php

namespace App\AppMain\Reponsitory;
use App\Models\Exercise;
use App\Models\Question;

class ExerciseReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Exercise::class;
    }

    public function getQueryBuilder()
    {
        return Exercise::query();
    }

    public function getAll($inputs) 
    {
        $query = $this->getQueryBuilder();
        if(isset($inputs['type']) && $inputs['type'] == 'teacher') {
            $query->whereNotNull('user_id');
        } 
        if(isset($inputs['type']) && $inputs['type'] == 'admin') {
            $query->whereNull('user_id');
        }
        $query->with('user');
        return $query->get();
    }

    public function getExercise($id, $teacher_id = null) 
    {
        $query = $this->getQueryBuilder();
        return $query->with([
            'questions' => function ($query2) {
                $query2->with('answers');
                $query2->with('questionsExtends.answers');
                $query2->whereNull('parent_id');
                $query2->where('type', Question::EXERCISE);
            }
        ])
            ->with('questionIds')
            ->when(isset($teacher_id), function ($query2) use ($teacher_id) {
                $query2->where('user_id', $teacher_id);
        })
        ->where('id', $id)->first();
    }
    
    //web
    public function listExercisesByTeacher($teacher_id)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->with(['Category' => function ($query2) {
            $query2->with('Grade');
            $query2->with('Subject');
        }])
        ->where('user_id', $teacher_id)
        ->get();
    }
    
    public function listExercisesByCategory($category_id)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->with(['Category' => function ($query2) {
            $query2->with('Grade');
            $query2->with('Subject');
        }])
        ->where('category_id', $category_id)
        ->where('is_active', Exercise::ACTIVE)->get();
    }

    public function getExerciseBySlug($slug)
    {
        $query = $this->getQueryBuilder();
        return $query->with([
            'questions' => function ($query2) {
                $query2->with('answers');
                $query2->with('questionsExtends.answers');
                $query2->whereNull('parent_id');
                $query2->where('type', Question::EXERCISE);
            }
        ])->with('Category', function ($query2) {
            $query2->with(['Grade', 'Subject']);
        })
        ->where('slug', $slug)->first();
    }

    public function checkExerciseCreateByTeacher($exercise_id, $teacher_id)
    {
        $query = $this->getQueryBuilder();
        return $query->where('id', $exercise_id)
        ->where('user_id', $teacher_id)->first();   
    }
}