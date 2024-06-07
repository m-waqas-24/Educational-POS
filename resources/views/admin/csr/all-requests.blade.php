@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Team Request List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumab-item active">Team Request</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Team Request </button>
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
                                        <th class="text-uppercase">CSR Name</th>
                                        <th class="text-uppercase">Subject</th>
                                        <th class="text-uppercase">Message</th>
                                        <th class="text-uppercase">Status</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td> {{ $request->user->name }} </td>
                                            <td> {{ $request->subject }} </td>
                                            <td> {{ Str::limit($request->msg, 40) }} </td>
                                            <td>
                                                @if($request->status_id == 1)
                                                <span class="badge badge-warning">Pending</span>
                                                @elseif($request->status_id == 2)
                                                <span class="badge badge-secondary">Approved</span>
                                                @elseif($request->status_id == 3)
                                                <span class="badge badge-danger">Cancelled</span>
                                                @elseif($request->status_id == 4)
                                                <span class="badge badge-success">Completed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->created_at->diffInHours(now()) < 1)
                                                <a href="#" class="btn btn-sm btn-primary edit-request" data-request-id="{{ $request->id }}"><i class="fa fa-edit"></i>  </a>
                                                @endif
                                                {{-- <a href="#" class="btn btn-sm btn-primary edit-request" data-request-id="{{ $request->id }}"><i class="fa fa-edit"></i></a> --}}
                                                @if(Auth::user()->type == 'admin' || Auth::user()->type == 'superadmin')
                                                    <a href="#" class="btn btn-warning btn-sm updateStatus" data-request-id="{{ $request->id }}">
                                                        <i class="fa fa-list me-2"></i> Update Status
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="ViewModal" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h4 class="title text-white" id="largeModalLabel">Update Request Status</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h4>Subject</h4>
                                                                <p> <strong>{{ $request->subject }} </strong> </p>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <h4>Message</h4>
                                                                <p> {{ $request->msg}}  </p>
                                                            </div>
                                                        </div>
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

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Request</h4>
            </div>
            <form action="{{ route('admin.store.request') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Subject</label>
                                <input required type="text" name="subject" class="form-control" placeholder="Enter Subject">
                            </div> 
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Message</label>
                                <textarea required name="message" class="form-control" placeholder="Message"></textarea>
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


<div class="modal fade" id="StatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Update Request Status</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                           <a class="btn btn-warning" href="{{ route('admin.update.status.request', ['status_id' => 1, 'request_id' => 'REQUEST_ID' ]) }}">Pending</a>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                           <a class="btn btn-primary" href="{{ route('admin.update.status.request', ['status_id' => 2, 'request_id' => 'REQUEST_ID' ]) }}">Approved</a>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                           <a class="btn btn-danger" href="{{ route('admin.update.status.request', ['status_id' => 3, 'request_id' => 'REQUEST_ID' ]) }}">Cancelled</a>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                           <a class="btn btn-success" href="{{ route('admin.update.status.request', ['status_id' => 4, 'request_id' => 'REQUEST_ID' ]) }}">Completed</a>
                        </div> 
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="requestId">Request ID:</label>
                            <span class="request-id"></span>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editCSRModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Edit Team Request</h4>
            </div>
            <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Subject</label>
                            <input required type="text" name="subject" class="form-control" placeholder="Enter Subject">
                        </div> 
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label>Message</label>
                            <textarea required name="message" class="form-control" placeholder="Message"></textarea>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">UPDATE REQUEST</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section('scripts')

<script>
    $(document).ready(function() {
        $('.updateStatus').on('click', function() {
            var requestId = $(this).data('request-id');
            $('#StatusModal .modal-body .request-id').text(requestId);
            $('#StatusModal .modal-body .btn').each(function() {
            var href = $(this).attr('href').replace('REQUEST_ID', requestId);
            $(this).attr('href', href);
        });
            $('#StatusModal').modal("show");
        });
    });
    </script>

<script>


function confirmAction(event, action, userId) {
        event.preventDefault();
        console.log(action,userId);

            Swal.fire({
                title: "Are you sure?",
                text: (action === 'block') ? "You are going to block user!" : "You are going to Active user!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with the action
                    sendRequest(action, userId);
                } else {
                    Swal.fire("Cancelled", "Your action was cancelled", "info");
                }
            });
        }

        function sendRequest(action, userId) {
            if (action === 'block') {
                window.location.href = "{{ route('admin.user.block') }}" + '/' + userId;
            } else {
                window.location.href = "{{ route('admin.user.unblock') }}" + '/' + userId;
            }
        }

        $(document).ready(function() {
            $('table tbody .edit-request').click(function() {
                var requestId = $(this).data('request-id');

                $.ajax({
                    url: '{{ route("admin.edit.request") }}', 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        requestId: requestId,
                    },
                    success: function(response) {
                        var request = response.request;

                        $('#editCSRModal input[name="subject"]').val(request.subject);
                        $('#editCSRModal textarea[name="message"]').val(request.msg);

                        var editFormAction = "{{ route('admin.update.request') }}/" + request.id;
                        $('#editForm').attr('action', editFormAction);

                        $('#editCSRModal').modal('show');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
</script>

@endsection

