<?php

namespace App\AppMain\Reponsitory;
use App\Models\Category;

class CategoryReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return Category::class;
    }

    public function getQueryBuilder()
    {
        return Category::query();
    }

    public function checkSlug($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->where('slug', $input)->count();
    }
}