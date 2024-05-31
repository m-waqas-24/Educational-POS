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
        
        <div class="row clearfix">

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
                                        <th class="text-uppercase">Last Comment</th>
                                        <th class="text-uppercase">Status</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentCourses as $index => $course)
                                    <tr style="{{ $course->statusStyle }}">
                                        <td>{{ $course->student->name }}</td>
                                        <td>{{ $course->student->user->email }}</td>
                                        <td>{{ \Carbon\Carbon::parse($course->getLatestPaymentDate())->diffForHumans() }}</td>
                                        <td id="lastComment_{{$course->id}}" style="max-width: 200px; word-wrap: break-word;">
                                            @if($course->comments)
                                                <?php
                                                $lastComment = $course->comments->last();
                                                $comment = $lastComment ? $lastComment->comment : null;

                                                // $comment = $course->comments->last()->comments;
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
                                            @if($course->status_id == 1)
                                                <span class="badge badge-primary">Paid</span>
                                            @elseif($course->status_id == 2)
                                                <span class="badge badge-danger {{ $course->statusStyle ? 'text-white' : '' }}">Partial</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary" onclick="openModal('{{ $course->id }}')">
                                                <i class="fa fa-eye"></i> 
                                            </a>
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
                                                                                    <dd> {{ $course->student->card  }} </dd>
                                                                                </dl>
                                                                            </div>
                                                                            
                                                                            <div class="col-md-3">
                                                                                <dl class="param">
                                                                                    <dt class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">Remaining Amount: </dt>
                                                                                    <dd class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">{{  formatPrice($course->remainingfee())   }} </dd>
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
                                                                                                    @if($payment->is_confirmed_first == 0 && (auth()->user()->type == 'admin' || getUserType() == 'superadmin'))
                                                                                                        <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'first', '{{ $payment->id }}')">
                                                                                                            <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                        </a>
                                                                                                        @if(!$payment->is_edit_first)
                                                                                                        <a href="#" data-payment-id="{{ $payment->id }}" data-payment-type="first" class="btn btn-warning btn-sm m-1 edit-payment" title="Edit Payment">
                                                                                                            <i class="fa fa-edit mr-2"></i> Edit Payment
                                                                                                        </a>
                                                                                                        @endif
                                                                                                        
                                                                                                        
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
                                                                                                    <span class="text-primary fw-bold">Edited</span>
                                                                                                @endif
                                                                                            </div>
                                
                                                                                            <div>
                                                                                                @if($payment->is_confirmed_second == 0 && auth()->user()->type == 'admin')
                                                                                                    <a href="#" class="btn btn-danger btn-sm m-1" title="Confirmed" onclick="confirmAction(event, 'approved', 'second', '{{ $payment->id }}')">
                                                                                                        <i class="fa fa-check mr-2"></i> Confirmed Payment
                                                                                                    </a>
                                                                                                    @if(!$payment->is_edit_second)
                                                                                                        <a href="#" data-payment-id="{{ $payment->id }}" data-payment-type="second" class="btn btn-warning btn-sm m-1 edit-payment" title="Edit Payment">
                                                                                                            <i class="fa fa-edit mr-2"></i> Edit Payment
                                                                                                        </a>
                                                                                                    @endif
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
    </div>
</div>


@if(getUserType() == 'admin')

<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">EDIT PAYMENT</h4>
            </div>
            <form id="editStudentPaymentForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body"> 
                    <div class="row first-payment">
                        <input type="hidden" name="paymentType" >
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Payment Details (First)</label>
                                    <div class="input-group mb-3">
                                        <select class="form-select form-control" name="mode_first" id="inputGroupSelect02">
                                            <option value="">Select Payment Mode</option>
                                            @foreach($modes as $mode) 
                                                <option value="{{ $mode->id }}">{{ $mode->name }}</option> 
                                            @endforeach
                                        </select>
                                        <input type="number" class="form-control" name="payment_first" placeholder="Enter Payment">
                                        <input type="file" name="payment_first_receipt" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row second-payment">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Payment Details (Second)</label>
                                    <div class="input-group mb-3">
                                        <select class="form-select form-control" name="mode_second" id="inputGroupSelect02">
                                            <option value="">Select Payment Mode</option>
                                            @foreach($modes as $mode) 
                                                <option value="{{ $mode->id }}">{{ $mode->name }}</option>
                                            @endforeach
                                        </select>
                                        <input  type="number" class="form-control" name="payment_second" placeholder="Enter Payment (Optional)">
                                        <input type="file" name="payment_second_receipt" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">UPDATE PAYMENT</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
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
                                <input type="date" class="form-control" name="payment_date_first">
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
                                  <input required type="number" class="form-control" name="payment_first" value="0" placeholder="Enter Payment" >
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

<script>
$(document).ready(function() {
    $('.make-payment').click(function(){
        var courseId = $(this).data('course-id');

        var paymentFormAction = "{{ route('admin.student.make-payment') }}/" + courseId;
        $('#paymentForm').attr('action', paymentFormAction);
        $('#paymentModal').modal('show');
    })

    $('.add-comment').click(function(){
        var courseId = $(this).data('course-id');
        $('#courseID').val(courseId);

        $('#commentModal').modal('show');
    })
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

                $('#commentModal').modal('hide');  
                $('.studentComment').text(response.comment);  
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

});
</script>

<script>
    // Check if modal should be shown
    $(document).ready(function () {
        let shouldShowModal = sessionStorage.getItem('showModal');
        if (shouldShowModal === 'true') {
            let modalCourseIds = JSON.parse(sessionStorage.getItem('modalCourseIds'));
            let modalIndex = parseInt(sessionStorage.getItem('modalIndex'));
            if (modalCourseIds && !isNaN(modalIndex) && modalIndex >= 0 && modalIndex < modalCourseIds.length) {
                openModal(modalCourseIds, modalIndex);
            }
            sessionStorage.removeItem('showModal');
            sessionStorage.removeItem('modalCourseIds');
            sessionStorage.removeItem('modalIndex');
        }
    });

    // Open Modal Function
    function openModal(courseId) {
        $('#largeModal_' + courseId).modal('show');
        sessionStorage.setItem('lastOpenedModal', courseId); // Save last opened modal ID
    }

    // Confirm Action Function
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

    // Send Request Function
    function sendRequest(action, confirmationType, stuId) {
        if (action == 'approved') {
            let courseId = sessionStorage.getItem('lastOpenedModal');
            if (courseId) {
                // Check if sessionStorage already has modalCourseIds array
                let modalCourseIds = JSON.parse(sessionStorage.getItem('modalCourseIds'));
                let modalIndex = 0;
                
                if (!modalCourseIds) {
                    modalCourseIds = [];
                } else {
                    modalIndex = modalCourseIds.length;
                }

                modalCourseIds.push(courseId);
                
                // Set sessionStorage to show modal after redirect
                sessionStorage.setItem('showModal', 'true');
                sessionStorage.setItem('modalCourseIds', JSON.stringify(modalCourseIds));
                sessionStorage.setItem('modalIndex', modalIndex);
                
                window.location.href = "{{ route('admin.confirm.student') }}" + '/' + stuId + '/' + confirmationType;
            } else {
                // Handle the case where $course->id does not exist
                console.error("Course ID does not exist.");
                // You can show an error message or perform some other action here
            }
        }
    }

    // After the page has reloaded, check for the last opened modal and show it
    $(document).ready(function () {
        let lastOpenedModal = sessionStorage.getItem('lastOpenedModal');
        if (lastOpenedModal) {
            $('#largeModal_' + lastOpenedModal).modal('show');
        }
    });
</script>


<script>
    $(document).ready(function() {
        $('.edit-payment').click(function(e) {
            e.preventDefault();
            var paymentId = $(this).data('payment-id');
            var paymentType = $(this).data('payment-type');
            
            // Send AJAX request
            $.ajax({
                url: "{{ route('admin.edit.student.payment') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    paymentId: paymentId,
                    paymentType: paymentType
                },
                success: function(response) {
                    console.log(response);
                    let payment = response.payment;
                    $('#editPaymentModal input[name="paymentType"]').val(paymentType); 
                    
                    // Set payment details based on paymentType
                    if (paymentType =='first') {
                        $('#editPaymentModal select[name="mode_first"]').val(payment.mode_first); 
                        $('#editPaymentModal input[name="payment_first"]').val(payment.payment_first); 
                        
                        // Hide second payment inputs
                        $('#editPaymentModal .second-payment').hide();
                    } else if (paymentType == 'second') {
                        $('#editPaymentModal select[name="mode_second"]').val(payment.mode_second); 
                        $('#editPaymentModal input[name="payment_second"]').val(payment.payment_second); 
                        
                        // Hide first payment inputs
                        $('#editPaymentModal .first-payment').hide();
                    }

                    var editPaymentFormAction = "{{ route('admin.update.student.payment') }}/" + payment.id;
                    $('#editStudentPaymentForm').attr('action', editPaymentFormAction);
                    
                    $('#editPaymentModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


@endsection

