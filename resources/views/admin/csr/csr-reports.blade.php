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
                <form action="{{ route('admin.search.csr.reports') }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-5">
                            <label>Range</label>  
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" value="" class="input-sm form-control" name="from" autocomplete="off">
                                <span class="input-group-addon mx-2">To</span>
                                <input type="text"  value="" class="input-sm form-control" name="to" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label>CSR</label>  
                            <select name="csr" class="form-control form-select" id="">
                                <option value="">Select CSR</option>
                                @foreach ($csrs as $csr)
                                    <option value="{{ $csr->id }}" >{{ $csr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search mr-2"></i> Search</button>
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
                                        <th class="text-uppercase">CSR</th>
                                        <th class="text-uppercase">Student Name</th>
                                        <th class="text-uppercase">Email</th>
                                        <th class="text-uppercase">Phone</th>
                                        <th class="text-uppercase">CNIC</th>
                                        <th class="text-uppercase">Course</th>
                                        <th class="text-uppercase">City</th>
                                        <th class="text-uppercase">Last Called</th>
                                        <th class="text-uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($csrStudents as $student)
                                    <tr>
                                        <td>{{ $student->csr->name }}</td>
                                        <td>{{ optional($student->student)->name }}</td>
                                        <td>{{ optional($student->student)->email }}</td>
                                        <td><a href="https://wa.me/{{ optional($student->student)->phone }}" target="_blank">{{ optional($student->student)->phone }}</a></td>
                                        <td>{{ optional($student->student)->cnic }}</td>  
                                        <td>{{ optional($student->student)->course }}</td>
                                        <td>{{ optional($student->student)->city }}</td>
                                        <td>
                                            @if($student->called_at)
                                            {{ \Carbon\Carbon::parse($student->called_at)->format('d F Y h:i:A') }}
                                            @else

                                            @endif
                                        </td>
                                        <td> <span class="badge badge-primary">{{ $student->action_status_id ? $student->statusAction->name : 'Uncalled' }}</span> </td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $csrStudents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection
