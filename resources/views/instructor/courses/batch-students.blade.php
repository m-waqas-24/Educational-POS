@extends('instructor.layouts.app')
@section('content')
@php
    $lectureDate = \Carbon\Carbon::parse($lecture->date_time);
@endphp

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{ $lecture->batch->course->name }} - Batch  {{ $lecture->batch->number }} Students</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $lecture->batch->course->name }} - Batch  {{ $lecture->batch->number }} Students</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            {{-- <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Download report</button>
                            <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Send report</button> --}}
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body project_report">
                        <form id="attendanceForm" action="{{ route('instructor.store.student.attendance', $lecture->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="">Today Topics/Lesson</label>
                                    <textarea name="topic" id="topic"  class="form-control" cols="30" rows="3" placeholder="Enter Topic"> {{ $lecture->lectureAttendances->first()->topic }} </textarea>
                                </div>
                                <div class="col-md-12 bg-primary mb-4">
                                    <h4 class="text-center text-white">All Students</h4>
                                </div>
                                @php
                                    function intToRoman($num) {
                                        $map = [
                                            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90,
                                            'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
                                        ];
                                        $result = '';
                                        foreach ($map as $roman => $int) {
                                            while ($num >= $int) {
                                                $result .= $roman;
                                                $num -= $int;
                                            }
                                        }
                                        return $result;
                                    }
                                @endphp
                                @foreach ($students as $index => $student)
                                    @php
                                        $attendance = $student->attendance()->where('lecture_id', $lecture->id)->first();
                                    @endphp
                                    <div class="col-md-6">
                                        <h6 class="text-capitalize">
                                        <small>{{ intToRoman($index + 1) }}.</small> <strong> {{ $student->student->name }}</strong>
                                        </h6>
                                    </div>
        
                                    <div class="col-md-6">
                                        @foreach($attendanceStatuses as $status)
                                            <label class="fancy-radio">
                                                <input name="students[{{ $student->id }}]" value="{{ $status->id }}" type="radio" {{ $attendance && $attendance->status == $status->id ? 'checked' : '' }} @if(!$lectureDate->isToday()) disabled @endif>
                                                <span><i></i>{{ $status->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                @endforeach
                              </div>
                              
                            
                            @if ($lectureDate->isToday())
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                                </div>
                            @endif
                              
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    document.getElementById('attendanceForm').addEventListener('submit', function(event) {
        let allMarked = true;
        const students = document.querySelectorAll('[name^="students"]');
        
        students.forEach(function(student) {
            const name = student.getAttribute('name');
            const marked = document.querySelector(`[name="${name}"]:checked`);
            if (!marked) {
                allMarked = false;
                student.closest('.row').classList.add('border', 'border-danger');
            } else {
                student.closest('.row').classList.remove('border', 'border-danger');
            }
        });

        if (!allMarked) {
            event.preventDefault();
            alert('Please mark attendance for all students.');
        }
    });
</script>

@endsection
