<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'take_exam',
        'total_score',
        'total_question_success',
        'duration'
    ];

    public function exam()
    {
        return $this->hasOne(Exam::class, 'id', 'exam_id');
    }
}
