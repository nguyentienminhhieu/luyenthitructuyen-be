<?php

namespace App\Http\Controllers;

use App\AppMain\DTO\RankDTO;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\TakeExam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function getRank() {
        try {
            $grades = Grade::query()->select(['id','name','slug'])->get()->toArray();
            foreach($grades as &$grade) {
                $categories = Category::query()->where('grade_id', $grade['id'])->select(['id'])->get();
                $grade['take_exams'] = [];
                foreach($categories as $category) {
                    $category['exams'] = Exam::query()->where('category_id', $category['id'])->select(['id'])->get();

                    foreach($category['exams'] as $exam) {
                        $exam['take_exam'] = TakeExam::query()
                        ->with(['user' => function ($query) {
                            $query->select('id', 'name', 'email','school','avatar');
                        }])
                        ->where('exam_id', $exam['id'])
                        ->select(['id', 'total_score', 'user_id'])
                        ->get();

                        array_push($grade['take_exams'], $exam['take_exam']);
                    }
                }
            }
            // dd(new RankDTO($grades));
            return response()->json(['data' => 'pending']); 
        } catch (Exception $e) {
            Log::error($e);
            return $e;
        }
    }
}
