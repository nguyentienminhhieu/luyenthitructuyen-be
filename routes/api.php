<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\TakeExamController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Models\Exam;
use App\Models\Exercise;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//web
Route::prefix('web') ->group(function() {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::post('/verify-email', [UserController::class, 'verifyEmail']);

    Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
        //user
        Route::get('/info-user', [UserController::class, 'infoUser']);
        Route::put('/update-user', [UserController::class, 'updateUser']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        //exam
        Route::get('/detail-exam', [ExamController::class, 'getExamBySlug']);
        Route::post('/submit-exam', [ExamController::class, 'submitExam']);
        Route::get('/list-history-exams-by-user', [TakeExamController::class, 'listHistoryExamByUser']);
        Route::get('/review-exam/{id}', [TakeExamController::class, 'reviewExamById']);
        
        //exercise
        Route::get('/detail-exercise', [ExerciseController::class, 'getExerciseBySlug']);
        Route::post('/submit-exercise', [ExerciseController::class, 'submitExercise']);
        //teacher 
            //exam
            Route::post('/create-exam', [ExamController::class, 'createExam']);
            Route::get('/list-exam-create-by-teacher', [ExamController::class, 'listExamsByTeacher']);
            Route::get('/exam/{id}', [ExamController::class, 'show']);
            Route::put('/update-exam/{id}', [ExamController::class, 'update']);
            Route::delete('/delete-exam/{id}', [ExamController::class, 'delete']);
            Route::post('/active-exam/{id}', [ExamController::class, 'activeExam']);
            Route::get('/list-exam-has-been-done-by-user', [TakeExamController::class, 'listExamsHasBeenDoneByUser']);
            Route::post('/comment-exam', [TakeExamController::class, 'commentExam']);
            Route::put('/update-comment-exam/{id}', [TakeExamController::class, 'updateCommentExam']);
            Route::delete('/delete-comment-exam/{id}', [TakeExamController::class, 'deleteCommentExam']);
            Route::get('/list-exams-has-user', [ExamController::class, 'listExamsHasUser']);
            //exercise
            Route::post('/create-exercise', [ExerciseController::class, 'createExercise']);
            Route::get('/list-exercise-create-by-teacher', [ExerciseController::class, 'listExercisesByTeacher']);
            Route::get('/exercise/{id}', [ExerciseController::class, 'show']);
            Route::put('/update-exercise/{id}', [ExerciseController::class, 'update']);
            Route::delete('/delete-exercise/{id}', [ExerciseController::class, 'delete']);
            Route::post('/active-exercise/{id}', [ExerciseController::class, 'activeExercise']);
    });
    //home
    Route::get('/get-rank-student', [HomeController::class, 'getRank']);
    Route::get('/list-exam-home', [HomeController::class, 'getExamHome']);
    Route::get('/list-exercise-home', [HomeController::class, 'getExerciseHome']);
    //grade
    Route::get('/list-grades', [GradeController::class, 'listGrade']);
    //subject
    Route::get('/list-subjects', [SubjectController::class, 'listSubject']);
    //category
    Route::get('/list-categories', [CategoryController::class, 'listCategory']);
    //exam
    Route::get('/list-exams', [ExamController::class, 'listExams']);

    //exercise
    Route::get('/list-exercises', [ExerciseController::class, 'listExercises']);
    
});


//admin
Route::prefix('admin') ->group(function() {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
        Route::get('/detail', [AdminController::class, 'detail']);
        Route::post('/create-account-admin', [AdminController::class, 'createAccount']);
        Route::resource('subject', SubjectController::class);
        Route::resource('grade', GradeController::class);
        Route::post('active-user', [AdminController::class, 'activeUser']);
        Route::post('active-admin', [AdminController::class, 'activeAdmin']);
        Route::get('list-admin', [AdminController::class, 'listAccount']);
        Route::delete('delete-admin/{id}', [AdminController::class, 'deleteAccount']);
        //user
        Route::get('list-user', [UserController::class, 'listUsers']);

        //category
        Route::get('/list-category', [CategoryController::class, 'index']);
        Route::get('/detail-category/{id}', [CategoryController::class, 'show']);
        Route::post('/create-category', [CategoryController::class, 'create']);
        Route::put('/update-category/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete-category/{id}', [CategoryController::class, 'delete']);

        //exam
        Route::get('/list-exam', [ExamController::class, 'listExam']);
        Route::post('/create-exam', [ExamController::class, 'createExam']);
        Route::get('/exam/{id}', [ExamController::class, 'show']);
        Route::put('/update-exam/{id}', [ExamController::class, 'update']);
        Route::delete('/delete-exam/{id}', [ExamController::class, 'delete']);
        Route::post('/active-exam/{id}', [ExamController::class, 'activeExam']);

        //exercise
        Route::get('/list-exercise', [ExerciseController::class, 'listExercise']);
        Route::post('/create-exercise', [ExerciseController::class, 'createExercise']);
        Route::get('/exercise/{id}', [ExerciseController::class, 'show']);
        Route::put('/update-exercise/{id}', [ExerciseController::class, 'update']);
        Route::delete('/delete-exercise/{id}', [ExerciseController::class, 'delete']);
        Route::post('/active-exercise/{id}', [ExerciseController::class, 'activeExercise']);
    });
});

Route::post('/upload', [FileUploadController::class, 'upload']);
