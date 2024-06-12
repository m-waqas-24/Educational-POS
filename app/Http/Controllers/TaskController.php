<?php

namespace App\Http\Controllers;

use App\Models\Admin\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasksByType = Task::with('taskType')->orderBy('type', 'ASC')->get()->groupBy(function($task) {
            return $task->taskType->name;   
        });
        $types = TaskStatus::all();
        return view('admin.tasks.index', compact('tasksByType', 'types'));
    }



    public function create()
    {
        $types = TaskStatus::all();
        return view('admin.tasks.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
        ]);

        Task::create($request->all());

        return redirect()->route('admin.index.task')->withSuccess('Task created successfully.');
    }

    public function edit(Request $request)
    {
        $task = Task::find($request->taskId);
        return response()->json(['task' => $task]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
        ]);
        $task = Task::find($id);

        $task->update($request->all());

        return redirect()->route('admin.index.task')->withSuccess('Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.index.task')->with('success', 'Task deleted successfully.');
    }
}
