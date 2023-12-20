<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\ExerciseReponsitory;
use App\AppMain\Reponsitory\QuestionReponsitory;
use App\AppMain\Reponsitory\AnswerReponsitory;
use App\AppMain\Reponsitory\CategoryReponsitory;
use App\Models\Exercise;
use App\AppMain\DTO\ExerciseDTO;
use App\Models\Question;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;


class ExerciseService {
    public $exerciseReponsitory;
    public $questionReponsitory;
    public $answerReponsitory;
    public $categoryReponsitory;
    public $takeExerciseReponsitory;

    public function __construct(ExerciseReponsitory $exerciseReponsitory,
    QuestionReponsitory $questionReponsitory,
    AnswerReponsitory $answerReponsitory,
    CategoryReponsitory $categoryReponsitory,
    ) {
        $this->exerciseReponsitory = $exerciseReponsitory;
        $this->questionReponsitory = $questionReponsitory;
        $this->answerReponsitory = $answerReponsitory;
        $this->categoryReponsitory = $categoryReponsitory;
    }

    public function list($inputs)
    {
        return $this->exerciseReponsitory->getAll($inputs);
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
    
            $exercise = $this->exerciseReponsitory->create($data);
            
            if(isset($inputs['questions']))
            {
                $this->createQuestions($inputs['questions'], $exercise->id);
            }
            return response()->json(['success' => $exercise]);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createQuestions($questions = [], $exercise_id, $parent_id = null)
    {
        try {
            foreach($questions as $item) {
                $data = [
                    'content' => $item['content'],
                    'slug' => 'slug',
                    'description' => $item['description'],
                    'reference_id' => $exercise_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'explanation' => $item['explanation']??'',
                    'page' => isset($item['page'])?$item['page']:false,
                    'type' => Question::EXERCISE, //type = 0 => Exercise, type = 1 => EXERCISE
                ];
                $question = $this->questionReponsitory->create($data);
                if(isset($item['answers']) && count($item['answers']) > 0)
                {
                    $this->createAnswers($item['answers'], $question->id);
                }
                if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                    $this->createQuestions($item['questions_extends'], $exercise_id, $question->id);
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
        $exercise = $this->exerciseReponsitory->getExercise($id, $teacher_id);
        if($exercise) {
            $exercise['question_ids'] = $exercise->questionIds->pluck('id');
            unset( $exercise->questionIds);
            return $exercise;
        } else {
            return false;
        }
    }

    public function update($id, $inputs, $teacher_id = null)
    {
        try {
            if(isset($teacher_id)) {
                $checkExercise = $this->exerciseReponsitory->checkExerciseCreateByTeacher($id, $teacher_id);
                if(!isset($checkExercise)) {
                    return false;
                }
            }
            
            $data = [
                'title' => $inputs['title'],
                'slug' => isset($inputs['slug'])?$inputs['slug']:Str::slug($inputs['title']),
                'description' => $inputs['description'],
                'max_score' => $inputs['max_score'],
                'duration' => $inputs['duration'],
                'url_img' => $inputs['url_img']??'',
                'category_id' => $inputs['category_id'],
            ];
    
            $exercise = $this->exerciseReponsitory->update('id', $id, $data);
            
            if(isset($inputs['questions']))
            {
                $checkQuestionIds = [];
                $this->updateQuestions($inputs['questions'], $id, $checkQuestionIds);
                $getIdQuestionNotExist = array_merge(array_diff($inputs['question_ids'], $checkQuestionIds), array_diff($checkQuestionIds,$inputs['question_ids']));
                
                foreach($getIdQuestionNotExist as $question_id) {
                    $this->questionReponsitory->delete($question_id);
                } 
            }
            return response()->json(['success' => $exercise??[]]);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateQuestions($questions = [], $exercise_id, &$checkQuestionIds = [], $parent_id = null)
    {
        try {
            foreach($questions as $item) {
                $data = [
                    'content' => $item['content'],
                    'slug' => 'slug',
                    'description' => $item['description'],
                    'reference_id' => $exercise_id,
                    'parent_id' => $parent_id,
                    'file' => $item['file'],
                    'explanation' => $item['explanation']??'',
                    'page' => isset($item['page'])?$item['page']:false,
                    'type' => Question::EXERCISE, //type = 0 => Exercise, type = 1 => EXERCISE
                ];
                if(isset($item['id'])) {
                    $question = $this->questionReponsitory->update('id', $item['id'] ,$data);
                    array_push($checkQuestionIds, $item['id']);
                    if(isset($item['answers']) && count($item['answers']) > 0)
                    {
                        $this->updateAnswers($item['answers'], $item['id']);
                    }
                    if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                        $this->updateQuestions($item['questions_extends'], $exercise_id, $checkQuestionIds, $item['id']);
                    }
                } else {
                    $question = $this->questionReponsitory->create($data);

                    if(isset($item['answers']) && count($item['answers']) > 0)
                    {
                        $this->createAnswers($item['answers'], $question->id);
                    }
                    if(isset($item['questions_extends']) && count($item['questions_extends']) > 0) {
                        $this->createQuestions($item['questions_extends'], $exercise_id, $question->id);
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
                    'explanation' => $item['explanation']??'',
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
            $checkExercise = $this->exerciseReponsitory->checkExerciseCreateByTeacher($id, $teacher_id);
            if(!isset($checkExercise)) {
                return false;
            }
        }
        $questions = $this->exerciseReponsitory->find($id)->questions->pluck('id')->toArray();
        foreach($questions as $item){
            $this->questionReponsitory->delete($item);
        }

        return $this->exerciseReponsitory->delete($id);
    }

    public function activeExercise($id, $teacher_id = null)
    {
        try {
            if(isset($teacher_id)) {
                $checkExercise = $this->exerciseReponsitory->checkExerciseCreateByTeacher($id, $teacher_id);
                if(!isset($checkExercise)) {
                    return false;
                }
            }
            $exercise = $this->exerciseReponsitory->find($id);
            if(isset($exercise)) {
                $data = [
                    'is_active' => $exercise->is_active==Exercise::ACTIVE?Exercise::UN_ACTIVE:Exercise::ACTIVE,
                ];
                return $this->exerciseReponsitory->update('id',$id,$data);
            }
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //web

    public function listExercisesByTeacher($techer_id, $inputs)
    {
        return $this->exerciseReponsitory->listExercisesByTeacher($techer_id, $inputs);
    }

    public function listExercises($inputs)
    {
        $category_id = null;
        if(isset($inputs['category_slug'])) {
            $category = $this->categoryReponsitory->findOne('slug', $inputs['category_slug']);
            $category_id = $category->id??null;
        }
        return $this->exerciseReponsitory->listExercises($category_id, $inputs);
    }

    public function getExerciseBySlug($slug)
    {
        try {
            $exercise = $this->exerciseReponsitory->getExerciseBySlug($slug);
            if(isset($exercise)) {
                $dto = new ExerciseDTO($exercise->toArray());
                return $dto->formatData();
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitExercise($slug, $exercise_submit)
    {
        try {
            $exercise = $this->exerciseReponsitory->getExerciseBySlug($slug);
            if(isset($exercise)) {
                $dto = new ExerciseDTO($exercise->toArray(), $exercise_submit);
                $data = $dto->formatTakeExam();
                return $data;
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getExerciseHome () 
    {
        return $this->exerciseReponsitory->getExerciseHome();
    }
}