<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    const EXAM = 0;
    const EXERCISE = 1;

    protected $table = "questions";

    protected $fillable = [
        "content",
        "slug",
        "description",
        "reference_id",
        "parent_id",
        "file",
        "type",
        "page",
        "explanation"
    ];

    public function questionsExtends()
    {
        return $this->hasMany(Question::class,'parent_id' ,'id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class,'question_id' ,'id');
    }
}
