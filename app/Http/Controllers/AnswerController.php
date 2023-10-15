<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\AnswerService;

class AnswerController extends Controller
{
    protected $answerService;

    public function __construct(AnswerService $answerService) {
        $this->answerService = $answerService;
    }
}
