@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Courses List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            @if(auth()->user()->type == 'admin')
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Course </button>
                            @endif
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
                                        <th class="text-uppercase">Course Name</th>
                                        <th class="text-uppercase">Course Fee</th>
                                        <th class="text-uppercase">Card Fee</th>
                                        <th class="text-uppercase">Duration</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as  $course)
                                    <tr>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->fee }}</td>
                                        <td>{{ $course->card_fee }}</td>
                                        <td>{{ $course->duration }}</td>
                                        <td>
                                            @if(auth()->user()->type == 'admin')
                                                <a href="#" class="btn btn-primary edit-course" data-course-id="{{ $course->id }}"><i class="fa fa-edit"></i></a>
                                            @endif
                                            <a href="{{ route('admin.course.batch', $course->id) }}" class="btn btn-warning" ><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
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

@if(auth()->user()->type == 'admin')
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Course</h4>
            </div>
            <form action="{{ route('admin.store.course') }}" method="POST">
                @csrf
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Course Name</label>
                            <input type="text" required class="form-control" name="name" placeholder="Course Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Course Fee</label>
                            <input type="number" required class="form-control" name="fee" placeholder="Course Fee">
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Card Fee</label>
                            <input type="number" required class="form-control" name="card_fee" placeholder="Card Fee">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Duration</label>
                            <input type="text" required class="form-control" name="duration" placeholder="Duration">
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
                <h4 class="title text-white" id="largeModalLabel">Create New Course</h4>
            </div>
            <form id="editCourseForm" action="" method="POST">
                @csrf
                @method('PUT')
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Course Name</label>
                            <input type="text" required class="form-control" name="name" placeholder="Course Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Course Fee</label>
                            <input type="number" required class="form-control" name="fee" placeholder="Course Fee">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Card Fee</label>
                            <input type="number" required class="form-control" name="card_fee" placeholder="Card Fee">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Duration</label>
                            <input type="text" required class="form-control" name="duration" placeholder="Duration">
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
@endif

@endsection


@section('scripts')

<script>
    $(document).ready(function() {
       $('table').on('click', '.edit-course', function() {
            var courseId = $(this).data('course-id');

            $.ajax({
                url: '{{ route("admin.edit.course") }}', 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    courseId: courseId,
                },
                success: function(response) {
                    var course = response.course;
                    
                    $('#editCourseModal input[name="name"]').val(course.name); 
                    $('#editCourseModal input[name="duration"]').val(course.duration); 
                    $('#editCourseModal input[name="fee"]').val(course.fee);
                    $('#editCourseModal input[name="card_fee"]').val(course.card_fee); 

                    var editCourseFormAction = "{{ route('admin.update.course') }}/" + course.id;
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