<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\ExamReponsitory;
use App\AppMain\Reponsitory\QuestionReponsitory;
use App\AppMain\Reponsitory\AnswerReponsitory;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\AppMain\Reponsitory\TakeExamReponsitory;
use App\AppMain\Reponsitory\CommentExamReponsitory;
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
    public $commentExamReponsitory;

    public function __construct(ExamReponsitory $examReponsitory,
    QuestionReponsitory $questionReponsitory,
    AnswerReponsitory $answerReponsitory,
    CategoryReponsitory $categoryReponsitory,
    TakeExamReponsitory $takeExamReponsitory,
    CommentExamReponsitory $commentExamReponsitory
    ) {
        $this->examReponsitory = $examReponsitory;
        $this->questionReponsitory = $questionReponsitory;
        $this->answerReponsitory = $answerReponsitory;
        $this->categoryReponsitory = $categoryReponsitory;
        $this->takeExamReponsitory = $takeExamReponsitory;
        $this->commentExamReponsitory = $commentExamReponsitory;
    }

    public function listHistoryExamByUser($inputs)
    {
        $user_id = Auth::id();
        return $this->takeExamReponsitory->listHistoryExamByUser($user_id, $inputs);
    }
    public function reviewExamById($id)
    {
        $exam = $this->takeExamReponsitory->reviewExam($id)->toArray();
        $exam['take_exam'] = json_decode($exam['take_exam']);
        return $exam;
    }

    public function listExamsHasBeenDoneByUser($inputs) 
    {
        return $this->takeExamReponsitory->listExamsHasBeenDoneByUser($inputs);
    }

    public function commentExam($teacher_id, $input) 
    {
        $data = [
            'teacher_id' => $teacher_id,
            'exam_id' => $input['take_exam_id'],
            'comment' => $input['comment']
        ];
        return $this->commentExamReponsitory->create($data);
    }
    
    public function updateCommentExam($teacher_id, $id, $input) 
    {
        $data = [
            'teacher_id' => $teacher_id,
            'exam_id' => $input['take_exam_id'],
            'comment' => $input['comment']
        ];
        return $this->commentExamReponsitory->update('id', $id, $data);
    }

    public function deleteCommentExam($id) 
    {
        return $this->commentExamReponsitory->delete($id);
    }
}