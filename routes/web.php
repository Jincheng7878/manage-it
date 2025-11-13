<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Home / Dashboard
|--------------------------------------------------------------------------
*/

// Root URL -> redirect to scenarios list
Route::get('/', function () {
    return redirect()->route('scenarios.index');
});

// After login, redirect to scenarios list
Route::get('/dashboard', function () {
    return redirect()->route('scenarios.index');
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated routes (students + teachers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Scenario CRUD
    Route::resource('scenarios', ScenarioController::class);

    // Decisions (student submits decisions for a given scenario)
    Route::get('scenarios/{scenario}/decisions', [DecisionController::class, 'index'])
        ->name('decisions.index');

    Route::get('scenarios/{scenario}/decisions/create', [DecisionController::class, 'create'])
        ->name('decisions.create');

    Route::post('scenarios/{scenario}/decisions', [DecisionController::class, 'store'])
        ->name('decisions.store');
    // Delete a decision (owner or admin)
    Route::delete('decisions/{decision}', [DecisionController::class, 'destroy'])
        ->name('decisions.destroy');
    // Results: students see their own; teachers see all (logic in controller)
    Route::resource('results', ResultController::class)->only(['index', 'show']);
});


/*
|--------------------------------------------------------------------------
| Admin-only routes (teachers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // Teacher grading page for all decisions under a scenario
    Route::get('scenarios/{scenario}/grade', 
        [ResultController::class, 'gradeList']
    )->name('results.gradeList');

    // Teacher submits a grade for a specific decision
    Route::post('decisions/{decision}/grade', 
        [ResultController::class, 'grade']
    )->name('results.grade');

    // Score analytics page (charts)
    Route::get('/analytics/scores', [AnalyticsController::class, 'scoreOverview'])
        ->name('analytics.scores');
});


/*
|--------------------------------------------------------------------------
| Breeze Auth Routes (login, register, password reset, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
