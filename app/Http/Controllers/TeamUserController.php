<?php

namespace App\Http\Controllers;

use App\Models\{ TeamUser, Team, TeamInvitation };
use App\Http\Requests\StoreTeamUserRequest;
use App\Http\Requests\UpdateTeamUserRequest;
use Illuminate\Support\Facades\Auth;

class TeamUserController extends Controller
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
    public function store(StoreTeamUserRequest $request, $teamId)
    {
        $team = Team::findOrFail($teamId);
        $user = Auth::user();
        $invitation = TeamInvitation::where('invitee_id', $user->id)->where('team_id', $teamId)->where('status', 'pending')->first();
        if ($invitation) {
            TeamUser::create([
                'team_id' => $teamId,
                'user_id' => $user->id
            ]);
            $invitation->update([
                'status' => 'accepted'
            ]);
            return response()->json(['message' => 'User accepted the invitation and joined the team.']);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamUser $teamUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamUser $teamUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamUserRequest $request, TeamUser $teamUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamUser $teamUser)
    {
        //
    }
}
