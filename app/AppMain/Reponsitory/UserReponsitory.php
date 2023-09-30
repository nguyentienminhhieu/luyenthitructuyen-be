<?php

namespace App\AppMain\Reponsitory;
use App\Models\User;

class UserReponsitory extends  BaseRepository{
    
    public function getModel()
    {
        return User::class;
    }

    public function getQueryBuilder()
    {
        return User::query();
    }

    public function create($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->create($input);
    }
}