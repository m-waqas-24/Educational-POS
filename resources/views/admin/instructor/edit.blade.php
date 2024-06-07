@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Edit Instructor</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Instructors</li>
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
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <form action="{{ route('admin.update.instructors',$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Instructor Name</label>
                                        <input type="text" required class="form-control" name="name" value="{{ $user->name }}" placeholder="Instructor Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="number" required class="form-control" name="phone" value="{{ $user->mob }}" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" required class="form-control" name="email" value="{{ $user->email }}" placeholder="Email">
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="text" class="form-control" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Courses</label>
                                    <div class="c_multiselect">
                                        <select id="multiselect1" name="courses[]" class="multiselect" multiple="multiple">
                                       
                                            @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                @if(in_array($course->id, $user->instructorCourses->pluck('course_id')->toArray())) 
                                                    selected 
                                                @endif
                                            >{{ $course->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-4">UPDATE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection