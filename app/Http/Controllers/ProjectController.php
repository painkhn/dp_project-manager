<?php

namespace App\Http\Controllers;

use App\Models\{ Project, ProjectInvitation, ProjectUser };
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $project = Project::with('user')->with('invitations', 'invitations.invitee')->where('id', $id)->findOrFail($id);
        $pendingInvitations = ProjectInvitation::where('project_id', $id)->where('status', 'pending')->with('invitee')->get();
        $projectUser = ProjectUser::with('user')->where('project_id', $id)->get();
        // dd($projectUser);
        // dd($projectUser);
        // $invitation = ProjectInvitation::where('id', $id)->first();
        // dd($project->user);
        return Inertia::render('Project', [
            'project' => $project,
            'invitations' => $project->invitations,
            'pendingInvitations' => $pendingInvitations,
            'projectUsers' => $projectUser,
        ]);
    }

    public function create_index()
    {
        return Inertia::render('Project/ProjectCreate');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getInvitedUsers($projectId)
    {
        $project = Project::findOrFail($projectId);
        $invitedUsers = $project->invitations()->with('invitee')->get()->pluck('invitee');

        return response()->json($invitedUsers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();

        // $project = Project::create($validatedData);
        $project = Project::create([
            'user_id' => $user_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('index')->with('success', 'Проект успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
