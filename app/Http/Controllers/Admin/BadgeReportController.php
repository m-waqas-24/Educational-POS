<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Task;
use App\Models\Batch;
use App\Models\TaskReport;
use Illuminate\Http\Request;

class BadgeReportController extends Controller
{
    public function batchReport($id){
        $tasks = Task::with('taskType')->orderBy('type', 'ASC')->get()->groupBy(function($task) {
            return $task->taskType->name;   
        });
        $batch = Batch::with('taskReports')->find($id); // Load taskReports with batch

        return view('admin.batch-report.tasks', compact('tasks', 'batch'));
    }

    public function storeReport(Request $request)
    {
        $data = $request->validate([
            'batch_id' => 'required|integer',
            'task_reports' => 'required|array',
            'task_reports.*.task_id' => 'required|integer',
            'task_reports.*.status' => 'nullable|in:1,0',
            'task_reports.*.clarification' => 'nullable|string',
            'task_reports.*.deadline' => 'nullable|date',
            'task_reports.*.verified' => 'nullable|boolean',
            'task_reports.*.rechecked' => 'nullable|boolean',
            'task_reports.*.comment' => 'nullable|string',
        ]);
    
        foreach ($data['task_reports'] as $report) {
            TaskReport::updateOrCreate(
                [
                    'batch_id' => $data['batch_id'],
                    'task_id' => $report['task_id'],
                ],
                [
                    'status' => $report['status'] ?? null,
                    'clarification' => $report['clarification'] ?? null,
                    'deadline' => $report['deadline'] ?? null,
                    'verified' => $report['verified'] ?? false,
                    'rechecked' => $report['rechecked'] ?? false,
                    'comment' => $report['comment'] ?? null,
                ]
            );
    
            // \Log::info('Updated task report:', $report);
        }
    
        return redirect()->back()->with('success', 'Task reports saved successfully.');
    }
    
    


}
