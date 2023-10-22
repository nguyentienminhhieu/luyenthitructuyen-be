<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "exams";

    protected $fillable = [
        "title",
        "slug",
        "description",
        "user_id",
        "max_score",
        "duration",
        "is_active",
        "category_id",
    ];

    public function questions()
    {
        return $this->hasMany(Question::class,'reference_id' ,'id');
    }

    public function questionIds()
    {
        return $this->hasMany(Question::class,'reference_id' ,'id');
    }
}
