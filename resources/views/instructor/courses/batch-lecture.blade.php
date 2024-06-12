@extends('instructor.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{ $batch->course->name }} - Batch  {{ $batch->number }} Lectures</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">{{ $batch->course->name }} - Batch  {{ $batch->number }} Lectures</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-download"></i> Attendance report</button>
                            {{-- <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Send report</button> --}}
                        </div>
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="body project_report">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Status</th>
                                        <th>Title</th>
                                        <th>Zoom Link</th>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lectures as $lecture)
                                    @php
                                        $lectureDate = \Carbon\Carbon::parse($lecture->date_time);
                                        $isToday = $lectureDate->isToday();
                                        $isPastDate = $lectureDate->isBefore(\Carbon\Carbon::today());
                                        $attendanceExists = \App\Models\StudentLectureAttendance::where('lecture_id', $lecture->id)->exists();
                                    @endphp
                                    <tr @if($isToday) style="background-color: #f0f8ff;" @endif>
                                        <td>
                                            @if($attendanceExists)
                                                <span class="badge badge-success">Conducted</span>
                                            @elseif($isPastDate)
                                                <span class="badge badge-danger">Missed</span>
                                            @else
                                                <span class="badge badge-warning">Upcoming</span>
                                            @endif
                                        </td>
                                        <td class="project-title">
                                            <h6><a href="javascript:void(0);">{{ $lecture->title }}</a></h6>
                                        </td>
                                        <td>
                                            @if($lecture->zoom_link)
                                                <a href="{{ $lecture->zoom_link }}" target="_blank"><span class="btn btn-sm btn-primary">Zoom Link</span></a>
                                            @else
                                                <span class="btn btn-sm btn-danger">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $lectureDate->format('d F, y / h:i A') }}
                                        </td>
                                        <td class="project-actions">
                                            @if($attendanceExists)
                                                <a href="{{ route('instructor.student.attendance', $lecture->id) }}" 
                                                   class="btn btn-outline-secondary">
                                                    View Attendance <i class="fa fa-eye text-primary ml-2"></i>
                                                </a>
                                            @elseif($isPastDate)
                                                <span class="btn btn-outline-danger">Attendance Not Marked</span>
                                            @else
                                                <a href="{{ route('instructor.student.attendance', $lecture->id) }}" 
                                                   class="btn btn-outline-secondary @if(!$isToday) disabled @endif">
                                                    Mark Attendance <i class="fa fa-external-link text-warning ml-2"></i>
                                                </a>
                                            @endif
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
                <h4 class="title text-white" id="largeModalLabel">Attendance Report of {{ $batch->course->name }} - Batch  {{ $batch->number }} Lectures</h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="body project_report">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Students</th>
                                                @foreach ($lecture->batch->lectures->sortBy('date_time') as $batchLecture)
                                                    <th>{{ \Carbon\Carbon::parse($batchLecture->date_time)->format('d F, Y') }} </th>
                                                @endforeach
                                                <th>Total Present</th>
                                                <th>Total Lectures</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->student->name }}</td>
                                                @php
                                                    $totalPresent = 0;
                                                    $totalLectures = count($lecture->batch->lectures);
                                                @endphp
                                                @foreach ($lecture->batch->lectures->sortBy('date_time') as $batchLecture)
                                                <td>
                                                    @php
                                                        $attendance = $student->attendance()->where('lecture_id', $batchLecture->id)->first();
                                                        if ($attendance) {
                                                            if ($attendance->status == 1) {
                                                                $totalPresent++;
                                                                echo 'Present';
                                                            } elseif ($attendance->status == 2) {
                                                                echo 'Absent';
                                                            } elseif ($attendance->status == 3) {
                                                                echo 'Leave';
                                                            }
                                                        } else {
                                                            echo '-';
                                                        }
                                                    @endphp
                                                </td>
                                                @endforeach
                                                <td>{{ $totalPresent }}</td>
                                                <td>{{ $totalLectures }}</td>
                                                <td>{{ ($totalLectures > 0) ? round(($totalPresent / $totalLectures) * 100, 2) : 0 }}%</td>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
    
@endsection