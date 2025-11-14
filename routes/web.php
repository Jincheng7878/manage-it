<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Shared (Students + Teachers)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // -----------------------------
    // Scenarios
    // -----------------------------
    Route::resource('scenarios', ScenarioController::class);

    Route::patch('/scenarios/{scenario}/toggle',
        [ScenarioController::class, 'toggle']
    )->name('scenarios.toggle');

    // -----------------------------
    // Decisions
    // -----------------------------
    Route::get('scenarios/{scenario}/decisions',
        [DecisionController::class, 'index']
    )->name('decisions.index');

    Route::get('scenarios/{scenario}/decisions/create',
        [DecisionController::class, 'create']
    )->name('decisions.create');

    Route::post('scenarios/{scenario}/decisions',
        [DecisionController::class, 'store']
    )->name('decisions.store');

    Route::delete('/decisions/{decision}',
        [DecisionController::class, 'destroy']
    )->name('decisions.destroy');

    // -----------------------------
    // Results
    // -----------------------------
    Route::resource('results', ResultController::class)
        ->only(['index', 'show']);

    // My decisions
    Route::get('/my-decisions',
        [ResultController::class, 'myDecisions']
    )->name('my.decisions');


    /*
    |--------------------------------------------------------------------------
    | ⭐ NEW: Comments Routes (评论区)
    |--------------------------------------------------------------------------
    */
    Route::post('/scenarios/{scenario}/comments',
        [CommentController::class, 'store']
    )->name('comments.store');

    Route::delete('/comments/{comment}',
        [CommentController::class, 'destroy']
    )->name('comments.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    // grading
    Route::get('scenarios/{scenario}/grade',
        [ResultController::class, 'gradeList']
    )->name('results.gradeList');

    Route::post('decisions/{decision}/grade',
        [ResultController::class, 'grade']
    )->name('results.grade');

    // analytics
    Route::get('/analytics',
        [AnalyticsController::class, 'scoreOverview']
    )->name('admin.analytics');

    Route::get('/analytics/scores',
        [AnalyticsController::class, 'scoreOverview']
    )->name('analytics.scores');
});

require __DIR__.'/auth.php';
