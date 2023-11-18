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

    public function getAll() {
        $query = $this->getQueryBuilder();
        return $query->with(['Subject', 'Grade'])->get();
    }

    public function checkSlug($input) 
    {
        $query = $this->getQueryBuilder();
        return $query->where('slug', $input)->count();
    }

    //web
    public function listCategory($grade_id, $subject_id)
    {
        $query = $this->getQueryBuilder();
        return $query
        ->when(isset($grade_id), function ($query2) use ($grade_id){
            $query2->where('grade_id', $grade_id);
        })
        ->when(isset($subject_id), function ($query2) use ($subject_id){
            $query2->where('subject_id', $subject_id);
        })
        ->get();
    }
}