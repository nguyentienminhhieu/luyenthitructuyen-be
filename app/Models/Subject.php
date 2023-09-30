<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug'
    ];

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'class_subjects', 'subject_id','grade_id');
    }
}
