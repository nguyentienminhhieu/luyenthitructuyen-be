<?php

namespace App\AppMain\Reponsitory;
use App\Models\Admin;

class AdminReponsitory {
    
    // public function __construct()
    // {
    //     $this->getQueryBuilder();
    // }

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