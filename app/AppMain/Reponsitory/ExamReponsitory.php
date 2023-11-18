<?php

namespace App\AppMain\Reponsitory;
use App\Models\Exam;

class ExamReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Exam::class;
    }

    public function getQueryBuilder()
    {
        return Exam::query();
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

    public function getExam($id, $teacher_id = null) 
    {
        $query = $this->getQueryBuilder();
        return $query->with([
            'questions' => function ($query2) {
                $query2->with('answers');
                $query2->with('questionsExtends.answers');
                $query2->whereNull('parent_id');

            }
        ])
            ->with('questionIds')
            ->when(isset($teacher_id), function ($query2) use ($teacher_id) {
                $query2->where('user_id', $teacher_id);
        })
        ->where('id', $id)->first();
    }
    
    //web
    public function listExamsByTeacher($teacher_id)
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
    
    public function listExamsByCategory($category_id)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->with(['Category' => function ($query2) {
            $query2->with('Grade');
            $query2->with('Subject');
        }])
        ->where('category_id', $category_id)
        ->where('is_active', Exam::ACTIVE)->get();
    }

    public function getExamBySlug($slug)
    {
        $query = $this->getQueryBuilder();
        return $query->with([
            'questions' => function ($query2) {
                $query2->with('answers');
                $query2->with('questionsExtends.answers');
                $query2->whereNull('parent_id');
            }
        ])->with('Category', function ($query2) {
            $query2->with(['Grade', 'Subject']);
        })
        ->where('slug', $slug)->first();
    }

    public function checkExamCreateByTeacher($exam_id, $teacher_id)
    {
        $query = $this->getQueryBuilder();
        return $query->where('id', $exam_id)
        ->where('user_id', $teacher_id)->first();   
    }
}