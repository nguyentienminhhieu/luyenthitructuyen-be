<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\AnswerReponsitory;
use Illuminate\Support\Facades\Auth;

class AnswerService {
    public $answerReponsitory;

    public function __construct(AnswerReponsitory $answerReponsitory) {
        $this->answerReponsitory = $answerReponsitory;
    }

    
}