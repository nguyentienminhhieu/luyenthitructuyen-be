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
        return $query->get();
    }

    public function getExam($id) 
    {
        $query = $this->getQueryBuilder();
        return $query->with(['questions'=> function ($query2) {
            $query2->with('answers');
            $query2->with('questionsExtends.answers');
            $query2->whereNull('parent_id');

        }])->with('questionIds')->where('id', $id)->first();
    }
    
    //web
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
        return $query->with(['questions'=> function ($query2) {
            $query2->with('answers');
            $query2->with('questionsExtends.answers');
            $query2->whereNull('parent_id');
        }])->where('slug', $slug)->first();
    }
}