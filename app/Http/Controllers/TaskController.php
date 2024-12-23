<?php

namespace App\Http\Controllers;

use App\Models\{ Task, Project };
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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
    public function store(StoreTaskRequest $request, $id)
    {
        // dd($request);
        $user_id = Auth::id();
        $project = Project::findOrFail($id);

        Task::create([
            'user_id' => $request->user_id ?: $user_id,
            'project_id' => $project->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'waiting',
        ]);

        return redirect()->back()->with('success', 'Задача успешно создана!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task) {
            $task->delete();
            return response()->json(['message' => 'Задача удалена']);
        }
        return response()->json(['message' => 'Задача не найдена']);
    }
}
