<?php

namespace App\AppMain\DTO;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamDTO
{
    public array $exam;
    public array $takeExam;

    public function __construct(array $exam = [], array $takeExam = [])
    {
        $this->exam = $exam;
        $this->takeExam = $takeExam;
    }

    public function formatData()
    {
        if(isset($this->exam['questions']) && count($this->exam['questions'])>0) {
            $index = 0;
            $this->setQuestions($this->exam['questions'],  $index);
        }

        return $this->exam;
    }

    public function setQuestions(&$questions = [],  &$index) 
    {
        foreach($questions as &$item) {
            if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                $this->setQuestions($item['questions_extends'], $index);
            } else {
                $index++;
                $item['index'] = $index;
                if(count($item['answers'])>0){
                    foreach($item['answers'] as &$answer){
                        unset($answer['is_correct']);
                        unset($answer['explanation']);
                    }
                }
            }
        }
    }

    public function formatTakeExam()
    {
        $data = [];
        $total_score = 0;
        $total_question_success = 0;
        if(isset($this->exam['questions']) && count($this->exam['questions'])>0) {
            $index = 0;
            $this->setQuestionsTakeExam($this->exam['questions'], $this->takeExam['questions'],  $index, $total_question_success);
        }

        $total_score = ($total_question_success/$index)*$this->exam['max_score'];
        $data = [
            'exam_id' => $this->exam['id'],
            'user_id' => Auth::id(),
            'take_exam' => json_encode($this->exam),
            'total_score' => (int)$total_score,
            'total_question_success' => $total_question_success,
            'duration' => $this->exam['duration'],
            'total_question' => $index
        ];
        return $data;
    }

    public function setQuestionsTakeExam(&$questions = [], &$questionsTakeExam = [],  &$index, &$total_question_success) 
    {
        foreach($questions as $key => &$item) {
                if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                    $this->setQuestionsTakeExam($item['questions_extends'],$questionsTakeExam[$key]['questions_extends'], $index, $total_question_success);
                } else {
                    $index++;
                    $item['index'] = $index;
                    if(count($item['answers'])>0){
                        foreach($item['answers'] as $keyAns => &$answer){
                            $answer['checked'] = isset($questionsTakeExam[$key]['answers'][$keyAns]['checked'])?$questionsTakeExam[$key]['answers'][$keyAns]['checked']:false;
                            if($answer['is_correct'] == 1 && $questionsTakeExam[$key]['answers'][$keyAns]['checked'] == true) {
                                $total_question_success++;
                                $item['is_success'] = true;
                            }
                        }
                    }
                }
        }
    }
}