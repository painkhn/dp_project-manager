<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use App\Http\Requests\StoreTeamInvitationRequest;
use App\Http\Requests\UpdateTeamInvitationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamInvitationController extends Controller
{
    public function invite(Request $request, $teamId)
    {
        $request->validate([
            'invitee_id' => 'required|exists:users,id',
        ]);

        $invitation = TeamInvitation::create([
            'team_id' => $teamId,
            'inviter_id' => Auth::id(),
            'invitee_id' => $request->invitee_id,
        ]);

        return response()->json(['message' => 'Приглашение отправлено', 'invitation' => $invitation]);
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
    public function store(StoreTeamInvitationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamInvitation $teamInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamInvitation $teamInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamInvitationRequest $request, TeamInvitation $teamInvitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamInvitation $teamInvitation)
    {
        //
    }
}
