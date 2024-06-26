@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-12">
                    <h2> {{ $action ? $action->name : 'Today' }} Students List @if(@$from || @$to)  <strong>from {{ \Carbon\Carbon::parse($from)->format('d F, Y') }} to {{ \Carbon\Carbon::parse($to)->format('d F, Y') }}</strong> @endif </h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $action ? $action->name : 'Today' }} Students  </li>
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

        @if($action)
        <div class="card">
            <div class="body">
                <form action="{{ route('admin.filter.followup', $action->id) }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <input type="hidden" name="csr_id" value="{{ $csr ?? ''  }}">
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
        @endif
        
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
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->csr->name }}</td>
                                        <td>{{ $student->student->name }}</td>
                                        <td>{{ $student->student->email }}</td>
                                        <td><a href="https://wa.me/{{ $student->student->phone }}" target="_blank">{{ $student->student->phone }}</a></td>
                                        <td>{{ $student->student->cnic }}</td>  
                                        <td>{{ $student->student->course }}</td>
                                        <td>{{ $student->student->city }}</td>
                                        <td>{{ \Carbon\Carbon::parse($student->called_at)->format('d F Y h:i:A') }}</td>
                                        <td>
                                            @if(Auth::user()->id == $student->csr_id)
                                                <a href="{{ route('admin.csr.show.student', $student->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                            @endif
                                            @if(!$student->remarks && Auth::user()->type== 'csr' && Auth::user()->role_id == '1')
                                                <a href="#" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#largeModal_{{ $student->id }}"><i class="fa fa-info"></i></a>
                                            @endif
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="largeModal_{{ $student->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h4 class="title text-white" id="largeModalLabel">Follow Up</h4>
                                                </div>
                                                <form action="{{ route('admin.store.remarks',$student->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                <div class="modal-body"> 
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Remarks:</label>
                                                                <textarea required class="form-control" name="remarks" placeholder="Remarks...."></textarea>
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
