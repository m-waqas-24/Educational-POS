@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Batch List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Batches</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Batch </button> --}}
                            <a href="{{ route('admin.create.batch') }}" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Create Batch</a>
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
                                      <th>Course .</th>
                                      <th>Batch No.</th>
                                      <th>Starting Date</th>
                                      <th>Ending Date</th>
                                      <th>Admission Ending Date</th>
                                      <th>Active Status</th>
                                      <th>Open Status</th>
                                      <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($batches as $batch)
                                    <tr>
                                        <td>{{$batch->course->name}}</td>
                                        <td>{{$batch->number}}</td>
                                        <td>{{$batch->starting_date}}</td>
                                        <td>{{$batch->ending_date}}</td>
                                        <td>{{$batch->adm_closing_date}}</td>
                                        <td>
                                          @if ($batch->is_active==1)
                                              <span class="badge badge-success">Active</span>
                                          @else
                                              <span class="badge badge-danger">Not Active</span>
                                          @endif
                                        </td>
                                        <td>
                                          @if ($batch->is_open)
                                              <span class="badge badge-success">Admissions Open</span>
                                          @else
                                              <span class="badge badge-danger">Admissions Closed</span>
                                          @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.edit.batch', $batch->id) }}" class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a>
                                            <a href="{{ route('admin.create.batch-lecture', $batch->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus me-2"></i> Lectures</a>
                                            <a href="{{ route('admin.batch-attendance-report', $batch->id) }}" class="btn btn-sm btn-info"> <i class="fa fa-list me-2"></i> Attendance Report</a>
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

@endsection
