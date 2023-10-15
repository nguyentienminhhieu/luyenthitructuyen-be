<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryService {
    public $categoryReponsitory;

    public function __construct(CategoryReponsitory $categoryReponsitory) {
        $this->categoryReponsitory = $categoryReponsitory;
    }

    public function all()
    {
        return $this->categoryReponsitory->all();
    }
    
    public function show($id)
    {
        return $this->categoryReponsitory->find($id);
    }

    public function create($inputs)
    {
        if($inputs['slug'] == ""){
            $inputs['slug'] = Str::slug($inputs['title']);
            if($this->checkSlug($inputs['slug']) > 0) {
                $inputs['slug'] = $inputs['slug'].'-'.$this->checkSlug($inputs['slug'])+1;
            }
        }
        return $this->categoryReponsitory->create($inputs);
    }

    public function update($id, $inputs)
    {
        if($inputs['slug'] == ""){
            $inputs['slug'] = Str::slug($inputs['title']);
            if($this->checkSlug($inputs['slug']) > 0) {
                $inputs['slug'] = $inputs['slug'].'-'.$this->checkSlug($inputs['slug'])+1;
            }
        }
        return $this->categoryReponsitory->update('id', $id, $inputs);
    }

    public function delete($id)
    {
        return $this->categoryReponsitory->delete($id);
    }

    public function checkSlug(&$slug)
    { 
        return $this->categoryReponsitory->checkSlug($slug);
    }
}