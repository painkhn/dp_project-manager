<?php

namespace App\Http\Controllers;

use App\Models\{ Project, ProjectInvitation, ProjectUser, Task, Report };
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
            $project = Project::with('user', 'task.report')
                ->with('invitations', 'invitations.invitee')
                ->where('id', $id)
                ->findOrFail($id);

            $pendingInvitations = ProjectInvitation::where('project_id', $id)
                ->where('status', 'pending')
                ->with('invitee')
                ->get();

            $projectUser = ProjectUser::with('user')->where('project_id', $id)->get();

            // Получаем задачи и сортируем их так, чтобы задачи со статусом 'waiting' были первыми
            $tasks = Task::with('user', 'report', 'report.user')
                ->where('project_id', $id)
                ->get()
                ->sortBy(function ($task) {
                    return $task->status === 'waiting' ? 0 : 1; // 'waiting' идут первыми
                });

            return Inertia::render('Project', [
                'project' => $project,
                'invitations' => $project->invitations,
                'pendingInvitations' => $pendingInvitations,
                'projectUsers' => $projectUser,
                'tasks' => $tasks->values(), // Преобразуем коллекцию в индексированный массив
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
