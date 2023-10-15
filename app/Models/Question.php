<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "questions";

    protected $fillable = [
        "content",
        "slug",
        "description",
        "reference_id",
        "parent_id",
        "file",
        "type",
    ];
}
