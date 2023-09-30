<?php

namespace App\AppMain\Reponsitory;
use App\Models\Grade;

class GradeReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Grade::class;
    }


    public function getQueryBuilder()
    {
        return Grade::query();
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

    public function getList()
    {
        $query = $this->getQueryBuilder();
        return $query->with('subjects')->get();
    }
}