@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Create Batch</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Batch</li>
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
                        <div class="row">			  
                            <div class="col-12">
                                  <div class="box">
                                    <!-- /.box-header -->
                                    <form class="form" action="{{ route('admin.store.batch') }}" method="POST">
                                      @csrf
                                        <div class="box-body">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Courses</label>
                                                  <select required class="form-select form-control courseSelect" name="course_id">
                                                    <option value="">Select Course</option>
                                                       @foreach($courses as $course) 
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option> 
                                                       @endforeach
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Batch No</label>
                                                  <input type="number" class="form-control" name="number" placeholder="Batch No">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Batch Starting Date</label>
                                                  <input type="date" class="form-control" name="starting_date" placeholder="Email">
                                                </div>    
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Batch Ending Date</label>
                                                  <input type="date" class="form-control" name="ending_date" placeholder="Email">
                                                </div>    
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Admission Closing Date</label>
                                                  <input type="date" class="form-control" name="adm_closing_date" placeholder="Email">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label class="form-label">Admission Opening Date</label>
                                                  <input type="date" class="form-control" name="adm_opening_date" placeholder="Email">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <input type="checkbox" id="md_checkbox_1" value="1" name="is_open" class="chk-col-primary mt-5"   />
                                                  <label for="md_checkbox_1">Open</label>
                                              </div>
                                              <div class="col-md-4">
                                                <input type="checkbox" id="md_checkbox_3" class="chk-col-primary mt-5" name="is_active"  value="1"/>
                                                <label for="md_checkbox_3">Active</label>
                                            </div>
                                            </div>
                                            <div class="text-center">
                                              <button type="submit" class="btn btn-primary btn-sm">SUBMIT</button>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                         
                                    </form>   
                                  </div>
                                  <!-- /.box -->			
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection

