@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> Discontinued Students List</h2>
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
                                        <td>
                                            @if($course->status_id == 1)
                                                <span class="badge badge-primary">Paid</span>
                                            @elseif($course->status_id == 2)
                                                <span class="badge badge-danger {{ $course->statusStyle ? 'text-white' : '' }}">Partial</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#largeModal_{{ $course->id }}"><i class="fa fa-eye"></i> </a>
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
                                                                            <p><b> {{ \Carbon\Carbon::parse($course->coursePayments()->min('created_at'))->format('d F, Y') }} </b></p>
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
                                                                                    <dd class="@if($course->status_id == 1) text-success @elseif($course->status_id == 2) text-danger @endif">{{ formatPrice($course->remainingfee())  }} </dd>
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

    </div>
</div>



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


@endsection

@section('scripts')

<script>
$(document).ready(function() {
    $('.make-payment').click(function(){
        var courseId = $(this).data('course-id');

        var paymentFormAction = "{{ route('admin.student.make-payment') }}/" + courseId;
        $('#paymentForm').attr('action', paymentFormAction);
        $('#paymentModal').modal('show');
    })
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

@endsection