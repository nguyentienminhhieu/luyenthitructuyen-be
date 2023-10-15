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

    // public function create($input) 
    // {
    //     $query = $this->getQueryBuilder();
    //     return $query->create($input);
    // }
}