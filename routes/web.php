<?php

use App\Http\Controllers\{ ProfileController, ProjectController };
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile/{id}', 'index')->name('profile.index');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::get('/profile/update', 'edit')->name('profile.update');
        Route::get('/profile/destroy', 'edit')->name('profile.destroy');
    });
    Route::controller(ProjectController::class)->group(function() {
        Route::get('/project/create', 'create_index')->name('project.create');
        Route::post('/project/store', 'store')->name('project.store');
    });
});

require __DIR__.'/auth.php';
