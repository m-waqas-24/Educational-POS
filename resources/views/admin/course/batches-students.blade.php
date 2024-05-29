@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> {{ $batch->course->name }} (Batch-{{ $batch->number }}) Students</h2>
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

        <div class="row clearfix">

            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#Home-withicon"><i class="fa fa-users mr-2"></i> Active Students List</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Home-withiconDiscontinued"><i class="fa fa-ban mr-2"></i> Discontinued Students List</a></li>
                            @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Profile-withicon"><i class="fa fa-dashboard mr-2"></i> Batch Report</a></li>
                            @endif
                        </ul>
                        <div class="tab-content" style="padding:0px; padding-top: 15px">
                            <div class="tab-pane active show " id="Home-withicon">

                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase">Name</th>
                                                            <th class="text-uppercase">Email</th>
                                                            <th class="text-uppercase">Last Payment</th>
                                                            <th class="text-uppercase">Status</th>
                                                            <th class="text-uppercase">Last Comment</th>
                                                            <th class="text-uppercase">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($studentCourses as $index => $course)
                                                        <tr style="{{ $course->statusStyle }}">
                                                            <td>{{ $course->student->name }}</td>
                                                            <td>{{ $course->student->user->email }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($course->getLatestPaymentDate())->diffForHumans() }}</td>
                                                            <td>
                                                                @if($course->status_id == 1)
                                                                    <span class="badge badge-primary">Paid</span>
                                                                @elseif($course->status_id == 2)
                                                                    <span class="badge badge-danger {{ $course->statusStyle ? 'text-white' : '' }}">Partial</span>
                                                                @endif
                                                            </td>
                                                            <td id="lastComment_{{$course->id}}" style="max-width: 200px; word-wrap: break-word;">
                                                                @if($course->comments->count() > 0)
                                                                    <?php
                                                                    $comment = $course->comments->last()->comments;
                                                                    $words = explode(' ', $comment);
                                                                    $newComment = '';
                                                                    $wordCount = 0;
                                                                    foreach ($words as $word) {
                                                                        $newComment .= $word . ' ';
                                                                        $wordCount++;
                                                                        if ($wordCount % 3 == 0) {
                                                                            $newComment .= '<br>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                    {!! $newComment !!}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#largeModal_{{ $course->id }}"><i class="fa fa-eye"></i> </a>
                                                                <a href="#" class="btn btn-warning add-comment" data-student-course="{{  $course->id }}">
                                                                    <i class="fa fa-comment"></i>
                                                                    {{ $course->comments ? ($course->comments->where('is_read', 0)->count() > 0 ? $course->comments->where('is_read', 0)->count() : '') : '' }}
                                                                </a>
                                                            </td>
                                                        </tr>
                    
                                                        <div class="modal fade" id="largeModal_{{ $course->id }}" tabindex="-1" role="dialog">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h4 class="title text-white" id="largeModalLabel">Student Details</h4>
                                                                    </div>
                                                                    <div class="modal-body"> 
                                                                        <div class="row clearfix">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                            
                                                                                <div class="card">
                                                                                    <div class="body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Student Name: </small>
                                                                                                <p><b> {{ $course->student->name }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Enrollment CSR: </small>
                                                                                                <p><b> {{ $course->student->csr->name }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Enrollment Date: </small>
                                                                                                <p><b> {{ \Carbon\Carbon::parse($course->coursePayments()->min('payment_date_first'))->format('d F, Y') }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">CNIC: </small>
                                                                                                <p><b> {{ $course->student->cnic }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Whatsapp: </small>
                                                                                                <p><b> {{ $course->student->whatsapp }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Student Status: </small>
                                                                                                <p>
                                                                                                    @if($course->is_continued)
                                                                                                    <span class="badge badge-success">Active</span>
                                                                                                    @else
                                                                                                    <span class="badge badge-danger">Discontinued</span>
                                                                                                    @endif
                                                                                                </p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <small class="">QUALIFICATION: </small>
                                                                                                <p><b> {{ $course->student->qualification->name }} @if($course->student->qualification->parent) ( {{ $course->student->qualification->parent->name }} ) @endif </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                            
                                                                                <div class="card">
                                                                                    <div class="header bg-primary">
                                                                                        <h2 class="text-light text-center text-uppercase">Course Details</h2>
                                                                                    </div>
                                                                                    <div class="body">
                                                                                        <section>
                                                                                            <div class="row">
                                                                                                <div class="col-md-12 text-right mb-2">
                                                                                                    @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                                                                                                    <script>
                                                                                                        function stuconfirmAction(courseId, action) {
                                                                                                            Swal.fire({
                                                                                                                title: "Are you sure?",
                                                                                                                text: (action == 'discontinued') ? "You are going to deactivate!" : "You are going to activate user!",
                                                                                                                icon: "warning",
                                                                                                                showCancelButton: true,
                                                                                                                confirmButtonColor: "#3085d6",
                                                                                                                cancelButtonColor: "#d33",
                                                                                                                confirmButtonText: "Yes"
                                                                                                            }).then((result) => {
                                                                                                                if (result.isConfirmed) {
                                                                                                                    // User confirmed, proceed with the action
                                                                                                                    window.location.href = "{{ route('admin.actOrDe.student') }}" + '/' + courseId + '/' + action;
                                                                                                                } else {
                                                                                                                    Swal.fire("Cancelled", "Your action was cancelled", "info");
                                                                                                                }
                                                                                                            });
                                                                                                        }
                                                                                                    </script>
                                                                                                        @if($course->is_continued)
                                                                                                            <button type="button" class="btn btn-warning" data-course-id="{{ $course->id }}" onclick="stuconfirmAction('{{ $course->id }}', 'discontinued')">
                                                                                                                <i class="fa fa-ban mr-2"></i> Discontinued Student
                                                                                                            </button>
                                                                                                        @else
                                                                                                            <button type="button" class="btn btn-info" data-course-id="{{ $course->id }}" onclick="stuconfirmAction('{{ $course->id }}', 'continued')">
                                                                                                                <i class="fa fa-check mr-2"></i> Activate Student
                                                                                                            </button>
                                                                                                        @endif
                                                                                                        <a href="{{ route('admin.edit.student', $course->student->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Student</a>
                                                                                                    @endif
                                                                                                    @if($course->status_id == 2 && getUserType() == 'csr')
                                                                                                        <button type="button" class="btn btn-primary make-payment" data-course-id="{{ $course->id }}"><i class="fa fa-money mr-2"></i> Make Payment</button>
                                                                                                        <button type="button" class="btn btn-secondary add-comment" data-course-id="{{ $course->id }}"><i class="fa fa-comment mr-2"></i> Comment</button>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Course:</dt>
                                                                                                        <dd>{{ $course->course->name }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Batch:</dt>
                                                                                                        <dd>{{ $course->batch->number }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Fee:</dt>
                                                                                                        <dd>{{ formatPrice($course->fee )  }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Discount: </dt>
                                                                                                        <dd> {{ formatPrice($course->discount) ?? 0 }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Student Card: </dt>
                                                                                                        <dd> {{ $course->student->card }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">Remaining Amount: </dt>
                                                                                                        <dd class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">{{  formatPrice($course->remainingfee() )  }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">Status: </dt>
                                                                                                        @if($course->status_id == 1)
                                                                                                            <span class="badge badge-primary">Paid</span>
                                                                                                        @elseif($course->status_id == 2)
                                                                                                            <span class="badge badge-danger">Partial</span>
                                                                                                        @endif
                                                                                                    </dl>
                                                                                                </div>
                                                                                            </div>  
                                                                                            <div class="row clearfix">
                                                                                                <div class="col-lg-12">
                                                                                                    <div class="card">
                                                                                                        <div class="card-header">
                                                                                                            <h5 class="text-center">Payment Timeline</h5>
                                                                                                        </div>
                                                                                                        <div class="body">
                                                                                                            @foreach($course->coursePayments->sortByDesc('created_at') as $payment)
                                                                                                                <div class="timeline-item green d-flex align-items-center justify-content-between" date-is="{{ \Carbon\Carbon::parse($payment->payment_date_first)->format('d F, Y') }}">
                                                    
                                                                                                                    <div>
                                                                                                                        <h5><i class="fa fa-money mr-2"></i> Paid Amount: {{ formatPrice($payment->payment_first) }} ({{ $payment->modeOne->name }}) </h5> 
                                                                                                                        @if($payment->payment_first_receipt)
                                                                                                                            <a href="{{ asset('storage/'.$payment->payment_first_receipt) }}" target="_blank" class="btn btn-warning my-2"><i class="fa fa-file mr-2"></i> Check Receipt</a>
                                                                                                                        @else
                                                                                                                            <span class="badge badge-danger my-2">Receipt not uploaded!</span>
                                                                                                                        @endif
                                                                                                                        @if($payment->is_edit_first)
                                                                                                                        <span class="fw-bold text-primary ">Edited</span>
                                                                                                                        @endif
                                                                                                                    </div>
                                                    
                                                                                                                    <div>
                                                                                                                        @if($payment->is_confirmed_first == 0 && auth()->user()->type == 'admin')
                                                                                                                            <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'first', '{{ $payment->id }}')">
                                                                                                                                <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                                            </a>
                                                                                                                        @elseif($payment->is_confirmed_first == 0 && auth()->user()->type == 'csr')
                                                                                                                            <span class="btn btn-danger"><i class="fa fa-times mr-2"></i> Not Confirmed Yet</span>
                                                                                                                        @elseif($payment->is_confirmed_first)
                                                                                                                            <span class="btn btn-success"><i class="fa fa-check mr-2"></i> Confirmed </span>
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                    
                                                                                                                </div>
                                                                                                                @if(@$payment->mode_second)
                                                                                                                <div class="timeline-item green d-flex align-items-center justify-content-between" date-is="{{ \Carbon\Carbon::parse($payment->payment_date_first)->format('d F, Y') }}">
                                                    
                                                                                                                <div>
                                                                                                                    <h5><i class="fa fa-money mr-2"></i> Paid Amount: {{ formatPrice($payment->payment_second) }} ({{ $payment->modeTwo->name }}) </h5>
                                                                                                                    @if($payment->payment_second_receipt)
                                                                                                                    <a href="{{ asset('storage/'.$payment->payment_second_receipt) }}" target="_blank" class="btn btn-warning my-2"><i class="fa fa-file mr-2"></i> Check Receipt</a>
                                                                                                                    @else
                                                                                                                    <span class="badge badge-danger my-2">Receipt not uploaded!</span>
                                                                                                                    @endif
                                                                                                                    @if($payment->is_edit_second)
                                                                                                                    <span class="fw-bold text-primary ">Edited</span>
                                                                                                                    @endif
                                                                                                                </div>
                                                    
                                                                                                                <div>
                                                                                                                    @if($payment->is_confirmed_second == 0 && auth()->user()->type == 'admin')
                                                                                                                        <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'second', '{{ $payment->id }}')">
                                                                                                                            <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                                        </a>
                                                                                                                    @elseif($payment->is_confirmed_second == 0 && auth()->user()->type == 'csr')
                                                                                                                        <span class="btn btn-danger"><i class="fa fa-times mr-2"></i> Not Confirmed Yet</span>
                                                                                                                    @elseif($payment->is_confirmed_second)
                                                                                                                        <span class="btn btn-success"><i class="fa fa-check mr-2"></i> Confirmed </span>
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </section>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                                                                    </div>
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

                            <div class="tab-pane " id="Home-withiconDiscontinued">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase">Name</th>
                                                            <th class="text-uppercase">Email</th>
                                                            <th class="text-uppercase">Last Payment</th>
                                                            <th class="text-uppercase">Status</th>
                                                            <th class="text-uppercase">Last Comment</th>
                                                            <th class="text-uppercase">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($discontinuedStudentCourses as $index => $course)
                                                        <tr style="{{ $course->statusStyle }}">
                                                            <td>{{ $course->student->name }}</td>
                                                            <td>{{ $course->student->user->email }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($course->getLatestPaymentDate())->diffForHumans() }}</td>
                                                            <td>
                                                                @if($course->status_id == 1)
                                                                    <span class="badge badge-primary">Paid</span>
                                                                @elseif($course->status_id == 2)
                                                                    <span class="badge badge-danger {{ $course->statusStyle ? 'text-white' : '' }}">Partial</span>
                                                                @endif
                                                            </td>
                                                            <td id="lastComment_{{$course->id}}" style="max-width: 200px; word-wrap: break-word;">
                                                                @if($course->comments->count() > 0)
                                                                    <?php
                                                                    $comment = $course->comments->last()->comments;
                                                                    $words = explode(' ', $comment);
                                                                    $newComment = '';
                                                                    $wordCount = 0;
                                                                    foreach ($words as $word) {
                                                                        $newComment .= $word . ' ';
                                                                        $wordCount++;
                                                                        if ($wordCount % 3 == 0) {
                                                                            $newComment .= '<br>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                    {!! $newComment !!}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#largeModal_{{ $course->id }}"><i class="fa fa-eye"></i> </a>
                                                                <a href="#" class="btn btn-warning add-comment" data-student-course="{{  $course->id }}">
                                                                    <i class="fa fa-comment"></i>
                                                                    {{ $course->comments ? ($course->comments->where('is_read', 0)->count() > 0 ? $course->comments->where('is_read', 0)->count() : '') : '' }}
                                                                </a>
                                                            </td>
                                                        </tr>
                    
                                                        <div class="modal fade" id="largeModal_{{ $course->id }}" tabindex="-1" role="dialog">
                                                            <div class="modal-dialog modal-xl" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h4 class="title text-white" id="largeModalLabel">Student Details</h4>
                                                                    </div>
                                                                    <div class="modal-body"> 
                                                                        <div class="row clearfix">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                            
                                                                                <div class="card">
                                                                                    <div class="body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Student Name: </small>
                                                                                                <p><b> {{ $course->student->name }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Enrollment CSR: </small>
                                                                                                <p><b> {{ $course->student->csr->name }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Enrollment Date: </small>
                                                                                                <p><b> {{ \Carbon\Carbon::parse($course->coursePayments()->min('payment_date_first'))->format('d F, Y') }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">CNIC: </small>
                                                                                                <p><b> {{ $course->student->cnic }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Whatsapp: </small>
                                                                                                <p><b> {{ $course->student->whatsapp }} </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <small class="">Student Status: </small>
                                                                                                <p>
                                                                                                    @if($course->is_continued)
                                                                                                    <span class="badge badge-success">Active</span>
                                                                                                    @else
                                                                                                    <span class="badge badge-danger">Discontinued</span>
                                                                                                    @endif
                                                                                                </p>
                                                                                                <hr>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <small class="">QUALIFICATION: </small>
                                                                                                <p><b> {{ $course->student->qualification->name }} @if($course->student->qualification->parent) ( {{ $course->student->qualification->parent->name }} ) @endif </b></p>
                                                                                                <hr>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                            
                                                                                <div class="card">
                                                                                    <div class="header bg-primary">
                                                                                        <h2 class="text-light text-center text-uppercase">Course Details</h2>
                                                                                    </div>
                                                                                    <div class="body">
                                                                                        <section>
                                                                                            <div class="row">
                                                                                                <div class="col-md-12 text-right mb-2">
                                                                                                    @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                                                                                                    <script>
                                                                                                        function stuconfirmAction(courseId, action) {
                                                                                                            Swal.fire({
                                                                                                                title: "Are you sure?",
                                                                                                                text: (action == 'discontinued') ? "You are going to deactivate!" : "You are going to activate user!",
                                                                                                                icon: "warning",
                                                                                                                showCancelButton: true,
                                                                                                                confirmButtonColor: "#3085d6",
                                                                                                                cancelButtonColor: "#d33",
                                                                                                                confirmButtonText: "Yes"
                                                                                                            }).then((result) => {
                                                                                                                if (result.isConfirmed) {
                                                                                                                    // User confirmed, proceed with the action
                                                                                                                    window.location.href = "{{ route('admin.actOrDe.student') }}" + '/' + courseId + '/' + action;
                                                                                                                } else {
                                                                                                                    Swal.fire("Cancelled", "Your action was cancelled", "info");
                                                                                                                }
                                                                                                            });
                                                                                                        }
                                                                                                    </script>
                                                                                                        @if($course->is_continued)
                                                                                                            <button type="button" class="btn btn-warning" data-course-id="{{ $course->id }}" onclick="stuconfirmAction('{{ $course->id }}', 'discontinued')">
                                                                                                                <i class="fa fa-ban mr-2"></i> Discontinued Student
                                                                                                            </button>
                                                                                                        @else
                                                                                                            <button type="button" class="btn btn-info" data-course-id="{{ $course->id }}" onclick="stuconfirmAction('{{ $course->id }}', 'continued')">
                                                                                                                <i class="fa fa-check mr-2"></i> Activate Student
                                                                                                            </button>
                                                                                                        @endif
                                                                                                        <a href="{{ route('admin.edit.student', $course->student->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Student</a>
                                                                                                    @endif
                                                                                                    @if($course->status_id == 2 && getUserType() == 'csr')
                                                                                                        <button type="button" class="btn btn-primary make-payment" data-course-id="{{ $course->id }}"><i class="fa fa-money mr-2"></i> Make Payment</button>
                                                                                                        <button type="button" class="btn btn-secondary add-comment" data-course-id="{{ $course->id }}"><i class="fa fa-comment mr-2"></i> Comment</button>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Course:</dt>
                                                                                                        <dd>{{ $course->course->name }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Batch:</dt>
                                                                                                        <dd>{{ $course->batch->number }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Fee:</dt>
                                                                                                        <dd>{{ formatPrice($course->fee )  }}</dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Discount: </dt>
                                                                                                        <dd> {{ formatPrice($course->discount) ?? 0 }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt>Student Card: </dt>
                                                                                                        <dd> {{ $course->student->card }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">Remaining Amount: </dt>
                                                                                                        <dd class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">{{  formatPrice($course->remainingfee() )  }} </dd>
                                                                                                    </dl>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <dl class="param">
                                                                                                        <dt class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">Status: </dt>
                                                                                                        @if($course->status_id == 1)
                                                                                                            <span class="badge badge-primary">Paid</span>
                                                                                                        @elseif($course->status_id == 2)
                                                                                                            <span class="badge badge-danger">Partial</span>
                                                                                                        @endif
                                                                                                    </dl>
                                                                                                </div>
                                                                                            </div>  
                                                                                            <div class="row clearfix">
                                                                                                <div class="col-lg-12">
                                                                                                    <div class="card">
                                                                                                        <div class="card-header">
                                                                                                            <h5 class="text-center">Payment Timeline</h5>
                                                                                                        </div>
                                                                                                        <div class="body">
                                                                                                            @foreach($course->coursePayments->sortByDesc('created_at') as $payment)
                                                                                                                <div class="timeline-item green d-flex align-items-center justify-content-between" date-is="{{ \Carbon\Carbon::parse($payment->payment_date_first)->format('d F, Y') }}">
                                                    
                                                                                                                    <div>
                                                                                                                        <h5><i class="fa fa-money mr-2"></i> Paid Amount: {{ formatPrice($payment->payment_first) }} ({{ $payment->modeOne->name }}) </h5> 
                                                                                                                        @if($payment->payment_first_receipt)
                                                                                                                            <a href="{{ asset('storage/'.$payment->payment_first_receipt) }}" target="_blank" class="btn btn-warning my-2"><i class="fa fa-file mr-2"></i> Check Receipt</a>
                                                                                                                        @else
                                                                                                                            <span class="badge badge-danger my-2">Receipt not uploaded!</span>
                                                                                                                        @endif
                                                                                                                        @if($payment->is_edit_first)
                                                                                                                        <span class="fw-bold text-primary ">Edited</span>
                                                                                                                        @endif
                                                                                                                    </div>
                                                    
                                                                                                                    <div>
                                                                                                                        @if($payment->is_confirmed_first == 0 && auth()->user()->type == 'admin')
                                                                                                                            <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'first', '{{ $payment->id }}')">
                                                                                                                                <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                                            </a>
                                                                                                                        @elseif($payment->is_confirmed_first == 0 && auth()->user()->type == 'csr')
                                                                                                                            <span class="btn btn-danger"><i class="fa fa-times mr-2"></i> Not Confirmed Yet</span>
                                                                                                                        @elseif($payment->is_confirmed_first)
                                                                                                                            <span class="btn btn-success"><i class="fa fa-check mr-2"></i> Confirmed </span>
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                    
                                                                                                                </div>
                                                                                                                @if(@$payment->mode_second)
                                                                                                                <div class="timeline-item green d-flex align-items-center justify-content-between" date-is="{{ \Carbon\Carbon::parse($payment->payment_date_first)->format('d F, Y') }}">
                                                    
                                                                                                                <div>
                                                                                                                    <h5><i class="fa fa-money mr-2"></i> Paid Amount: {{ formatPrice($payment->payment_second) }} ({{ $payment->modeTwo->name }}) </h5>
                                                                                                                    @if($payment->payment_second_receipt)
                                                                                                                    <a href="{{ asset('storage/'.$payment->payment_second_receipt) }}" target="_blank" class="btn btn-warning my-2"><i class="fa fa-file mr-2"></i> Check Receipt</a>
                                                                                                                    @else
                                                                                                                    <span class="badge badge-danger my-2">Receipt not uploaded!</span>
                                                                                                                    @endif
                                                                                                                    @if($payment->is_edit_second)
                                                                                                                    <span class="fw-bold text-primary ">Edited</span>
                                                                                                                    @endif
                                                                                                                </div>
                                                    
                                                                                                                <div>
                                                                                                                    @if($payment->is_confirmed_second == 0 && auth()->user()->type == 'admin')
                                                                                                                        <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'second', '{{ $payment->id }}')">
                                                                                                                            <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                                        </a>
                                                                                                                    @elseif($payment->is_confirmed_second == 0 && auth()->user()->type == 'csr')
                                                                                                                        <span class="btn btn-danger"><i class="fa fa-times mr-2"></i> Not Confirmed Yet</span>
                                                                                                                    @elseif($payment->is_confirmed_second)
                                                                                                                        <span class="btn btn-success"><i class="fa fa-check mr-2"></i> Confirmed </span>
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </section>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                                                                    </div>
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

                            @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                            <div class="tab-pane" id="Profile-withicon">
                                <div class="row clearfix" >
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-info"><i class="fa fa-users"></i> </div>
                                                <div class="content">
                                                    <div class="text">Total Students</div>
                                                    <h5 class="number">{{ formatPrice($studentCourses->count()) }}</h5>
                                                </div>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-warning"><i class="fa fa-user"></i> </div>
                                                <div class="content">
                                                    <div class="text">Paid Students</div>
                                                    <h5 class="number">{{ formatPrice($studentCourses->where('status_id',1)->count()) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-user"></i> </div>
                                                <div class="content">
                                                    <div class="text">Partial Students</div>
                                                    <h5 class="number">{{ formatPrice($studentCourses->where('status_id',2)->count()) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                            <div class="card top_counter" data-toggle="modal" data-target="#WorkshopModal">
                                                <div class="body">
                                                    <div class="icon text-info"><i class="fa fa-desktop"></i> </div>
                                                    <div class="content">
                                                        <div class="text">Workshops</div>
                                                        {{-- <h5 class="number">{{ count(getWorkshopsByBatchId($batch->id)) }}</h5> --}}
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-bars"></i> </div>
                                                <div class="content">
                                                    <div class="text">Orientation</div>
                                                    <h5 class="number"></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-ban"></i> </div>
                                                <div class="content">
                                                    <div class="text">Left Student</div>
                                                    <h5 class="number"> {{ $studentCourses->where('is_continued',0)->count() }} </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-calendar"></i> </div>
                                                <div class="content">
                                                    <div class="text">Batch Start Date</div>
                                                    <h5 class="number"> {{ $batch->starting_date }} </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card top_counter">
                                            <div class="body">
                                                <div class="icon text-danger"><i class="fa fa-close"></i> </div>
                                                <div class="content">
                                                    <div class="text">Batch Ending Date</div>
                                                    <h5 class="number"> {{ $batch->ending_date }} </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2>Students Enroll By Csr </h2>
                                            </div>
                                            <div class="body">
                                                <div id="Google-Analytics-Dashboard" style="height: 230px"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(getUserType() == 'superadmin')
                                    <div class="col-lg-6 col-md-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2>Fees Report</h2>
                                            </div>
                                            <div class="body">
                                                <ul class="list-unstyled">
                                                    @php
                                                        if ($totalFees > 0) {
                                                            $percentageRecovered = ($totalRecovered / $totalFees) * 100;
                                                        } else {
                                                            // Handle the case where totalFees is 0 to avoid division by zero
                                                            $percentageRecovered = 0;
                                                        }
                                                        // Calculate the remaining percentage
                                                        $totalReamining = $totalFees - $totalRecovered;
                                                        $percentageRemaining = 100 - $percentageRecovered;
                                                    @endphp 
                                                    <li>
                                                        <h6 class="d-flex justify-content-between align-items-center">
                                                            <span>{{ formatPrice($totalFees) }}</span>
                                                            <span class="text-muted font-14">Batch Total Fees</span>
                                                        </h6>
                                                        <div class="progress progress-xs progress-transparent custom-color-blue">
                                                            <div class="progress-bar" data-transitiongoal="100"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <h6 class="d-flex justify-content-between align-items-center">
                                                            <span>{{ formatPrice($totalRecovered) }}</span>
                                                            <span class="text-muted font-14">Total Received</span>
                                                        </h6>
                                                        <div class="progress progress-xs progress-transparent custom-color-purple">
                                                            <div class="progress-bar" data-transitiongoal="{{ $percentageRecovered }}"></div>
                                                        </div>
                                                    </li>                                
                                                    <li>
                                                        <h6 class="d-flex justify-content-between align-items-center">
                                                            <span>{{ formatPrice($totalReamining) }}</span>
                                                            <span class="text-muted font-14">Total Remaining</span>
                                                        </h6>
                                                        <div class="progress progress-xs progress-transparent custom-color-yellow">
                                                            <div class="progress-bar" data-transitiongoal="{{ $percentageRemaining }}"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@if(getUserType() == 'admin' || getUserType() == 'superadmin')
<div class="modal fade" id="WorkshopModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Workshops Details</h4>
            </div>
            <div class="modal-body">
                @if(!empty($workshops))
                    <div class="card">
                        <div class="header">
                            <h2>Student Workshops</h2>
                        </div>
                        <div class="body">
                            <div id="wizard_vertical">
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
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <h5 class="text-white text-center">Registered Students</h5>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-custom">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase">Student Name</th>
                                                                <th class="text-uppercase">Email</th>
                                                                <th class="text-uppercase">Phone</th>
                                                                <th class="text-uppercase">City</th>
                                                                <th class="text-uppercase">CNIC</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="height: 300px; overflow-y: auto">
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
                                    </section>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                <p class="text-danger">There is no Workshops created!</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@endif

@if(getUserType() == 'csr')
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">MAKE PAYMENT</h4>
            </div>
            <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body"> 
               <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Payment Date</label>
                                <input required type="date" class="form-control" name="payment_date_first">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Payment Details</label>
                                <div class="input-group mb-3">eb
                                  <select required class="form-select form-control" name="mode_first" id="inputGroupSelect02">
                                    <option value="">Select Payment Mode</option>
                                    @foreach($modes as $mode) 
                                        <option value="{{ $mode->id }}">{{ $mode->name }}</option> 
                                    @endforeach
                                  </select>
                                  <input required type="number" class="form-control" name="payment_first" min="0"  placeholder="Enter Received Payment "  >
                                  <input type="file" name="payment_first_receipt" class="form-control" required>
                                </div>
                            </div>
                              <div class="col-md-12">
                                <div class="input-group mb-3">
                                  <select  name="mode_second" class="form-select form-control" id="inputGroupSelect02">
                                    <option value="">Select Payment Mode</option>
                                    @foreach($modes as $mode) 
                                        <option value="{{ $mode->id }}">{{ $mode->name }} </option>
                                    @endforeach
                                  </select>
                                  <input type="number" min="0" class="form-control" name="payment_second" value="0" placeholder="Enter Payment(Optional)" >
                                  <input type="file" name="payment_second_receipt" class="form-control">
                                </div>
                              </div> 
                        </div>
                    </div>
                </div>

               </div>
               <div class="modal-footer">
                <button type="submit" class="btn btn-primary">PAY</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </div>
            
            </form>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="largeModalComment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Comments</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
            </div>
            <div class="modal-body"> 
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card chat-app">                            
                            <div class="chat" style="margin-left: 0">
                                <div class="chat-history" style="height: 400px; overflow-y: auto;">
                                    <ul class="mb-0">
                                        {{-- <li class="clearfix">
                                            <div class="message-data text-right">
                                                <span class="message-data-time" >10:10 AM, Today</span>
                                            </div>
                                            <div class="message other-message float-right"> Hi Aiden, how are you? How is the project coming along? </div>
                                        </li> --}}
                                    </ul>
                                </div>
                                <div class="chat-message clearfix">
                                    <form id="commentForm" action="{{ route('admin.student-course.comment') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group mb-0">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-message"></i></span>
                                            </div>
                                            <input required type="hidden" id="studentCourseId" name="studentCourseId" class="form-control" placeholder="Enter Comment here...">                                    
                                            <input required type="text" name="comment" class="form-control" placeholder="Enter Comment here..." autocomplete="off">                                 
                                            <button type="submit" class="btn btn-primary ml-2">Send <i class="fa fa-send"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<p style="display: none" id="authID">{{ Auth::user()->id }}</p>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
    function scrollChatHistoryToBottom() {
        let chatHistory = $('.chat-history');
        chatHistory.scrollTop(chatHistory[0].scrollHeight);
    }

    $('.chat-history').on('load', function() {
        scrollChatHistoryToBottom();
    });

    $('#largeModalComment').on('shown.bs.modal', function() {
        scrollChatHistoryToBottom();
    });

    let authUserId = $('#authID').text().trim();

    // Using event delegation for the click event
    $(document).on('click', '.add-comment', function(e) {
        e.preventDefault();
        var clickedButton = $(this);
        // Modify the HTML of the clicked button
        clickedButton.html('<i class="fa fa-comment"></i>');

        var studentCourseId = $(this).data('student-course');
        $('#commentForm input[name="studentCourseId"]').val(studentCourseId);

        $.ajax({
            url: "{{ route('admin.get.comments') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                studentCourseId: studentCourseId,
            },
            success: function(response) {
                let comments = response.comments;
                populateChatHistory(comments);
                $('#largeModalComment').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Function to populate the chat history
    function populateChatHistory(comments) {
        let chatHistory = $('.chat-history ul');
        chatHistory.empty();

        comments.forEach(function(comment) {
            let messageClass = (comment.user_id != authUserId) ? 'my-message' : 'other-message float-right';
            let messageTime = formatTime(comment.created_at);
            let messageDateTime = formatDate(comment.created_at);

            let liElement = $('<li>').addClass('clearfix');
            let messageData = $('<div>').addClass('message-data ' + (comment.user_id == authUserId ? 'text-right' : ''));
            let messageDataTime = $('<span>').addClass('message-data-time').html((comment.user_id != authUserId ? '<strong>' + comment.user.name + ": " + '</strong>' : '') + messageTime + ' - ' + messageDateTime);
            let messageContent = $('<div>').addClass('message ' + messageClass).text(comment.comments);

            messageData.append(messageDataTime);
            liElement.append(messageData);
            liElement.append(messageContent);

            chatHistory.append(liElement);
        });
    }

    // Function to format time
    function formatTime(timestamp) {
        let date = new Date(timestamp);
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        let timeString = hours + ':' + minutes + ' ' + ampm;
        return timeString;
    }

    // Function to format date
    function formatDate(timestamp) {
        let date = new Date(timestamp);
        let month = date.getMonth() + 1;
        let day = date.getDate();
        let year = date.getFullYear();
        let formattedDate = month + '/' + day + '/' + year;
        return formattedDate;
    }

    // Submit form via AJAX
    $('#commentForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                let newcomment = response.comment;

                $('#commentForm input[name="comment"]').val('');

                let liElement = $('<li>').addClass('clearfix');
                let messageData = $('<div>').addClass('message-data ' + (newcomment.user_id == authUserId ? 'text-right' : ''));
                let messageDataTime = $('<span>').addClass('message-data-time').text(formatTime(newcomment.created_at) + ' - ' + formatDate(newcomment.created_at));
                let messageContent = $('<div>').addClass('message other-message float-right').text(newcomment.comments);

                messageData.append(messageDataTime);
                liElement.append(messageData);
                liElement.append(messageContent);

                $('.chat-history ul').append(liElement);

                $('.chat-history').scrollTop($('.chat-history')[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
    
@endsection

@if(getUserType() == 'admin' || getUserType() == 'superadmin')
    @section('scripts')
    <script>
        var csrStudentCounts = {!! json_encode($csrStudentCounts) !!};
    
        var categories = Object.keys(csrStudentCounts);
        var data = Object.values(csrStudentCounts);
    
        $(document).ready(function(){
            var chart = c3.generate({
                bindto: '#Google-Analytics-Dashboard',  
                data: {
                    columns: [
                        ['Enroll Student', ...data], 
                    ],
                    type: 'bar',  
                    colors: {
                        'Enroll Student': '#1f77b4',  
                    },
                },
                axis: {
                    x: {
                        type: 'category',
                        categories: categories,
                    },
                },
                bar: {
                    width: {
                        ratio: 0.8 
                    }
                },
                legend: {
                    show: true,
                },
                padding: {
                    left: 40,  
                    right: 40,
                    bottom: 40,
                    top: 40
                },
            });
        });
    </script>
    
    @endsection
@endif

@section('scripts')

<script>
    $(document).ready(function() {
        $('.make-payment').click(function(){
            var courseId = $(this).data('course-id');

            var paymentFormAction = "{{ route('admin.student.make-payment') }}/" + courseId;
            $('#paymentForm').attr('action', paymentFormAction);
            $('#paymentModal').modal('show');
        });
    });
</script>

<script>
    function confirmAction(event, action, confirmationType, stuId) {
    event.preventDefault();

    Swal.fire({
        title: "Are you sure?",
        text: (action == 'approved') ? "You are going to approve!" : "You are going to activate user!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with the action
            sendRequest(action, confirmationType, stuId);
        } else {
            Swal.fire("Cancelled", "Your action was cancelled", "info");
        }
    });
}

function sendRequest(action, confirmationType, stuId) {
    if (action == 'approved') {
        window.location.href = "{{ route('admin.confirm.student') }}" + '/' + stuId + '/' + confirmationType;
    } 
}

</script>


@endsection