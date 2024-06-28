@extends('admin.layouts.app')
@section('content')

<div id="main-content" class="taskboard">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>All Courses</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Courses</li>
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
                        <div class="" data-plugin="nestable">
                            <ol class="dd-list d-flex flex-wrap">
                                @foreach($courses as $course)
                                    <li class="dd-item mr-4 mb-4" style="width: 45%" data-id="1">
                                        <div class="dd-handle" style="font-size: 22px !important">{{ $course->name }} </div>
                                        <div class="dd-content p-15">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>Course Fee: <strong>{{ formatPrice($course->fee) }}</strong></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>Card Fee: <strong>{{ formatPrice($course->card_fee) }}</strong></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>Duration: <strong>{{ $course->duration }}</strong></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="action text-center">
                                                <a href="{{ route('admin.reports.batches', $course->id) }}" type="button" class="btn btn-sm btn-warning " title="Edit">All Batches <i class="fa fa-external-link ml-2"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                               
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        

        </div>
    </div>
</div>
    
@endsection