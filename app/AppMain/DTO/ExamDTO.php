<?php

namespace App\AppMain\DTO;

class ExamDTO
{
    public array $exam;

    public function __construct(array $exam = [])
    {
        $this->exam = $exam;
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
}