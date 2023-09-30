<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeController;


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
    Route::get('/detail', [AdminController::class, 'detail'])->middleware('auth:sanctum', 'abilities:admin');
    Route::post('/create-account-admin', [AdminController::class, 'createAccount'])->middleware('auth:sanctum', 'abilities:admin');
    Route::resource('subject', SubjectController::class)->middleware('auth:sanctum', 'abilities:admin');
    Route::resource('grade', GradeController::class)->middleware('auth:sanctum', 'abilities:admin');
    Route::post('active-user', [AdminController::class, 'activeUser'])->middleware('auth:sanctum', 'abilities:admin');
});
