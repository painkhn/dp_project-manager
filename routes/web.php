<?php

use App\Http\Controllers\{ ProfileController, ProjectController, ProjectInvitationController, UserController, ProjectUserController, TeamController, TeamInvitationController, TeamUser, TaskController, TeamUserController, ReportController };
use App\Models\TeamInvitation;
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
    Route::controller(ProjectUserController::class)->group(function() {
        Route::post('/project/user/{id}/store', 'store')->name('project.user.store');
        Route::delete('/project/user/{id}/delete', 'destroy')->name('project.user.delete');
    });
    Route::controller(TeamController::class)->group(function() {
        Route::get('/user/{id}/teams/', 'index')->name('teams.list');
        Route::get('/team/create', 'create')->name('team.create');
        Route::post('/team/store', 'store')->name('team.store');
        Route::get('/team/{id}', 'show')->name('team.page');
        Route::delete('team/{id}/delete', 'destroy')->name('team.delete');
    });
    Route::controller(TeamInvitationController::class)->group(function() {
        Route::post('/team/{teamId}/invite', 'invite')->name('team.invite');
    });
    Route::controller(TeamUserController::class)->group(function() {
        Route::post('/team/user/{id}/store', 'store')->name('team.user.store');
    });
    Route::controller(TaskController::class)->group(function() {
        Route::post('/project/{id}/task/store', 'store')->name('task.store');
        Route::delete('/project/{id}/task/destroy', 'destroy')->name('task.destroy');
    });
    Route::controller(ReportController::class)->group(function() {
        Route::post('/task/{taskId}/report/store', 'store')->name('report.store');
        Route::patch('/task/{taskId}/report/back', 'back')->name('report.back');
    });
});

require __DIR__.'/auth.php';
