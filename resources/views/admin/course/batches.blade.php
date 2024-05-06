@extends('admin.layouts.app')
@section('content')
    <!-- mani page content body part -->
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h2>{{ $course->name }} Batches</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active">Batches List</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex flex-row-reverse">
                            <div class="page_action">
                                {{-- <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Download report</button>
                                <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Send report</button> --}}
                            </div>
                            <div class="p-2 d-flex">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                @foreach($batches as $batch)
                    <div class="col-md-3">
                        <div class="card">
                            <div class="body">
                                <h6 class="m-b-15">Batch No: <span class="badge badge-success float-right">{{ $batch->number }}</span></h6>                                
                                <h6 class="m-b-15">Total Students: <span class="badge badge-info float-right">{{ $batch->student->count() }}</span></h6>                                
                                <div class="text-center">
                                    <a href="{{ route('admin.batch.students', $batch->id) }}" class="btn btn-primary btn-sm">Students List <i class="fa fa-external-link ml-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @endsection