<?php

use App\Http\Controllers\V1\{
    CourseController
};
use Illuminate\Support\Facades\Route;

Route::name('courses.')->prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::post('/', [CourseController::class, 'store'])->name('store');
    Route::get('/{uuid}', [CourseController::class, 'show'])->name('show');
    Route::delete('/{uuid}', [CourseController::class, 'destroy'])->name('destroy');
    Route::put('/{uuid}', [CourseController::class, 'update'])->name('update');
});

Route::get('/', function () {
    return response()->json([
        'message' => 'testing'
    ]);
});