<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\AppMain\Reponsitory\GradeReponsitory;
use App\AppMain\Reponsitory\SubjectReponsitory;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryService {
    public $categoryReponsitory;
    public $gradeReponsitory;
    public $subjectReponsitory;

    public function __construct(
        CategoryReponsitory $categoryReponsitory,
        GradeReponsitory $gradeReponsitory,
        SubjectReponsitory $subjectReponsitory,
    ) {
        $this->categoryReponsitory = $categoryReponsitory;
        $this->gradeReponsitory = $gradeReponsitory;
        $this->subjectReponsitory = $subjectReponsitory;
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

    //web
    public function listCategory($inputs)
    {
        $grade_id = null;
        $subject_id = null;
        if(isset($inputs['grade_slug'])){
            $grade_id = $this->gradeReponsitory->findOne('slug', $inputs['grade_slug'])->id;
        }
        if(isset($inputs['subject_slug'])){
            $subject_id = $this->subjectReponsitory->findOne('slug', $inputs['subject_slug'])->id;
        }   
        return $this->categoryReponsitory->listCategory($grade_id, $subject_id);
    }
}