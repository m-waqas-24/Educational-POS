@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Students List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Students</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <a id="downloadLink" href="{{ asset('assets/csv/demo.csv') }}" download class="btn btn-danger"><i class="fa fa-download"></i> CSV Format</a>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Import Data </button>
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
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link @if(!isset($from)) active show @endif" data-toggle="tab" href="#Home-withicon"><i class="fa fa-home mr-2"></i> New Import Data</a></li>
                            <li class="nav-item"><a class="nav-link @if(isset($from)) active show @endif" data-toggle="tab" href="#Profile-withicon"><i class="fa fa-user mr-2"></i> Distributed Data</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane @if(!isset($from)) show active @endif" id="Home-withicon">
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 0">
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
                                                        <th class="text-uppercase">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($importedStudents as  $student)
                                                    <tr>
                                                        <td>{{ $student->name }}</td>
                                                        <td>{{ $student->email }}</td>
                                                        <td>{{ $student->phone }}</td>
                                                        <td>{{ $student->cnic }}</td>
                                                        <td>{{ $student->course }}</td>
                                                        <td>{{ $student->city }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($student->datetime)->format('d F, Y') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane @if(isset($from)) show active @endif" id="Profile-withicon">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="body">
                                                <form action="{{ route('admin.filter.distribute-data') }}" method="GET" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row clearfix">
                                                        <div class="col-md-8">
                                                            <label>Range</label>  
                                                            <div class="input-daterange input-group" data-provide="datepicker">
                                                                <input type="text" value="{{ $from ?? '' }}" class="input-sm form-control" name="from" autocomplete="off">
                                                                <span class="input-group-addon mx-2">To</span>
                                                                <input type="text"  value="{{ $to  ?? '' }}" class="input-sm form-control" name="to" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mt-4">
                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search mr-2"></i> Search</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding: 0">
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
                                                        <th class="text-uppercase">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($distributedStudents as  $student)
                                                    <tr>
                                                        <td>{{ $student->name }}</td>
                                                        <td>{{ $student->email }}</td>
                                                        <td>{{ $student->phone }}</td>
                                                        <td>{{ $student->cnic }}</td>
                                                        <td>{{ $student->course }}</td>
                                                        <td>{{ $student->city }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($student->datetime)->format('d F, Y') }}</td>
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
            </div>
            
        </div>
        

    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">IMPORT NEW DATA</h4>
            </div>
            <form action="{{ route('admin.store.import-data') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body"> 
               <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>UPLOAD FILE FORMAT => <span class="text-danger"> Student Name , Email , Phone , CNIC , Course , City , Date </span> </label>
                        <input type="file" name="csv_file" accept=".csv" required class="form-control" >
                    </div> 
                </div>
                {{-- <div class="col-md-12">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date"  required class="form-control" >
                    </div> 
                </div> --}}
               </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">UPLOAD FILE</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')

<script>
    document.getElementById('downloadLink').addEventListener('click', function(e) {
        e.preventDefault(); 
        var csvUrl = this.getAttribute('href');
        var filename = csvUrl.substring(csvUrl.lastIndexOf('/') + 1); // Extract filename from URL
        var link = document.createElement('a');
        link.setAttribute('href', csvUrl);
        link.setAttribute('download', filename);
        link.click();
    });
    </script>
    
@endsection