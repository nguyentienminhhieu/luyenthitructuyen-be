<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRating extends Model
{
    use HasFactory;

    protected $table = "student_ratings";

    protected $fillable = [
        'user_id',
        'total_score',
        'total_exam'
    ];
}
