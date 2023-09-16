<?php

namespace App\AppMain\Reponsitory;
use App\Models\User;

class UserReponsitory {
    
    // public function __construct()
    // {
    //     $this->getQueryBuilder();
    // }

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