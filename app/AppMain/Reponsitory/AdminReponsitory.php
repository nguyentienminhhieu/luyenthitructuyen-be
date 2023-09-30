<?php

namespace App\AppMain\Reponsitory;
use App\Models\Admin;

class AdminReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Admin::class;
    }

    public function getQueryBuilder()
    {
        return Admin::query();
    }

    public function create($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->create($input);
    }
}