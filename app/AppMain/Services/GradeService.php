<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\GradeReponsitory;

class GradeService {
    public $gradeReponsitory;

    public function __construct(GradeReponsitory $gradeReponsitory) {
        $this->gradeReponsitory = $gradeReponsitory;
    }

    public function all()
    {
        return  $this->gradeReponsitory->getList();
    }
    public function show($id)
    {
       $grade = $this->gradeReponsitory->getDetail($id);
       $grade->subject_ids =  $grade->gradeSubjectIds->map(function($item){
        return $item->subject_id;
       });
       unset($grade->gradeSubjectIds);
       return $grade;
    }
    public function store($input)
    {
        $check_slug = $this->gradeReponsitory->checkSlug(['slug' => $input['slug']]);
        if($check_slug >0 ) {
            return response()->json(['error' => 'Slug của bạn không được trùng lặp!']);
        }
        $grade = $this->gradeReponsitory->create($input);
        $grade->subjects()->attach($input['subjectIds']);
        return $grade;
    }
    public function update($input, $id)
    {
        $this->gradeReponsitory->update('id',$id,['name'=>$input['name'],'slug'=>isset($input['slug'])?$input['slug']:'']);
        $grade = $this->gradeReponsitory->find($id);
        $grade->subjects()->sync($input['subjectIds']);
        return  $grade;
    }
    public function destroy($id)
    {
        return  $this->gradeReponsitory->delete($id);
    }
}