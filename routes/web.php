<?php

use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\CqController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\McqController;
use App\Http\Controllers\Admin\OcrController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\ShortQuestionController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\PaperBuilderController;
use App\Http\Controllers\PaperController;
use Illuminate\Support\Facades\Route;

use App\Models\Paper;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route(
        auth()->user()->isAdmin() ? 'admin.dashboard' : 'papers.index'
    ))->name('dashboard');

    // Paper builder (user)
    Route::get('/papers/builder', [PaperBuilderController::class, 'index'])->name('papers.builder');
    Route::post('/papers/fetch', [PaperBuilderController::class, 'fetch'])->name('papers.fetch');

    Route::resource('papers', PaperController::class)->except(['create', 'edit', 'update']);
    Route::get('/papers/{paper}/download', [PaperController::class, 'download'])->name('papers.download');

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('classes', SchoolClassController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('chapters', ChapterController::class);
        Route::resource('mcqs', McqController::class)->except(['show']);
        Route::resource('shorts', ShortQuestionController::class)->except(['show']);
        Route::resource('cqs', CqController::class)->except(['show']);

        Route::get('/ocr', [OcrController::class, 'create'])->name('ocr.create');
        Route::post('/ocr', [OcrController::class, 'extract'])->name('ocr.extract');

Route::get('/api/subjects', fn (\Illuminate\Http\Request $r) =>
    \App\Models\Subject::where('class_id', $r->query('class_id'))
        ->orderBy('name')
        ->get(['id', 'name'])
);

Route::get('/api/chapters', fn (\Illuminate\Http\Request $r) =>
    \App\Models\Chapter::where('subject_id', $r->query('subject_id'))
        ->orderBy('position')
        ->get(['id', 'name'])
);
    });

    Route::get('/api/subjects', fn (\Illuminate\Http\Request $r) =>
    \App\Models\Subject::where('class_id', $r->query('class_id'))->orderBy('name')->get(['id', 'name'])
);

Route::get('/api/chapters', fn (\Illuminate\Http\Request $r) =>
    \App\Models\Chapter::where('subject_id', $r->query('subject_id'))->orderBy('position')->get(['id', 'name'])
);
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    $totalPapers = $user->papers()->count();
    $latestPaper = $user->papers()->latest()->first();
    $recentPapers = $user->papers()->latest()->take(5)->get();

    return view('dashboard', compact('totalPapers', 'latestPaper', 'recentPapers'));
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
