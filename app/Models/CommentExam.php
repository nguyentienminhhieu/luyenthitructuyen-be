<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentExam extends Model
{
    use HasFactory;

    protected $table = "comment_exams";

    protected $fillable = [
        'exam_id',
        'teacher_id',
        'comment'
    ];

    public function teacher()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }
}
