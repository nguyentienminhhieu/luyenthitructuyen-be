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
        if(isset($inputs['category_id']) && $inputs['category_id'] != null) {
            $query->where('category_id', $inputs['category_id']);
        }
        if(isset($inputs['grade_id']) && $inputs['grade_id'] != null) {
            $query->whereHas('Category', function ($q2) use ($inputs) {
                $q2->where('grade_id', $inputs['grade_id']);
            });
            $query->with('Category');
        }
        if(isset($inputs['subject_id']) && $inputs['subject_id'] != null) {
            $query->whereHas('Category', function ($q2) use ($inputs) {
                $q2->where('subject_id', $inputs['subject_id']);
            });
            $query->with('Category');
        }
        if(isset($inputs['title']) && $inputs['title'] != '') {
            $query->where('title','LIKE' , '%'.$inputs['title'].'%');
        }
        return $query->paginate($inputs['limit']??10);
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
    public function listExercisesByTeacher($teacher_id, $inputs)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->with(['Category' => function ($query2) {
            $query2->with('Grade');
            $query2->with('Subject');
        }])
        ->where('user_id', $teacher_id)
        ->when(isset($inputs['title']) && $inputs['title'] != '', function ($q) use ($inputs) {
            $q->where('title', 'LIKE', '%'.$inputs['title'].'%');
        })
        ->paginate($inputs['limit']??10);
    }
    
    public function listExercises($category_id = null, $inputs)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->with(['Category' => function ($query2) {
            $query2->with('Grade');
            $query2->with('Subject');
        }])
        ->when(isset($category_id), function ($q) use ($category_id) {
            $q->where('category_id', $category_id);
        })
        ->when(isset($inputs['title']) && $inputs['title'] != '', function ($q) use ($inputs) {
            $q->where('title','LIKE' , '%'.$inputs['title'].'%');
        })
        ->where('is_active', Exercise::ACTIVE)->paginate($inputs['limit']??10);
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

    public function getExerciseHome()
    {
        $query = $this->getQueryBuilder();
        return $query->where('is_active', Exercise::ACTIVE)
        ->limit(10)->orderBy('created_at', 'DESC')->get();   
    }
}