@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Banks List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Banks</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Bank </button>
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
                                        <th>Name</th>
                                        <th>Account No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($banks as $bank)
                                    <tr>
                                        <td>{{ $bank->name }}</td>
                                        <td>{{ $bank->acc_no }}</td>
                                        <td>
                                            <button type="button" data-bank-id="{{ $bank->id }}" class="btn btn-sm  btn-warning edit-bank"><i class="fa fa-edit"></i></button>
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
                <h4 class="title text-white" id="largeModalLabel">Create New Bank</h4>
            </div>
            <form action="{{ route('admin.store.bank') }}" method="POST">
                @csrf
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <input type="text" required class="form-control" name="name" placeholder="Bank Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Account No.</label>
                            <input type="text" required class="form-control" name="acc_no" placeholder="Account No">
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


<div class="modal fade" id="editBankModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Bank</h4>
            </div>
            <form id="editBankForm" method="POST">
                @csrf
                @method('PUT')
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <input type="text" required class="form-control" name="name" placeholder="Bank Name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Account No.</label>
                            <input type="text" required class="form-control" name="acc_no" placeholder="Account No">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">UPDATE</button>
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
        $('.edit-bank').click(function() {
            var bankId = $(this).data('bank-id');

            $.ajax({
                url: '{{ route("admin.edit.bank") }}', 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    bankId: bankId,
                },
                success: function(response) {
                    var bank = response.bank;
                    
                    $('#editBankModal input[name="name"]').val(bank.name); 
                    $('#editBankModal input[name="acc_no"]').val(bank.acc_no);

                    var editBankFormAction = "{{ route('admin.update.bank') }}/" + bank.id;
                    $('#editBankForm').attr('action', editBankFormAction);

                    $('#editBankModal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

@endsection