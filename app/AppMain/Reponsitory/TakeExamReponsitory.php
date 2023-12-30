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

    public function listHistoryExamByUser($user_id, $inputs)
    {
        $query = $this->getQueryBuilder();
        $query->whereHas('exam', function ($q) use ($inputs) {
            $q->when(isset($inputs['category_id']) && $inputs['category_id'] != null, function ($q2) use ($inputs) {
                $q2->where('category_id', $inputs['category_id']);
            });
            $q->whereHas('Category', function ($q2) use ($inputs) {
                $q2->when(isset($inputs['subject_id']) && $inputs['subject_id'] != null, function ($q3) use ($inputs) {
                    $q3->where('subject_id', $inputs['subject_id']);
                });
            });
            $q->whereHas('Category', function ($q2) use ($inputs) {
                $q2->when(isset($inputs['grade_id']) && $inputs['grade_id'] != null, function ($q3) use ($inputs) {
                    $q3->where('grade_id', $inputs['grade_id']);
                });
            });
        });
        $query->with('exam', function ($q)
        {
            $q->with('Category');
        })
        ->when(isset($inputs['title']) && $inputs['title'] != '', function ($q) use ($inputs) {
            $q->whereHas('exam', function ($q2) use ($inputs) {
                $q2->where('title', 'LIKE', '%'.$inputs['title'].'%');
            });
        })
        ->where('user_id',$user_id)
        ->with('user')
        ->orderBy('created_at','DESC')
        ->select('user_id','exam_id','total_score','total_question_success', 'duration','id', 'total_question','times');

        return $query->paginate($inputs['limit']??10);
    }

    public function listExamsHasBeenDoneByUser($inputs) 
    {
        $query = $this->getQueryBuilder();
        return $query->with('exam')
        ->where('exam_id', $inputs['exam_id'])
        ->with('user')
        ->when(isset($inputs['username']) && $inputs['username'] != '' , function ($q) use ($inputs) {
            $q->whereHas('user', function ($q2) use ($inputs) {
                $q2->where('name', 'LIKE', '%'.$inputs['username'].'%');
            });
        })
        ->when(isset($inputs['username']) && $inputs['username'] != '' , function ($q) use ($inputs) {
            $q->whereHas('user', function ($q2) use ($inputs) {
                $q2->where('name', 'LIKE', '%'.$inputs['username'].'%');
            });
        })
        ->when(isset($inputs['score']) && $inputs['score'] != null , function ($q) use ($inputs) {
            $q->where('total_score', '>=', $inputs['score']);
        })
        ->select('user_id','exam_id','total_score','total_question_success', 'duration','id','total_question','times')
        ->orderBy('created_at','DESC')
        ->paginate($inputs['limit']??'');
    }
    
    public function reviewExam($take_exam_id) 
    {
        $query = $this->getQueryBuilder();
        return $query->with('comments', function ($q) {
            $q->with('teacher');
        })
        ->where('id', $take_exam_id)->first();
    }

    public function getLastTakeExam ($exam_id, $user_id) 
    {
        $query = $this->getQueryBuilder();
        return $query->where('exam_id', $exam_id)
        ->where('user_id', $user_id)->latest()
        ->select('user_id','exam_id','total_score','total_question_success', 'duration','id','total_question','times')
        ->first();
    }
}   