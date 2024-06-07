@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Batch List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Batches</li>
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
                                        <th>Students</th>
                                        @foreach ($batch->lectures->sortBy('date_time') as $batchLecture)
                                        <th>{{ \Carbon\Carbon::parse($batchLecture->date_time)->format('d F, Y') }}</th>
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
                                            $totalLectures = count($batch->lectures);
                                        @endphp
                                        @foreach ($batch->lectures->sortBy('date_time') as $batchLecture)
                                        <td>
                                            @php
                                                $attendance = $student->attendance()->where('lecture_id', $batchLecture->id)->first();
                                                if ($attendance) {
                                                    $totalPresent++;
                                                    echo $attendance->attendanceStatus->name;
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
</div>

@endsection
