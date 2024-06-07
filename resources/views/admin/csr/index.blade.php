@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>CSR's List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumab-item active">CSR's</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create CSR </button>
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
                                        <th class="text-uppercase">Name</th>
                                        <th class="text-uppercase">Email</th>
                                        <th class="text-uppercase">Mobile</th>
                                        <th class="text-uppercase">Status</th>
                                        <th class="text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($csrs as $csr)
                                        <tr>
                                            <td> {{ $csr->name }} </td>
                                            <td> {{ $csr->email }} </td>
                                            <td> {{ $csr->mob }} </td>
                                            <td>
                                                @if($csr->status == 1)
                                                <span class="badge badge-success">Active</span>
                                                @else
                                                <span class="badge badge-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary edit-csr" data-csr-id="{{ $csr->id }}"><i class="fa fa-edit"></i></a>
                                                @if($csr->status == 1)
                                                <a href="#" class="btn btn-danger btn-sm" title="Block" onclick="confirmAction(event, 'block', '{{ $csr->id }}')">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                                @else
                                                <a href="#" class="btn btn-success btn-sm" title="Un-block" onclick="confirmAction(event, 'unblock', '{{ $csr->id }}')">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('admin.csr.dashboard', $csr->id) }}" class="btn btn-sm btn-warning" ><i class="fa fa-eye mr-2"></i>CSR Activity</a>                                            
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

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New CSR</h4>
            </div>
            <form action="{{ route('admin.csr.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input required type="text" name="name" class="form-control" placeholder="Full Name">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input required type="text" name="email" class="form-control" placeholder="Email">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input required type="password" name="password" class="form-control" placeholder="Enter Password">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Enter Phone</label>
                                <input type="number" name="mob" class="form-control" placeholder="Phone">
                            </div> 
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">SAVE USER</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editCSRModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New CSR</h4>
            </div>
            <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input required type="text" name="name" class="form-control" placeholder="Full Name">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input required type="text" name="email" class="form-control" placeholder="Email">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Password" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Enter Phone</label>
                                <input type="number" name="mob" class="form-control" placeholder="Phone">
                            </div> 
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">UPDATE CSR</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section('scripts')
    
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
        $('.edit-csr').click(function() {
            var csrId = $(this).data('csr-id');

            $.ajax({
                url: '{{ route("admin.edit.csr") }}', 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    csrId: csrId,
                },
                success: function(response) {
                    var user = response.user;

                    $('#editCSRModal input[name="name"]').val(user.name);
                    $('#editCSRModal input[name="email"]').val(user.email);
                    $('#editCSRModal input[name="mob"]').val(user.mob);

                    var editFormAction = "{{ route('admin.csr.update') }}/" + user.id;
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