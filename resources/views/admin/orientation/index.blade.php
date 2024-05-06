@extends('admin.layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('richtexteditor/rte_theme_default.css') }}" />
<script type="text/javascript" src="{{ asset('richtexteditor/rte.js') }}"></script>
<script>
    RTE_DefaultConfig.url_base = 'richtexteditor'
</script>
<script type="text/javascript" src="{{ asset('richtexteditor/plugins/all_plugins.js') }}"></script>

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Orientations List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Orientations</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Create Orientation </button>
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
                                        <th>Course</th>
                                        <th>Batch</th>
                                        <th>Trainer</th>
                                        <th>Venue</th>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($orientations as $orientation)
                                    <tr>
                                        <td>{{ $orientation->batch->course->name }}</td>
                                        <td>{{ $orientation->batch->number }}</td>
                                        <td>{{ $orientation->trainer }}</td>
                                        <td>{{ $orientation->venue }}</td>
                                        <td>{{ \Carbon\Carbon::parse($orientation->dateTime)->format('d F Y / h:i:A') }}</td>
                                        <td>
                                            <button type="button" data-orientation-id="{{ $orientation->id }}" class="btn btn-sm  btn-warning edit-orientation"><i class="fa fa-edit"></i></button>
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
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Orientation</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
            </div>
            <form action="{{ route('admin.store.orientation') }}" method="POST">
                @csrf
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Orientaion Batch</label>
                            <select required class="form-select form-control" name="batch_id">
                                <option value="">Select Batch</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->number }} ({{ $batch->course->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Trainer Name</label>
                            <input type="text" required class="form-control" name="trainer" placeholder="Trainer Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Venue</label>
                            <input type="text" required class="form-control" name="venue" placeholder=" Venue">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" required class="form-control" name="dateTime" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">DESCRIPTION </label>
                            <textarea  name="desc" id="div_editorB">
                               
                            </textarea>
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


<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title text-white" id="largeModalLabel">Create New Orientation</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
            </div>
            <form id="editOriForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Orientation Batch</label>
                                <select required class="form-select form-control" name="batch_id">
                                    <option value="">Select Batch</option>
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->number }} ({{ $batch->course->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Trainer Name</label>
                                <input type="text" required class="form-control" name="trainer" placeholder="Trainer Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Venue</label>
                                <input type="text" required class="form-control" name="venue" placeholder="Venue">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Date & Time</label>
                                <input type="datetime-local" required class="form-control" name="dateTime" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" id="descriptionContainer">
                                <label class="form-label">DESCRIPTION </label>
                                <!-- Textarea will be dynamically appended here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
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
        $('.edit-orientation').click(function() {
            var oriId = $(this).data('orientation-id');

            $.ajax({
                url: '{{ route("admin.edit.orientation") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    oriId: oriId,
                },
                success: function(response) {
                    var orientation = response.orientation;

                    $('#editModal input[name="trainer"]').val(orientation.trainer);
                    $('#editModal input[name="venue"]').val(orientation.venue);
                    $('#editModal input[name="dateTime"]').val(orientation.dateTime);
                    $('#editModal select[name="batch_id"]').val(orientation.batch_id);

                    // Create textarea with orientation description
                    var $descriptionContainer = $('#descriptionContainer');
                    $descriptionContainer.empty(); // Clear previous content
                    var $textarea = $('<textarea></textarea>');
                    $textarea.attr({
                        'name': 'desc',
                        'id': 'div_editorA',
                        'class': 'form-control',
                        'required': 'required'
                    });
                    $textarea.val(orientation.desc);
                    $descriptionContainer.append($textarea);

                    // Initialize Rich Text Editor
                    var editor1 = new RichTextEditor("#div_editorA");

                    // Update form action
                    var editOriFormAction = "{{ route('admin.update.orientation') }}/" + orientation.id;
                    $('#editOriForm').attr('action', editOriFormAction);

                    $('#editModal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

<script src="{{ asset('res/patch.js') }}"></script>

@endsection
