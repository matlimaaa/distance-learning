<?php

use App\Http\Controllers\V1\{
    CourseController
};
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index']);

Route::get('/', function () {
    return response()->json([
        'message' => 'testing'
    ]);
});