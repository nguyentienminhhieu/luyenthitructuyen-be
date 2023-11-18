<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;
    const UN_ACTIVE = 0;

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
        'url_img',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class,'reference_id' ,'id');
    }

    public function questionIds()
    {
        return $this->hasMany(Question::class,'reference_id' ,'id');
    }

    public function Category()
    {
        return $this->hasOne(Category::class,'id' ,'category_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
