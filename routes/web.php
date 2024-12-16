<?php

use App\Http\Controllers\{ ProfileController, ProjectController, ProjectInvitationController, UserController, ProjectUserController };
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
    Route::controller(UserController::class)->group(function() {
        Route::get('/api/users', 'search')->name('user.search');
    });
    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile/{id}', 'index')->name('profile.index');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::get('/profile/update', 'edit')->name('profile.update');
        Route::get('/profile/destroy', 'edit')->name('profile.destroy');
        Route::post('/profile/new_avatar', 'updateAvatar')->name('new_avatar');
    });
    Route::controller(ProjectController::class)->group(function() {
        Route::get('/project/create', 'create_index')->name('project.create');
        Route::post('/project/store', 'store')->name('project.store');
        Route::get('/project/{id}', 'index')->name('project.index');
        Route::get('/project/{projectId}/invited_users', 'getInvitedUsers')->name('project.invited_users');
    });
    Route::controller(ProjectInvitationController::class)->group(function() {
        Route::post('/project/{projectId}/invite', 'invite')->name('project.invite');
        Route::post('/invitations/{invitationId}/accept', 'accept');
        Route::post('/invitations/{invitationId}/reject', 'reject');
        Route::delete('/invitations/{invitationId}/delete', 'destroy')->name('invitation.delete');
    });
    Route::controller(ProjectUserController::class)->group(function(): void {
        Route::get('/project/{projectId}/users/store', 'store')->name('project.user.store');
    });
});

require __DIR__.'/auth.php';
