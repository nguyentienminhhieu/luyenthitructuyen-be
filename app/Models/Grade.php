<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'grade_id','subject_id');
    }
    public function gradeSubjectIds()
    {
        return $this->hasMany(GradeSubject::class, 'grade_id','id');
    }
}
