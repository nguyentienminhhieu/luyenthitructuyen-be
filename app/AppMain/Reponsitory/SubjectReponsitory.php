<?php

namespace App\AppMain\Reponsitory;
use App\Models\Subject;

class SubjectReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Subject::class;
    }


    public function getQueryBuilder()
    {
        return Subject::query();
    }

    public function create($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->create($input);
    }

    public function checkSlug($condition = []) 
    {
        $query = $this->getQueryBuilder();
        return $query->where($condition)->whereNull('deleted_at')->count();
    }

}