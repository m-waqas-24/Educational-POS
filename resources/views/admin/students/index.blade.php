@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> @if($status == 1) Paid @else Partial @endif Students List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Students</li>
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

        <div class="card">
            <div class="body">
                <form action="{{ route('admin.filter.students') }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="@if(getUserType() == 'admin' || getUserType() == 'superadmin' ) col-md-4 @else col-md-6 @endif">
                            <label>Range</label>  
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" value="{{ $from ?? '' }}" class="input-sm form-control" name="from" autocomplete="off">
                                <span class="input-group-addon mx-2">To</span>
                                <input type="text" value="{{ $to ?? '' }}" class="input-sm form-control" name="to" autocomplete="off">
                            </div>
                        </div>
                        @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                        <div class="col-md-3">
                            <label>Select CSR</label>
                            <select name="csr" class="form-control form-select" id="">
                                <option value="">Select CSR</option>
                                @foreach ($csrs as $csr)
                                    <option value="{{ $csr->id }}" @if($s_csr)  {{ $csr->id == $s_csr ? 'selected' : '' }} @endif>{{ $csr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(getUserType() == 'admin' || getUserType() == 'superadmin' || (getUserType() == 'csr' && Auth::user()->role_id == 1))
                        <div class="col-md-2">
                            <label>Select Course</label>
                            <select name="course" class="form-control form-select" id="">
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" >{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Select Batch</label>
                            <select name="batch" class="form-control form-select" id="">
                                <option value="">Batches</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->number }} ({{ $batch->course->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-1 mt-4">
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
                                        <th class="text-uppercase">Name</th>
                                        <th class="text-uppercase">Email</th>
                                        <th class="text-uppercase">Course / Batch</th>
                                        <th class="text-uppercase">Last Payment</th>
                                        <th class="text-uppercase">Last Comment</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentCourses as $index => $course)
                                    <tr style="{{ $course->statusStyle }}">
                                        <td>{{ $course->student->csr->name }}</td>
                                        <td>{{ $course->student->name }}</td>
                                        <td>{{ $course->student->user->email }}</td>
                                        <td>{{ $course->course->name }} / {{ $course->batch->number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($course->getLatestPaymentDate())->diffForHumans() }}</td>
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
                                                                        @php
                                                                            $whatsapp = $course->student->whatsapp;
                                                                            if (substr($whatsapp, 0, 1) === '0') {
                                                                                $whatsapp = '+92' . substr($whatsapp, 1);
                                                                            }
                                                                        @endphp
                                                                        <div class="col-md-3">
                                                                            <small class="">Whatsapp: </small>
                                                                            <p><b><a href="https://wa.me/{{ $whatsapp }}" target="_blank">{{ $whatsapp }}</a></b></p>
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
                                                            @if($course->switch_from)
                                                            <div class="card">
                                                                <div class="header bg-primary">
                                                                    <h2 class="text-light text-center text-uppercase">Course History</h2>
                                                                </div>
                                                                <div class="body">
                                                                    <section>
                                                                        <div class="row">
                                                                            <div class="col-md-12 text-right mb-2">
                                                                                <button type="button" data-course-id="{{ $course->id }}" class="btn btn-warning add-returns">
                                                                                    <i class="fa fa- mr-2"></i> Add Returns
                                                                                </button>                                                                                    
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <dl class="param">
                                                                                    <dt>Previous Course:</dt>
                                                                                    <dd>{{ $course->switchCourse->name }}</dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <dl class="param">
                                                                                    <dt>Balance:</dt>
                                                                                    <dd>{{ $course->refund > 0 ? '+ ' .$course->refund : 0  }}</dd>
                                                                                </dl>
                                                                            </div>  
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                            @endif
                                        
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
                                                                                    <button type="button" class="btn btn-primary make-followup" data-course-id="{{ $course->id }}"><i class="fa fa-money mr-2"></i> Follow Up History</button>
                                                                                    {{-- <button type="button" class="btn btn-secondary add-comment" data-course-id="{{ $course->id }}"><i class="fa fa-comment mr-2"></i> Comment</button> --}}
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
                                                                                    <dd class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">{{  formatPrice($course->remainingfee() < 0 ? 0 : $course->remainingfee() ) }} </dd>
                                                                                </dl>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <dl class="param">
                                                                                    <dt>Total Paid By Student:</dt>
                                                                                    <dd>{{ formatPrice($course->totalPaidByStudent() )  }}</dd>
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
                                                                            <div class="col-md-6">
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
                                                                            <div class="col-md-6">
                                                                                <div class="card">
                                                                                    <div class="card-header">
                                                                                        <h5 class="text-center">Follow Up History</h5>
                                                                                    </div>
                                                                                    <div class="body">
                                                                                        @foreach($course->courseHistory as $history)
                                                                                            <div class="timeline-item green" date-is="{{ \Carbon\Carbon::parse($history->created_at)->format('d F, Y') }} - {{ $history->user->name }}">
                                                                                                <h6> {{ $history->comment }} </h6>
                                                                                            </div>
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

    </div>
</div>

<div class="modal fade box-shadow" id="refundModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="title text-white" id="largeModalLabel">Add Returns</h4>
            </div>
            <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body"> 
               <div class="row">
                <div class="col-md-12">
                    <label for="">Returns Amount</label>
                    <input type="number" required min="0" max="{{ $course->refund ?? '' }}" value="0" class="form-control" placeholder="Amount cannot be greater then balance" name="refund">
                </div>
                </div>
               </div>
               <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

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

@if(getUserType() == 'csr')
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
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
                                <input type="date" required class="form-control" name="payment_date_first">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Payment Details</label>
                                <div class="input-group mb-3">
                                  <select required class="form-select form-control" name="mode_first" id="inputGroupSelect02">
                                    <option value="">Select Payment Mode</option>
                                    @foreach($modes as $mode) 
                                        <option value="{{ $mode->id }}">{{ $mode->name }}</option> 
                                    @endforeach
                                  </select>
                                  <input required type="number" class="form-control" name="payment_first" min="0"  placeholder="Enter Received Payment " >
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
                <button type="submit" class="btn btn-primary" style="background: rgb(85, 85, 194) !important">PAY</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </div>
            
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="followupModal" tabindex="-1" role="dialog" style="box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="title text-white" id="largeModalLabel">Follow Up</h4>
            </div>
            <form id="followupForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body"> 
               <div class="row">
                <div class="col-md-12">
                    <textarea required name="comment" class="form-control" id="" placeholder="Follow up" cols="30" rows="03"></textarea>
                </div>
               </div>
               <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="background: rgb(85, 85, 194) !important">SUBMIT</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
            
            </form>
        </div>
    </div>
</div>

@endif

@endsection

<p style="display: none" id="authID">{{ Auth::user()->id }}</p>

@section('scripts')

<script>
$(document).ready(function() {
    $('.make-payment').click(function(){
        var courseId = $(this).data('course-id');

        var ppaymentFormAction = "{{ route('admin.student.make-payment') }}/" + courseId;
        $('#paymentModal #paymentForm').attr('action', ppaymentFormAction);
        $('#paymentModal').modal('show');
    });
    $('.make-followup').click(function(){
        var courseId = $(this).data('course-id');

        var followupFormAction = "{{ route('admin.student.followup') }}/" + courseId;
        $('#followupModal #followupForm').attr('action', followupFormAction);
        $('#followupModal').modal('show');
    });


});
</script>

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
                $('#lastComment_' + newcomment.student_course_id).html(newcomment.comments);

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