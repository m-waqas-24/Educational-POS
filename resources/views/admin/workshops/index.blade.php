@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> {{ $batch->course->name }} / Batch {{ $batch->number }} - Workshops List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Workshops</li>
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
                        <div class="wizard_vertical3" id="">
                            @foreach ($workshops as  $index => $work)
                                <h2>{{ $work->title }}</h2>
                                <section>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <dl class="param">
                                                <dt>Title: </dt>
                                                <dd>{{ $work->title }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-4">
                                            <dl class="param">
                                                <dt>Trainer: </dt>
                                                <dd>{{ $work->trainer }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-4">
                                            <dl class="param">
                                                <dt>Workshop Date & Time: </dt>
                                                <dd>{{ \Carbon\Carbon::parse($work->datetime)->format('d F, Y') }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-4">
                                            <dl class="param">
                                                <dt>Venue: </dt>
                                                <dd>{{ $work->venue }}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {{-- <a href="{{ route('admin.students.by.status', ['status' => 1, 'batch_id' => $batch->id]) }}"> --}}
                                                        <div class="card top_counter" style="height: 100px">
                                                            <div class="body">
                                                                <div class="icon text-warning"><i class="fa fa-user"></i> </div>
                                                                <div class="content">
                                                                    <div class="text">Total Students</div>
                                                                    <h5 class="number">{{ count($work->workshop_students) }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header bg-primary">
                                                    <h5 class="text-white text-center">Registered Students</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered ">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-uppercase">Student Name</th>
                                                                    <th class="text-uppercase">Email</th>
                                                                    <th class="text-uppercase">Phone</th>
                                                                    <th class="text-uppercase">City</th>
                                                                    <th class="text-uppercase">CNIC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($work->workshop_students as $index => $stu)
                                                                <tr>
                                                                    <td>{{ $stu->name }}</td>
                                                                    <td>{{ $stu->email }}</td>
                                                                    <td>{{ $stu->number }}</td>
                                                                    <td>{{ $stu->city }}</td>
                                                                    <td>{{ $stu->cnic }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection
