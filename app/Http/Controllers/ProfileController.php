<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\{ User, Project, ProjectInvitation, TeamInvitation };
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index($id)
    {
        $projectInvitations = ProjectInvitation::where('invitee_id', $id)
            ->where('status', 'pending')
            ->with('project', 'inviter')
            ->get();

        $teamInvitations = TeamInvitation::where('invitee_id', $id)
            ->where('status', 'pending')
            ->with('team', 'inviter')
            ->get();
        // dd($invitations);
        $user = User::where('id', $id)->with('project')->first();
        // здесь нужно как то получить и передать приглашения
        return Inertia::render('Profile', [
            // 'user_id' => Auth::id(),
            'user' => $user,
            'projects' => Project::where('user_id', $user->id)->with('user')->get(),
            'projectInvitations' => $projectInvitations,
            'teamInvitations' => $teamInvitations,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'avatar_change' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // dd($request->all());
        
        $user = Auth::user();
        $avatarPath = public_path($user->avatar);
    
        // Удаляем старую аватарку, если она существует
        if ($user->avatar && file_exists($avatarPath)) {
            unlink($avatarPath);
        }
    
        // Сохраняем новую аватарку
        $name = time() . "." . $request->file('avatar_change')->extension();
        $destination = 'avatars'; // Путь для хранения аватарок
        $path = $request->file('avatar_change')->storeAs($destination, $name, 'public');
    
        // Обновляем путь к аватарке в базе данных
        $user->where('id', $user->id)->update([
            'avatar' => $path
        ]);
    
        return redirect()->back()->with('success', 'Аватарка успешно изменена!');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
