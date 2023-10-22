<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\ExamReponsitory;
use App\AppMain\Reponsitory\QuestionReponsitory;
use App\AppMain\Reponsitory\AnswerReponsitory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;


class ExamService {
    public $examReponsitory;
    public $questionReponsitory;
    public $answerReponsitory;

    public function __construct(ExamReponsitory $examReponsitory,
    QuestionReponsitory $questionReponsitory,
    AnswerReponsitory $answerReponsitory
    ) {
        $this->examReponsitory = $examReponsitory;
        $this->questionReponsitory = $questionReponsitory;
        $this->answerReponsitory = $answerReponsitory;
    }

    public function create($inputs)
    {
        try {
            $data = [
                'title' => $inputs['title'],
                'slug' => isset($inputs['slug'])?$inputs['slug']:Str::slug($inputs['title']),
                'description' => $inputs['description'],
                'max_score' => $inputs['max_score'],
                'duration' => $inputs['duration'],
                'category_id' => $inputs['category_id'],
            ];
    
            $exam = $this->examReponsitory->create($data);
            
            if(isset($inputs['questions']))
            {
                $this->createQuestions($inputs['questions'], $exam->id);
            }
            return response()->json(['success' => $exam]);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createQuestions($questions = [], $exam_id, $parent_id = null)
    {
        try {
            foreach($questions as $item) {
                $data = [
                    'content' => $item['content'],
                    'slug' => isset($item['slug'])?$item['slug']:Str::slug($item['content']),
                    'description' => $item['description'],
                    'reference_id' => $exam_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'type' => 0, //type = 0 => EXAM, type = 1 => EXERCISE
                ];
                $question = $this->questionReponsitory->create($data);
                if(isset($item['answers']) && count($item['answers']) > 0)
                {
                    $this->createAnswers($item['answers'], $question->id);
                }
                if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                    $this->createQuestions($item['questions_extends'], $exam_id, $question->id);
                }
            }
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createAnswers($answers = [], $question_id)
    {
        try {
            foreach($answers as $item) {
                $data = [
                    'content' => $item['content'],
                    'explanation' => $item['explanation'],
                    'question_id' => $question_id,
                    'is_correct' => $item['is_correct'],
                ];
                $this->answerReponsitory->create($data);
            }
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $exam = $this->examReponsitory->getExam($id);
        $exam['question_ids'] = $exam->questionIds->pluck('id');
        unset( $exam->questionIds);
        return $exam;
    }

    public function update($id, $inputs)
    {
        try {
            $data = [
                'title' => $inputs['title'],
                'slug' => isset($inputs['slug'])?$inputs['slug']:Str::slug($inputs['title']),
                'description' => $inputs['description'],
                'max_score' => $inputs['max_score'],
                'duration' => $inputs['duration'],
                'category_id' => $inputs['category_id'],
            ];
    
            $exam = $this->examReponsitory->update('id', $id, $data);
            
            if(isset($inputs['questions']))
            {
                $checkQuestionIds = [];
                $this->updateQuestions($inputs['questions'], $id, $checkQuestionIds);

                $getIdQuestionNotExist = array_merge(array_diff($inputs['question_ids'], $checkQuestionIds), array_diff($checkQuestionIds,$inputs['question_ids']));
                foreach($getIdQuestionNotExist as $question_id) {
                    $this->questionReponsitory->delete($question_id);
                } 
            }
            return response()->json(['success' => $exam??[]]);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateQuestions($questions = [], $exam_id, &$checkQuestionIds = [], $parent_id = null)
    {
        try {
            foreach($questions as $item) {
                $data = [
                    'content' => $item['content'],
                    'slug' => isset($item['slug'])?$item['slug']:Str::slug($item['content']),
                    'description' => $item['description'],
                    'reference_id' => $exam_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'type' => 0, //type = 0 => EXAM, type = 1 => EXERCISE
                ];
                if(isset($item['id'])) {
                    
                    $question = $this->questionReponsitory->update('id', $item['id'] ,$data);
                    array_push($checkQuestionIds, $item['id']);
                    if(isset($item['answers']) && count($item['answers']) > 0)
                    {
                        $this->updateAnswers($item['answers'], $item['id']);
                    }
                    if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                        $this->updateQuestions($item['questions_extends'], $exam_id, $checkQuestionIds, $item['id']);
                    }
                } else {
                    $question = $this->questionReponsitory->create($data);

                    if(isset($item['answers']) && count($item['answers']) > 0)
                    {
                        $this->createAnswers($item['answers'], $question->id);
                    }
                    if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                        $this->createQuestions($item['questions_extends'], $exam_id, $question->id);
                    }
                }
                
                
            }
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateAnswers($answers = [], $question_id)
    {
        try {
            $checkAnswerIds = [];
            $list_answer_id = $this->questionReponsitory->find($question_id)->answers->pluck('id')->toArray();

            foreach($answers as $item) {
                $data = [
                    'content' => $item['content'],
                    'explanation' => $item['explanation'],
                    'question_id' => $question_id,
                    'is_correct' => $item['is_correct'],
                ];
                if(isset($item['id'])){
                    $this->answerReponsitory->update('id',$item['id'], $data);
                    array_push($checkAnswerIds,$item['id']);
                } else {
                    $this->answerReponsitory->create($data);
                }
            }
            $getIdAnswerNotExist = array_merge(array_diff($list_answer_id, $checkAnswerIds), array_diff($checkAnswerIds,$list_answer_id));
            foreach($getIdAnswerNotExist as $answer_id) {
                $this->answerReponsitory->delete($answer_id);
            } 
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $questions = $this->examReponsitory->find($id)->questions->pluck('id')->toArray();
        foreach($questions as $item){
            $this->questionReponsitory->delete($item);
        }

        return $this->examReponsitory->delete($id);
    }
}