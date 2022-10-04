<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getcommitdata',[CommitController::class,'index']);
Route::get('/commitdatas/{repo_id}',[CommitController::class,'getcommitdata']);
Route::get('/all-commit-data',[CommitController::class,'show']);
Route::post('/repo-url',[CommitController::class,'repoUrl']);
Route::get('/repourldata',[CommitController::class,'getData']);