<?php

namespace App\AppMain\Reponsitory;
use App\Models\TakeExam;

class TakeExamReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return TakeExam::class;
    }

    public function getQueryBuilder()
    {
        return TakeExam::query();
    }

    public function listHistoryExamByUser($user_id)
    {
        $query = $this->getQueryBuilder();
        return $query->with('exam')->where('user_id',$user_id)->select('user_id','exam_id','total_score','total_question_success', 'duration','id')->get();
    }

    public function listExamsHasBeenDoneByUser($exam_id) 
    {
        $query = $this->getQueryBuilder();
        return $query->with('exam')->where('exam_id', $exam_id)->select('user_id','exam_id','total_score','total_question_success', 'duration','id')->get();
    }

    public function reviewExam($take_exam_id) 
    {
        $query = $this->getQueryBuilder();
        return $query->with('comments', function ($q) {
            $q->with('teacher');
        })
        ->where('id', $take_exam_id)->first();
    }
}   