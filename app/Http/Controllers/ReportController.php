<?php

namespace App\Http\Controllers;

use App\Models\{ Report, Task };
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
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
    public function store(StoreReportRequest $request, $taskId)
    {
        $task = Task::where('id', $taskId)->first();
        // dd($request->message);
        Report::create([
            'message' => $request->message,
            'file' => $request->file,
            'user_id' => Auth::id(),
            'task_id' => $task->id
        ]);
        $task->update([
            'status' => 'completed'
        ]);
    }

    public function back(StoreReportRequest $request, $taskId)
    {
        $task = Task::where('id', $taskId)->first();
        Report::create([
            'message' => $request->message,
            'file' => $request->file,
            'user_id' => Auth::id(),
            'task_id' => $task->id
        ]);
        $task->update([
            'status' => 'waiting'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
