@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> Courses</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashbaord</li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row clearfix row-deck">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                        <thead>
                                <tr>
                                    <th class="text-uppercase">Course Name</th>
                                    <th class="text-uppercase">Duration</th>
                                    <th class="text-uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($instructorCourses as $cours)
                                <tr>
                                    <td>{{ $cours->course->name }}</td>
                                    <td>{{ $cours->course->duration }}</td>
                                    <td>
                                        {{-- @if(auth()->user()->type == 'admin')
                                            <a href="#" class="btn btn-primary edit-course" data-course-id="{{ $course->id }}"><i class="fa fa-edit"></i></a>
                                        @endif --}}
                                        <a href="{{ route('admin.instructor.batches', ['instructorId' => $cours->instructor_id, 'courseId' => $cours->course_id]) }}" class="btn btn-warning" ><i class="fa fa-eye mr-2"></i>Batches</a>
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
    
@endsection