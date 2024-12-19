<?php

namespace App\Http\Controllers;

use App\Models\{ ProjectUser, Project, User, ProjectInvitation };
use App\Http\Requests\StoreProjectUserRequest;
use App\Http\Requests\UpdateProjectUserRequest;
use Auth;

class ProjectUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectUserRequest $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $user = Auth::user();
        $invitation = ProjectInvitation::where('invitee_id', $user->id)->where('project_id', $project->id)->where('status', 'pending')->first();
        if ($invitation) {
            ProjectUser::create([
                'project_id' => $project->id,
                'user_id' => $user->id
            ]);
            $invitation->update([
                'status' => 'accepted'
            ]);
            return response()->json(['Статус приглашения изменён']);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectUser $projectUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectUser $projectUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectUserRequest $request, ProjectUser $projectUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $projectUser = ProjectUser::where('user_id', $id)->first();
        
        if ($projectUser) {
            $projectUser->delete();
            return response()->json(['Пользователь удалён из проекта']);
        }

        return response()->json(['Не удалось удалить пользователя']);
    }
}
