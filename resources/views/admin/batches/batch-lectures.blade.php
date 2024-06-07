@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{ $batch->course->name }} - Batch {{ $batch->number }} Lectures</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $batch->course->name }} - Batch {{ $batch->number }} Lectures</li>
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
                                    <form class="form" action="{{ route('admin.store.batch-lecture', $batch->id) }}" method="POST">
                                      @csrf
                                        <div class="box-body">

                                            <div class="row" id="lectureContainer">
                                                @foreach($batch->lectures as $index => $lect)
                                                    <div class="col-md-4">
                                                        <div class="card position-relative">
                                                            <div class="card-body">
                                                                <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;"><strong>{{ $index + 1 }}</strong> </span>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Lecture Title</label>
                                                                            <input type="text" class="form-control" name="title[]" value="{{ $lect->title }}" placeholder="Lecture Title ">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Zoom Link</label>
                                                                            <input type="text" class="form-control" name="zoom_link[]" value="{{ $lect->zoom_link }}" placeholder="Zoom Link">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Lecture Date & Time</label>
                                                                            <input required type="datetime-local" class="form-control" name="date_time[]" value="{{ $lect->date_time }}" placeholder="Lecture Date & Time">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-danger btn-sm mt-2 deleteLectureBtn">X</button>
                                                                    </div>
                                                                    <input type="hidden" name="lecture_id[]" value="{{ $lect->id }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-success mb-3" id="addLectureBtn"><i class="fa fa-plus me-3"></i> Add Lecture</button>
                                          
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

@section('scripts')

<script>
    document.getElementById('addLectureBtn').addEventListener('click', function() {
        const lectureContainer = document.getElementById('lectureContainer');
        const newLectureCard = document.createElement('div');
        newLectureCard.classList.add('col-md-4');
        newLectureCard.innerHTML = `
            <div class="card position-relative">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Lecture Title</label>
                                <input required type="text" class="form-control" name="title[]" placeholder="Lecture Title ">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Zoom Link</label>
                                <input type="text" class="form-control" name="zoom_link[]" placeholder="Zoom Link">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Lecture Date & Time</label>
                                <input required type="datetime-local" class="form-control" name="date_time[]" placeholder="Lecture Date & Time">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger btn-sm mt-2 deleteLectureBtn">X</button>
                        </div>
                        <input type="hidden" name="lecture_id[]" value="">
                    </div>
                </div>
            </div>
        `;
        lectureContainer.appendChild(newLectureCard);
        addDeleteEvent(newLectureCard.querySelector('.deleteLectureBtn'));
    });

    function addDeleteEvent(deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this lecture?')) {
                deleteBtn.closest('.col-md-4').remove();
            }
        });
    }

    // Attach delete event to the initial delete button
    document.querySelectorAll('.deleteLectureBtn').forEach(addDeleteEvent);
</script>
    
@endsection
