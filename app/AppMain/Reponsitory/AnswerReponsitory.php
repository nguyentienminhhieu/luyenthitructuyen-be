<?php

namespace App\AppMain\Reponsitory;
use App\Models\Answer;

class AnswerReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Answer::class;
    }

    public function getQueryBuilder()
    {
        return Answer::query();
    }

    // public function create($input) 
    // {
    //     $query = $this->getQueryBuilder();
    //     return $query->create($input);
    // }
}