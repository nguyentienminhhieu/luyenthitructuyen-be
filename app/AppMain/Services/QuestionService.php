<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\QuestionReponsitory;
use Illuminate\Support\Facades\Auth;

class QuestionService {
    public $questionReponsitory;

    public function __construct(QuestionReponsitory $questionReponsitory) {
        $this->questionReponsitory = $questionReponsitory;
    }

    
}