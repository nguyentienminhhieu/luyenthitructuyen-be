<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subjects';
    protected $fillable = [
        'grade_id',
        'subject_id'
    ];

}
