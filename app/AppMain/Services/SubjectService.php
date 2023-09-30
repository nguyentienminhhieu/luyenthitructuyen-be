<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\SubjectReponsitory;

class SubjectService {
    public $subjectReponsitory;

    public function __construct(SubjectReponsitory $subjectReponsitory) {
        $this->subjectReponsitory = $subjectReponsitory;
    }

    public function all()
    {
        return  $this->subjectReponsitory->all();
    }
    public function store($input)
    {
        $check_slug = $this->subjectReponsitory->checkSlug(['slug' => $input['slug']]);
        if($check_slug >0 ) {
            return response()->json(['error' => 'Slug của bạn không được trùng lặp!']);
        }
        return  $this->subjectReponsitory->create($input);
    }
    public function update($input, $id)
    {
        return  $this->subjectReponsitory->update('id',$id,$input);
    }
    public function destroy($id)
    {
        return  $this->subjectReponsitory->delete($id);
    }
}