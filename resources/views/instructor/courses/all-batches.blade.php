@extends('instructor.layouts.app')
@section('content')

<div id="main-content" class="taskboard">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{ $course->name }} Batches</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $course->name }} Batches</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        {{-- <div class="page_action">
                            <button type="button" class="btn btn-primary">Generate Report</button>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#new_task">Add New</button>
                        </div> --}}
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix row-deck">

            {{-- <div class="col-lg-4 col-md-12">
                <div class="card planned_task">
                    <div class="header">
                        <h2>Planned</h2>
                    </div>
                    <div class="body taskboard">
                        <div class="" data-plugin="nestable">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle">#L1008</div>
                                    <div class="dd-content p-15">
                                        <h5>Job title</h5>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                        <ul class="list-unstyled team-info m-t-20 m-b-20">
                                            <li class="m-r-15"><small class="text-muted">Team</small></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar1.jpg') }}" title="Avatar" alt="Avatar"></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar2.jpg') }}" title="Avatar" alt="Avatar"></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar5.jpg') }}" title="Avatar" alt="Avatar"></li>
                                        </ul>
                                        <hr>
                                        <div class="action">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                            <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                        </div>
                                    </div>
                                </li>                                    
                            </ol>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-12">
                <div class="card progress_task">
                    <div class="header">
                        {{-- <h2>In progress</h2> --}}
                    </div>
                    <div class="body ">
                        <div class="" data-plugin="nestable">
                            <ol class="dd-list d-flex flex-wrap ">
                                @foreach($batches as $batch)
                                    <li class="dd-item mr-4" style="width: 45%" data-id="1">
                                        <div class="dd-handle"> #Batch-{{ $batch->batch->number }} </div>
                                        <div class="dd-content p-15">
                                            <h5>{{ $course->name }}</h5>
                                            <p>Batch Starting Date: <strong>{{ \Carbon\Carbon::parse($batch->batch->starting_date)->format('d F, Y') }}</strong></p>
                                            <p>Batch Ending Date: <strong>{{ \Carbon\Carbon::parse($batch->batch->ending_date)->format('d F, Y') }}</strong></p>
                                            <ul class="list-unstyled team-info m-t-20 m-b-20">
                                              
                                                <li class="m-r-15"><img src="{{ asset('assets/images/xs/avatar4.jpg') }}" title="Avatar" alt="Avatar"></li>
                                                {{-- <li class="m-r-15"> {{ $batch->batch->student->count() }} </li>
                                                <li class="m-r-15"><small class="-muted"><strong>Students,</strong></small></li> --}}
                                                <li class="m-r-15"> {{ $batch->batch->lectures->count() }} </li>
                                                <li class="m-r-15"><small class="-muted"><strong>Lectures</strong></small></li>
                                            </ul>
                                            <hr>
                                            <div class="action">
                                                <a href="{{ route('instructor.batch.lecture', $batch->batch_id) }}" type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-list mr-2"></i>Lectures</a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                               
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-4 col-md-12">
                <div class="card completed_task">
                    <div class="header">
                        <h2>Completed</h2>
                    </div>
                    <div class="body taskboard">
                        <div class="" data-plugin="nestable">
                            <ol class="dd-list">                                   
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle">#L1005</div>
                                    <div class="dd-content p-15">
                                        <h5>Job title</h5>
                                        <p>If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                                        <ul class="list-unstyled team-info m-t-20 m-b-20">
                                            <li class="m-r-15"><small class="text-muted">Team</small></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar4.jpg') }}" title="Avatar" alt="Avatar"></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar5.jpg') }}" title="Avatar" alt="Avatar"></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar6.jpg') }}" title="Avatar" alt="Avatar"></li>
                                            <li><img src="{{ asset('assets/images/xs/avatar8.jpg') }}" title="Avatar" alt="Avatar"></li>
                                        </ul>
                                        <hr>
                                        <div class="action">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="icon-note"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Time"><i class="icon-clock"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Comment"><i class="icon-bubbles"></i></button>
                                            <button type="button" data-type="confirm" class="btn btn-sm btn-outline-danger float-right js-sweetalert" title="Delete"><i class="icon-trash"></i></button>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>
</div>
    
@endsection