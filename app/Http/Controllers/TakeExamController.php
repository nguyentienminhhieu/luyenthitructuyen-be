<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppMain\Services\TakeExamService;
use Exception;

class TakeExamController extends Controller
{
    protected $takeExamService;

    public function __construct(TakeExamService $takeExamService) {
        $this->takeExamService = $takeExamService;
    }

    public function listHistoryExamByUser()
    {
        try {
            $exam = $this->takeExamService->listHistoryExamByUser();

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function reviewExamById($id)
    {
        try {
            $exam = $this->takeExamService->reviewExamById($id);

            return response()->json(['data'=> $exam], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
