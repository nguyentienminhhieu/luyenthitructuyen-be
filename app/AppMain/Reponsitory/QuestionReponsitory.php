<?php

namespace App\AppMain\Reponsitory;
use App\Models\Question;

class QuestionReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Question::class;
    }

    public function getQueryBuilder()
    {
        return Question::query();
    }

    // public function create($input) 
    // {
    //     $query = $this->getQueryBuilder();
    //     return $query->create($input);
    // }
}