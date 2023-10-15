<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\QuestionService;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService) {
        $this->questionService = $questionService;
    }
}
