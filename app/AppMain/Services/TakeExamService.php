<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\ExamReponsitory;
use App\AppMain\Reponsitory\QuestionReponsitory;
use App\AppMain\Reponsitory\AnswerReponsitory;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\AppMain\Reponsitory\TakeExamReponsitory;
use App\Models\Exam;
use App\AppMain\DTO\ExamDTO;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;


class TakeExamService {
    public $examReponsitory;
    public $questionReponsitory;
    public $answerReponsitory;
    public $categoryReponsitory;
    public $takeExamReponsitory;

    public function __construct(ExamReponsitory $examReponsitory,
    QuestionReponsitory $questionReponsitory,
    AnswerReponsitory $answerReponsitory,
    CategoryReponsitory $categoryReponsitory,
    TakeExamReponsitory $takeExamReponsitory
    ) {
        $this->examReponsitory = $examReponsitory;
        $this->questionReponsitory = $questionReponsitory;
        $this->answerReponsitory = $answerReponsitory;
        $this->categoryReponsitory = $categoryReponsitory;
        $this->takeExamReponsitory = $takeExamReponsitory;
    }

    public function listHistoryExamByUser()
    {
        $user_id = Auth::id();
        return $this->takeExamReponsitory->listHistoryExamByUser($user_id);
    }
    public function reviewExamById($id)
    {
        $exam = $this->takeExamReponsitory->find($id)->toArray();
        $exam['take_exam'] = json_decode($exam['take_exam']);
        return $exam;
    }

    public function listExamsHasBeenDoneByUser($exam_id) 
    {
        return $this->takeExamReponsitory->listExamsHasBeenDoneByUser($exam_id);
    }
}