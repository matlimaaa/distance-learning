<?php

use App\Http\Controllers\V1\{
    CourseController
};
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index']);

Route::post('/courses', [CourseController::class, 'store']);

Route::get('/', function () {
    return response()->json([
        'message' => 'testing'
    ]);
});