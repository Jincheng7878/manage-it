<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Home / Root
|--------------------------------------------------------------------------
*/

// Root → scenario list for everyone
Route::get('/', function () {
    return redirect()->route('scenarios.index');
});


/*
|--------------------------------------------------------------------------
| Authenticated routes (students + teachers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ✅ Student / personal dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])
        ->name('dashboard');

    // Scenario CRUD
    Route::resource('scenarios', ScenarioController::class);

    // Decisions (student submissions)
    Route::get('scenarios/{scenario}/decisions', [DecisionController::class, 'index'])
        ->name('decisions.index');

    Route::get('scenarios/{scenario}/decisions/create', [DecisionController::class, 'create'])
        ->name('decisions.create');

    Route::post('scenarios/{scenario}/decisions', [DecisionController::class, 'store'])
        ->name('decisions.store');

    // Delete decision (owner or admin, logic in controller)
    Route::delete('decisions/{decision}', [DecisionController::class, 'destroy'])
        ->name('decisions.destroy');

    // Results: students see their own; teachers see all (handled in controller)
    Route::resource('results', ResultController::class)->only(['index', 'show']);
});


/*
|--------------------------------------------------------------------------
| Admin-only routes (teachers)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // Admin analytics dashboard
    Route::get('/admin/analytics', [AdminDashboardController::class, 'index'])
        ->name('admin.analytics');

    // Teacher grading
    Route::get('scenarios/{scenario}/grade',
        [ResultController::class, 'gradeList']
    )->name('results.gradeList');

    Route::post('decisions/{decision}/grade',
        [ResultController::class, 'grade']
    )->name('results.grade');
});


/*
|--------------------------------------------------------------------------
| Breeze Auth routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
