@extends('admin.layouts.app')
@section('content')

<div id="main-content" class="taskboard">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{ $course->name }} Batches Report</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $course->name }} Batches</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix row-deck">

            <div class="col-md-12">
                <div class="card progress_task">
                    <div class="header">
                    </div>
                    <div class="body ">

                    @if($course->batches->isEmpty())
                        <span class="text-danger">
                            Batches Not found!
                        </span>
                    @else
                        <ul class="nav nav-tabs-new2">
                                         
                            @foreach($batches as $batch)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active show' : '' }}" data-toggle="tab" href="#batch{{ $batch->id }}">Batch {{ $batch->number }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($batches as $batch)
                                
                                <div class="tab-pane {{ $loop->first ? 'active show' : '' }}" id="batch{{ $batch->id }}">

                                    <div class="row clearfix">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="body">
                                                    <style>
                                                        .table-container {
                                                            overflow-x: auto;
                                                        }
                                                        .table-custom th, .table-custom td {
                                                            min-width: 150px;
                                                            white-space: pre-wrap; /* To allow breaking within words */
                                                            word-wrap: break-word; /* To break long words */
                                                        }
                                                    </style>
                                                    <form action="{{ route('admin.storeReport') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                                    
                                                        <!-- Tab Navigation -->
                                                        <ul class="nav nav-tabs-new2">
                                                            @foreach($tasks as $type => $groupedTasks)
                                                                <li class="nav-item">
                                                                    <a class="nav-link @if($loop->first) active show @endif" data-toggle="tab" href="#{{ str_replace(' ', '-', $type) }}">{{ $type }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                    
                                                        <!-- Tab Content -->
                                                        <div class="tab-content">
                                                            @foreach($tasks as $type => $groupedTasks)
                                                                <div class="tab-pane @if($loop->first) show active @endif" id="{{ str_replace(' ', '-', $type) }}">
                                                                    <div class="table-container">
                                                                        <table class="table table-bordered table-hover  table-custom">
                                                                            <thead>
                                                                                <tr>
                                                                                    {{-- <th>Task Type</th> --}}
                                                                                    <th>Task</th>
                                                                                    <th>Status</th>
                                                                                    <th>Deadline</th>
                                                                                    <th>Clarification</th>
                                                                                    <th>Verified</th>
                                                                                    <th>Rechecked</th>
                                                                                    <th>Comment</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($groupedTasks as $index => $task)
                                                                                    @php
                                                                                        $existingReport = $batch->taskReports->firstWhere('task_id', $task->id);
                                                                                    @endphp
                                                                                    <tr>
                                                                                        {{-- @if($loop->first)
                                                                                            <td class="bg-primary text-white" rowspan="{{ $groupedTasks->count() }}">{{ $type }}</td>
                                                                                        @endif --}}
                                                                                        <td class="task-title">{{ $task->title }}</td>
                                                                                        <td>
                                                                                            <input type="hidden" name="task_reports[{{ $type }}_{{ $index }}][task_id]" value="{{ $task->id }}">
                                                                                            <select name="task_reports[{{ $type }}_{{ $index }}][status]" class="form-control form-select">
                                                                                                <option value="" selected>Select Status</option>
                                                                                                <option value="1" {{ @$existingReport->status == 1 ? 'selected' : '' }}>Active</option>
                                                                                                <option value="0" {{ @$existingReport->status === 0 ? 'selected' : '' }}>Inactive</option>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="date" name="task_reports[{{ $type }}_{{ $index }}][deadline]" class="form-control" value="{{ $existingReport ? $existingReport->deadline : '' }}">
                                                                                        </td>
                                                                                        <td>
                                                                                            <textarea name="task_reports[{{ $type }}_{{ $index }}][clarification]" class="form-control" cols="10" rows="6" placeholder="Clarification">{{ $existingReport ? $existingReport->clarification : '' }}</textarea>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="checkbox" name="task_reports[{{ $type }}_{{ $index }}][verified]" value="1" class="form-control" {{ $existingReport && $existingReport->verified ? 'checked' : '' }}>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="checkbox" name="task_reports[{{ $type }}_{{ $index }}][rechecked]" value="1" class="form-control" {{ $existingReport && $existingReport->rechecked ? 'checked' : '' }}>
                                                                                        </td>
                                                                                        <td>
                                                                                            <textarea name="task_reports[{{ $type }}_{{ $index }}][comment]" class="form-control" cols="10" rows="6" placeholder="Comment">{{ $existingReport ? $existingReport->comment : '' }}</textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                    
                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">Save Report</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        

        </div>
    </div>
</div>
    
@endsection