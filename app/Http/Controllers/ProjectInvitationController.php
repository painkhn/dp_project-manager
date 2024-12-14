<?php

namespace App\Http\Controllers;

use App\Models\ProjectInvitation;
use App\Http\Requests\StoreProjectInvitationRequest;
use App\Http\Requests\UpdateProjectInvitationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectInvitationController extends Controller
{

    public function invite(Request $request, $projectId)
    {
        $request->validate([
            'invitee_id' => 'required|exists:users,id',
        ]);

        $invitation = ProjectInvitation::create([
            'project_id' => $projectId,
            'inviter_id' => Auth::id(),
            'invitee_id' => $request->invitee_id,
        ]);

        return response()->json(['message' => 'Приглашение отправлено', 'invitation' => $invitation]);
    }

    public function accept($invitationId)
    {
        $invitation = ProjectInvitation::findOrFail($invitationId);

        if ($invitation->invitee_id !== Auth::id()) {
            return response()->json(['message' => 'Вы не можете принять это приглашение'], 403);
        };

        $invitation->update(['status' => 'accepted']);

        $invitation->project->users()->attach($invitation->invitee_id);

        return response()->json(['message' => 'Приглашение принято']);
    }

    public function reject($invitationId)
    {
        $invitation = ProjectInvitation::findOrFail($invitationId);

        if ($invitation->invitee_id !== Auth::id()) {
            return response()->json(['message' => 'Вы не можете принять это приглашение'], 403);
        };

        $invitation->update(['status' => 'rejected']);

        return response()->json(['message' => 'Приглашение отклонено']);
    }
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
    public function store(StoreProjectInvitationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectInvitation $projectInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectInvitation $projectInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectInvitationRequest $request, ProjectInvitation $projectInvitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $projectInvitation = ProjectInvitation::where('id', $id)->first();

        if ($projectInvitation) {
            $projectInvitation->delete();
            return response()->json(['message' => 'Приглашение удалено']);
        }

        return response()->json(['message' => 'Приглашение не было найдено'], 404);
    }
}
