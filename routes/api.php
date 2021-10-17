<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->name('auth.')->middleware('api')->group(function () {
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout')->name('logout');
    Route::get('/profile', 'AuthController@profile')->name('profile');
    Route::post('/refresh', 'AuthController@refresh')->name('refresh');
    Route::get('/required', function () {
        return response()->json([
            'error' => 'Authentication is required'
        ], 401);
    })->name('required');
});

Route::prefix('courses')->middleware('api')->name('courses.')->group(function () {
    Route::get('/', 'CourseController@list')->name('list');
    Route::post('/store', 'CourseController@store')->name('store');
    Route::put('/{id}/update', 'CourseController@update')->name('update');
    Route::get('/{id}/show', 'CourseController@show')->name('show');
    Route::get('/search-by-name/{slug}', 'CourseController@search_by_name')->name('search_by_name');
    Route::delete('/remove', 'CourseController@delete')->name('delete');
    Route::post('/completed', 'CourseController@completed')->name('completed');
    Route::post('/incompleted', 'CourseController@incompleted')->name('incompleted');
});

Route::prefix('trainers')->middleware('api')->name('trainers.')->group(function () {
    Route::get('/', 'TrainerController@list')->name('list');
    Route::post('/store', 'TrainerController@store')->name('store');
    Route::put('/{id}/update', 'TrainerController@update')->name('update');
    Route::get('/{id}/show', 'TrainerController@show')->name('show');
    Route::delete('/remove', 'TrainerController@delete')->name('delete');
});

Route::prefix('trainees')->middleware('api')->name('trainees.')->group(function () {
    Route::get('/', 'TraineeController@list')->name('list');
    Route::post('/store', 'TraineeController@store')->name('store');
    Route::put('/{id}/update', 'TraineeController@update')->name('update');
    Route::get('/{id}/show', 'TraineeController@show')->name('show');
    Route::delete('/remove', 'TraineeController@delete')->name('delete');
});
