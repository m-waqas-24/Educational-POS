@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Instructor List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Instructors</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Instructor </button>
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
                                        <th class="text-uppercase">Instructor Name</th>
                                        <th class="text-uppercase">Email</th>
                                        <th class="text-uppercase">Phone</th>
                                        <th class="text-uppercase">Courses</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($instructors as  $ins)
                                    <tr>
                                        <td>{{ $ins->name }}</td>
                                        <td>{{ $ins->email }}</td>
                                        <td>{{ $ins->mob }}</td>
                                        <td>
                                            @foreach($ins->instructorCourses as $cour)
                                                <span class="btn btn-sm btn-primary mb-2">{{ $cour->course->name }}</span>
                                                @if($loop->iteration % 2 == 0)
                                                    <br>
                                                @endif
                                            @endforeach

                                        </td>
                                        <td>
                                            <a href="{{ route('admin.edit.instructor', $ins->id) }}" class="btn btn-primary edit-instructor" ><i class="fa fa-edit"></i></a>
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

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Instructor</h4>
            </div>
            <form action="{{ route('admin.store.instructor') }}" method="POST">
                @csrf
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Instructor Name</label>
                            <input type="text" required class="form-control" name="name" placeholder="Instructor Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="number" required class="form-control" name="phone" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" required class="form-control" name="email" placeholder="Email">
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="text" required class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Courses</label>
                        <div class="c_multiselect">
                            <select id="multiselect1" name="courses[]" class="multiselect" multiple="multiple">
                           
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
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




@endsection


