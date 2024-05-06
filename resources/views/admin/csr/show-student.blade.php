@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Student Profile</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <a href="{{ route('admin.csr.enroll-student', $student->id) }}" class="btn btn-primary"><i class="fa fa-user-plus mr-2"></i> Enroll Student</a>
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.csr.update.student', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="mail-compose m-b-20">
                            <a href="#" class="btn btn-primary btn-block"> Basic Information</a>
                        </div>
                        <div class="body">
                            <h5><small><b>Name :</b></small> {{ $student->student->name }}</h5>
                            <h5><small><b>Address :</b></small> {{ $student->student->city }}</h5>
                            <h5><small><b>Phone :</b></small> {{ $student->student->phone }}</h5>
                            <h5><small><b>Email :</b></small> {{ $student->student->email }}</h5>
                            <h5><small><b>CNIC :</b></small> {{ $student->student->cnic }}</h5>
                            <h5><small><b>Course :</b></small> <span class="badge badge-warning">{{ $student->student->course }}</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="mail-compose m-b-20">
                            <a href="#" class="btn btn-danger btn-block"> Actions</a>
                        </div>
                        <div class="body">
                            <div class="row">
                                @foreach ($csrActions as $key => $act)
                                    <div class="col-md-6">
                                        <div class="fancy-radio">
                                            <label>
                                                <input required name="status" type="radio" value="{{ $act->id }}" @if($student->action_status_id == $act->id) checked @endif>
                                                <span><i></i>{{ $act->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="">Comments: </label>
                    <textarea class="form-control" name="comments" id="" ></textarea>
                </div>
                <div class="col-md-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary"> SUBMIT</button>
                </div>
            </div>
        </form>

    </div>
</div>


@endsection