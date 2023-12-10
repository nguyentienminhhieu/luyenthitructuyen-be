<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\ExamReponsitory;
use App\AppMain\Reponsitory\QuestionReponsitory;
use App\AppMain\Reponsitory\AnswerReponsitory;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\AppMain\Reponsitory\TakeExamReponsitory;
use App\Models\Exam;
use App\Models\Question;
use App\AppMain\DTO\ExamDTO;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;


class ExamService {
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

    public function list($inputs)
    {
        return $this->examReponsitory->getAll($inputs);
    }

    public function create($inputs, $user_id = null)
    {
        try {
            $data = [
                'title' => $inputs['title'],
                'slug' => isset($inputs['slug'])?$inputs['slug']:Str::slug($inputs['title']),
                'description' => $inputs['description'],
                'max_score' => $inputs['max_score'],
                'duration' => $inputs['duration'],
                'url_img' => $inputs['url_img']??'',
                'category_id' => $inputs['category_id'],
                'user_id' => $user_id
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
                    'slug' => 'slug',
                    'description' => $item['description'],
                    'reference_id' => $exam_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'explanation' => $item['explanation']??'',
                    'page' => isset($item['page'])?$item['page']:false,
                    'type' => Question::EXAM, //type = 0 => EXAM, type = 1 => EXERCISE
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
                    'explanation' => $item['explanation']??'',
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

    public function show($id, $teacher_id)
    {
        $exam = $this->examReponsitory->getExam($id, $teacher_id);
        if($exam) {
            $exam['question_ids'] = $exam->questionIds->pluck('id');
            unset( $exam->questionIds);
            return $exam;
        } else {
            return false;
        }
    }

    public function update($id, $inputs, $teacher_id = null)
    {
        try {
            if(isset($teacher_id)) {
                $checkExam = $this->examReponsitory->checkExamCreateByTeacher($id, $teacher_id);
                if(!isset($checkExam)) {
                    return false;
                }
            }
            
            $data = [
                'title' => $inputs['title'],
                'slug' => isset($inputs['slug'])?$inputs['slug']:Str::slug($inputs['title']),
                'description' => $inputs['description'],
                'max_score' => $inputs['max_score'],
                'duration' => $inputs['duration'],
                'url_img' => $inputs['url_img'],
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
                    'slug' => 'slug',
                    'description' => $item['description'],
                    'reference_id' => $exam_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'explanation' => $item['explanation']??'',
                    'page' => isset($item['page'])?$item['page']:false,
                    'type' => Question::EXAM, //type = 0 => EXAM, type = 1 => EXERCISE
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

    public function delete($id, $teacher_id = null)
    {
        if(isset($teacher_id)) {
            $checkExam = $this->examReponsitory->checkExamCreateByTeacher($id, $teacher_id);
            if(!isset($checkExam)) {
                return false;
            }
        }
        $questions = $this->examReponsitory->find($id)->questions->pluck('id')->toArray();
        foreach($questions as $item){
            $this->questionReponsitory->delete($item);
        }

        return $this->examReponsitory->delete($id);
    }

    public function activeExam($id, $teacher_id = null)
    {
        try {
            if(isset($teacher_id)) {
                $checkExam = $this->examReponsitory->checkExamCreateByTeacher($id, $teacher_id);
                if(!isset($checkExam)) {
                    return false;
                }
            }
            $exam = $this->examReponsitory->find($id);
            if(isset($exam)) {
                $data = [
                    'is_active' => $exam->is_active==Exam::ACTIVE?Exam::UN_ACTIVE:Exam::ACTIVE,
                ];
                return $this->examReponsitory->update('id',$id,$data);
            }
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //web

    public function listExamsByTeacher($techer_id)
    {
        return $this->examReponsitory->listExamsByTeacher($techer_id);
    }

    public function listExamsByCategory($category_slug)
    {
        $category_id = $this->categoryReponsitory->findOne('slug', $category_slug)->id;
        return $this->examReponsitory->listExamsByCategory($category_id);
    }

    public function getExamBySlug($slug)
    {
        try {
            $exam = $this->examReponsitory->getExamBySlug($slug);
            if(isset($exam)) {
                $dto = new ExamDTO($exam->toArray());
                return $dto->formatData();
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitExam($slug, $exam_submit)
    {
        try {
            $exam = $this->examReponsitory->getExamBySlug($slug);
            if(isset($exam)) {
                $dto = new ExamDTO($exam->toArray(), $exam_submit);
                $data = $dto->formatTakeExam();
                return $this->takeExamReponsitory->create($data);
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
}