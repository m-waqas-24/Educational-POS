@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Tasks List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Task </button>
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Title</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasksByType as $type => $tasks)
                                        <tr>
                                            <td colspan="2">
                                                <h4 class="bg-primary text-center text-white">{{ $type }}</h4>
                                            </td>
                                        </tr>
                                        @foreach ($tasks as $task)
                                            <tr>
                                                <td>{{ $task->title }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-primary edit-task" data-task-id="{{ $task->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.destroy.task', $task) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Task</h4>
            </div>
            <form action="{{ route('admin.store.task') }}" method="POST">
                @csrf
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Task</label>
                            <input type="text" required class="form-control" name="title" placeholder="Task">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Task Type</label>
                            <select name="type" class="form-control form-select" id="">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">SUBMIT</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Edit Task</h4>
            </div>
            <form id="editCourseForm" action="" method="POST">
                @csrf
                @method('PUT')
            <div class="modal-body"> 
               <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Task</label>
                            <input type="text" required class="form-control" name="title" placeholder="Task">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Task Type</label>
                            <select name="type" class="form-control form-select" id="">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">UPDATE</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')

<script>
    $(document).ready(function() {
       $('table').on('click', '.edit-task', function() {
            var taskId = $(this).data('task-id');

            $.ajax({
                url: '{{ route("admin.edit.task") }}', 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    taskId: taskId,
                },
                success: function(response) {
                    var task = response.task;
                    
                    $('#editCourseModal input[name="title"]').val(task.title); 
                    $('#editCourseModal select[name="type"]').val(task.type); 

                    var editCourseFormAction = "{{ route('admin.update.task') }}/" + task.id;
                    $('#editCourseForm').attr('action', editCourseFormAction);

                    $('#editCourseModal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

@endsection