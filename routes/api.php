<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamController;
use App\Models\Exam;

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
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


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

        //category
        Route::get('/list-category', [CategoryController::class, 'index']);
        Route::get('/detail-category/{id}', [CategoryController::class, 'show']);
        Route::post('/create-category', [CategoryController::class, 'create']);
        Route::put('/update-category/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete-category/{id}', [CategoryController::class, 'delete']);

        //exam
        Route::post('/create-exam', [ExamController::class, 'createExam']);
    });
});
