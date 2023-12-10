<?php

namespace App\AppMain\Reponsitory;
use App\Models\CommentExam;

class CommentExamReponsitory extends  BaseRepository  {
    
    public function getModel()
    {
        return CommentExam::class;
    }

    public function getQueryBuilder()
    {
        return CommentExam::query();
    }

}