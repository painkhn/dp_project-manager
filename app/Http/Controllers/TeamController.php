<?php

namespace App\Http\Controllers;

use App\Models\{ Team, User, TeamUser };
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = User::where('id', $id)->with('project')->first();
        $teams = Team::where('owner_id', $user->id)->get();
        // dd($teams);
        return Inertia::render('Teams', [
            'user' => $user,
            'teams' => $teams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Team/TeamCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();

        if (!$user_id) {
            return response()->json(['error' => 'Пользователь не авторизован'], 401);
        }
        // dd($user->id);
        // dd($request->all());
        $team = Team::create([
            'owner_id' => $user_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $user = Auth::user();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $team = Team::with('teamUsers')->where('id', $id)->first();
        // $owner = User::where('team_id', $id)->first();
        // dd($team);
        $teamUsers = TeamUser::with('user')->where('team_id', $id)->get();
        return Inertia::render('Team/TeamPage', [
            'team' => $team,
            'user' => $user,
            // 'owner' => $owner,
            'teamUsers' => $teamUsers
        ]);
    }

    public function invite()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::where('id', $id)->first();
        $team->delete();
        return redirect()->back();
    }
}
