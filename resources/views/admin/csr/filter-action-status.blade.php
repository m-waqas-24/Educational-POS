@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> {{ $action->name }} Students List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $action->name }} Students</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Import Data </button> --}}
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="body">
                <form action="{{ route('admin.filter.followup', $action->id) }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-9">
                            <label>Range</label>                                    
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" value="{{ $from ?? '' }}" class="input-sm form-control" name="from" autocomplete="off">
                                <span class="input-group-addon mx-2">To</span>
                                <input type="text" value="{{ $to ?? '' }}" class="input-sm form-control" name="to" autocomplete="off">
                            </div>
                        </div>  
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search mr-2"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Student Name</th>
                                        <th class="text-uppercase">Email</th>
                                        <th class="text-uppercase">Phone</th>
                                        <th class="text-uppercase">CNIC</th>
                                        <th class="text-uppercase">Course</th>
                                        <th class="text-uppercase">City</th>
                                        <th class="text-uppercase">Last Called</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->student->name }}</td>
                                        <td>{{ $student->student->email }}</td>
                                        <td><a href="https://wa.me/{{ $student->student->phone }}" target="_blank">{{ $student->student->phone }}</a></td>
                                        <td>{{ $student->student->cnic }}</td>  
                                        <td>{{ $student->student->course }}</td>
                                        <td>{{ $student->student->city }}</td>
                                        <td>{{ \Carbon\Carbon::parse($student->called_at)->format('d F Y h:i:A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.csr.show.student', $student->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
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
