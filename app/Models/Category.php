<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "categories";

    protected $fillable = [
        "title",
        "slug",
        "grade_id",
        "subject_id",
        "is_active",
    ];

    public function Subject()
    {
        return $this->hasOne(Subject::class,'id' ,'subject_id');
    }

    public function Grade()
    {
        return $this->hasOne(Grade::class,'id' ,'grade_id');
    }
}
