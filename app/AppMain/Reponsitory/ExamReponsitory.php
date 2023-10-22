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

    public function getExam($id) 
    {
        $query = $this->getQueryBuilder();
        return $query->with(['questions'=> function ($query2) {
            $query2->with('answers');
            $query2->with('questionsExtends.answers');
            $query2->whereNull('parent_id');

        }])->with('questionIds')->where('id', $id)->first();
    }
}