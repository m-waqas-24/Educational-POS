@extends('admin.layouts.app')
@section('content')

<style>
    .wizard .content{
        min-height: 20px !important;
    }
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Dashboard</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        {{-- <div class="page_action">
                            <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Download report</button>
                            <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Send report</button>
                        </div> --}}
                        <div class="p-2 d-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h2> Monthly Data Distribution & Enrollment Chart
                        </h2>
                    </div>
                    <div class="body">
                        <div id="chart-combination" style="height: 16rem"></div>
                    </div>
                </div>                
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>CSR TODAY ACTIVITY
                        </h2>
                    </div>
                    <div class="body">
                        <div id="wizard_vertical">
                            @foreach($csrs as $csr)
                            @if(Auth::user()->type == 'csr' && Auth::user()->id != $csr->id)
                                @continue
                            @endif
                                <h2 class="text-white" style="color: #fff !important;">{{ $csr->name }}</h2>
                                <section>
                                    <div class="row clearfix row-deck">
                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <a href="{{ route('admin.filter.action.status.today',  ['id' => null, 'csr' => $csr->id]) }}">
                                                    <div class="body">
                                                        <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                        <div class="content">
                                                            <div class="text mb-2 text-uppercase">Today Calls</div>
                                                            <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->whereDate('called_at', \Carbon\Carbon::today()->toDateString())->count() }}</h4>
                                                            <small class="text-muted">Analytics for Today</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        @foreach ($actionStatus as $status)
                                            <div class="col-md-4">
                                                <div class="card top_widget">
                                                    <a href="{{ route('admin.filter.action.status.today',  ['id' => $status->id, 'csr' => $csr->id]) }}">
                                                        <div class="body">
                                                            <div class="icon"><i class="{{ $status->icon }}"></i></div>
                                                            <div class="content">
                                                                <div class="text mb-2 text-uppercase">{{ $status->name }}</div>
                                                                <h4 class="number mb-0">{{ $status->CsrStudent()->where('csr_id', $csr->id)->whereDate('called_at', \Carbon\Carbon::today())->count() }}</h4>
                                                                <small class="text-muted">Analytics for Today</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <div class="body">
                                                    <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                    <div class="content">
                                                        <div class="text mb-2 text-uppercase">Due Data CSR</div>
                                                        <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where(['csr_id' => $csr->id, 'action_status_id' => 0 ])->count() }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <div class="body">
                                                    <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                    <div class="content">
                                                        <div class="text mb-2 text-uppercase">Total CSR Data</div>
                                                        <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->count() }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Performance Chart for {{ $csr->name }}</h2>
                                                </div>
                                                <div class="body">
                                                    @if (isset($csrData[$csr->name]))
                                                        @foreach($csrData[$csr->name] as $year => $months)
                                                            <div id="chart-combinationcsr-{{ $csr->name }}-{{ $year }}" style="height: 16rem"></div>
                                                            <p>Performance chart for {{ $csr->name }} in {{ $year }}</p>
                                                        @endforeach
                                                    @else
                                                        <p>No data available for {{ $csr->name }}.</p>
                                                    @endif
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </div>                
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h2>COURSES REPORTS BY BATCH
                        </h2>
                    </div>
                    <div class="body">
                        <div id="wizard_vertical2">
                            @foreach($courses as $course)
                                <h2 class="text-white" style="color: #fff !important;">{{ $course->name }}</h2>
                                <section>
                                    <div class="row clearfix row-deck">
                                        <ul class="nav nav-tabs-new2">
                                         
                                            @foreach($course->batches as $batch)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $loop->first ? 'active show' : '' }}" data-toggle="tab" href="#batch{{ $batch->id }}">Batch {{ $batch->number }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach($course->batches as $batch)
                                                @php
                                                    $studentCourses = \App\Models\StudentCourse::where(['batch_id' => $batch->id, 'is_continued' => 1])->get();
                                                    $discontinuedStudentCourses = \App\Models\StudentCourse::where(['batch_id' => $batch->id, 'is_continued' => 0])->get();
                                                    //total fees of students
                                                    $totalFees = $studentCourses->sum('fee');
                                                    $totalRecovered = sumConfirmedPayments($studentCourses);
                                                    //csr student count
                                                    $csrStudentCounts = $studentCourses->groupBy('student.csr.name')
                                                    ->map->count()
                                                    ->toArray();
                                                    // dd($csrStudentCounts);
                                                    $workshops = getWorkshopsByBatchId($batch->id);
                                                @endphp
                                                <div class="tab-pane {{ $loop->first ? 'active show' : '' }}" id="batch{{ $batch->id }}">

                                                    <div class="row clearfix">

                                                        <div class="col-lg-12">
                                                            <div class="card">
                                                                <div class="body">
                                                                    <ul class="nav nav-tabs">
                                                                            <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#Profile-withicon"><i class="fa fa-dashboard mr-2"></i> Batch Report</a></li>
                                                                    </ul>
                                                                    <div class="tab-content" style="padding:0px; padding-top: 15px">
                                                                      
                                            
                                                                        <div class="tab-pane active show" id="Profile-withicon">
                                                                            <div class="row clearfix" >
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <a href="{{ route('admin.students.by.status', ['status' => null, 'batch_id' => $batch->id]) }}">
                                                                                        <div class="card top_counter">
                                                                                            <div class="body">
                                                                                                <div class="icon text-info"><i class="fa fa-users"></i> </div>
                                                                                                <div class="content">
                                                                                                    <div class="text">Total Students</div>
                                                                                                    <h5 class="number">{{ formatPrice($studentCourses->count()) }}</h5>
                                                                                                </div>
                                                                                            </div>                        
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <a href="{{ route('admin.students.by.status', ['status' => 1, 'batch_id' => $batch->id]) }}">
                                                                                        <div class="card top_counter">
                                                                                            <div class="body">
                                                                                                <div class="icon text-warning"><i class="fa fa-user"></i> </div>
                                                                                                <div class="content">
                                                                                                    <div class="text">Paid Students</div>
                                                                                                    <h5 class="number">{{ formatPrice($studentCourses->where('status_id',1)->count()) }}</h5>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <a href="{{ route('admin.students.by.status', ['status' => 2, 'batch_id' => $batch->id]) }}">
                                                                                        <div class="card top_counter">
                                                                                            <div class="body">
                                                                                                <div class="icon text-danger"><i class="fa fa-user"></i> </div>
                                                                                                <div class="content">
                                                                                                    <div class="text">Partial Students</div>
                                                                                                    <h5 class="number">{{ formatPrice($studentCourses->where('status_id',2)->count()) }}</h5>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    {{-- <a href="{{ route('admin.students.by.status', ['status' => 2, 'batch_id' => $batch->id]) }}"> --}}
                                                                                        <div class="card top_counter" data-toggle="modal" data-target="#WorkshopModal_{{$batch->id }}">
                                                                                            <div class="body">
                                                                                                <div class="icon text-info"><i class="fa fa-desktop"></i> </div>
                                                                                                <div class="content">
                                                                                                    <div class="text">Workshops</div>
                                                                                                    <h5 class="number">{{ count($workshops) }}</h5>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    {{-- </a> --}}
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <div class="card top_counter">
                                                                                        <div class="body">
                                                                                            <div class="icon text-danger"><i class="fa fa-bars"></i> </div>
                                                                                            <div class="content">
                                                                                                <div class="text">Orientation</div>
                                                                                                <h5 class="number"></h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <a href="{{ route('admin.students.by.status', ['status' => 3, 'batch_id' => $batch->id]) }}">
                                                                                        <div class="card top_counter">
                                                                                            <div class="body">
                                                                                                <div class="icon text-danger"><i class="fa fa-ban"></i> </div>
                                                                                                <div class="content">
                                                                                                    <div class="text">Left Student</div>
                                                                                                    <h5 class="number"> {{ $discontinuedStudentCourses->count() }} </h5>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <div class="card top_counter">
                                                                                        <div class="body">
                                                                                            <div class="icon text-danger"><i class="fa fa-calendar"></i> </div>
                                                                                            <div class="content">
                                                                                                <div class="text">Batch Start Date</div>
                                                                                                <h5 class="number"> {{ $batch->starting_date }} </h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-6">
                                                                                    <div class="card top_counter">
                                                                                        <div class="body">
                                                                                            <div class="icon text-danger"><i class="fa fa-close"></i> </div>
                                                                                            <div class="content">
                                                                                                <div class="text">Batch Ending Date</div>
                                                                                                <h5 class="number"> {{ $batch->ending_date }} </h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                {{-- <div class="col-md-12">
                                                                                    <div class="card">
                                                                                        <div class="header">
                                                                                            <h2>Students Enroll By Csr </h2>
                                                                                        </div>
                                                                                        <div class="body">
                                                                                            <div id="Google-Analytics-Dashboard" style="height: 230px"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> --}}
                                                                                @if(getUserType() == 'superadmin')
                                                                                <div class="col-lg-6 col-md-12">
                                                                                    <div class="card">
                                                                                        <div class="header">
                                                                                            <h2>Fees Report</h2>
                                                                                        </div>
                                                                                        <div class="body">
                                                                                            <ul class="list-unstyled">
                                                                                                @php
                                                                                                    if ($totalFees > 0) {
                                                                                                        $percentageRecovered = ($totalRecovered / $totalFees) * 100;
                                                                                                    } else {
                                                                                                        // Handle the case where totalFees is 0 to avoid division by zero
                                                                                                        $percentageRecovered = 0;
                                                                                                    }
                                                                                                    // Calculate the remaining percentage
                                                                                                    $totalReamining = $totalFees - $totalRecovered;
                                                                                                    $percentageRemaining = 100 - $percentageRecovered;
                                                                                                @endphp 
                                                                                                <li>
                                                                                                    <h6 class="d-flex justify-content-between align-items-center">
                                                                                                        <span>{{ formatPrice($totalFees) }}</span>
                                                                                                        <span class="text-muted font-14">Batch Total Fees</span>
                                                                                                    </h6>
                                                                                                    <div class="progress progress-xs progress-transparent custom-color-blue">
                                                                                                        <div class="progress-bar" data-transitiongoal="100"></div>
                                                                                                    </div>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <h6 class="d-flex justify-content-between align-items-center">
                                                                                                        <span>{{ formatPrice($totalRecovered) }}</span>
                                                                                                        <span class="text-muted font-14">Total Received</span>
                                                                                                    </h6>
                                                                                                    <div class="progress progress-xs progress-transparent custom-color-purple">
                                                                                                        <div class="progress-bar" data-transitiongoal="{{ $percentageRecovered }}"></div>
                                                                                                    </div>
                                                                                                </li>                                   
                                                                                                <li>
                                                                                                    <h6 class="d-flex justify-content-between align-items-center">
                                                                                                        <span>{{ formatPrice($totalReamining) }}</span>
                                                                                                        <span class="text-muted font-14">Total Remaining</span>
                                                                                                    </h6>
                                                                                                    <div class="progress progress-xs progress-transparent custom-color-yellow">
                                                                                                        <div class="progress-bar" data-transitiongoal="{{ $percentageRemaining }}"></div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                           
                                        </div>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                    
                </div>                
            </div>

        </div>            
    </div>
</div>


    @foreach($courses as $course)
        @foreach($course->batches as $batch)
            @php
                $workshops = getWorkshopsByBatchId($batch->id);
            @endphp                                            
                <div class="modal fade" id="WorkshopModal_{{ $batch->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="title" id="largeModalLabel">Workshops Details</h4>
                            </div>
                            <div class="modal-body">
                                @if(!empty($workshops))
                                    <div class="card">
                                        <div class="header">
                                            <h2>Student Workshops</h2>
                                        </div>
                                        <div class="body">
                                            <div class="wizard_vertical3" id="">
                                                @foreach ($workshops as  $index => $work)
                                                    <h2>{{ $work->title }}</h2>
                                                    <section>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <dl class="param">
                                                                    <dt>Title: </dt>
                                                                    <dd>{{ $work->title }}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <dl class="param">
                                                                    <dt>Trainer: </dt>
                                                                    <dd>{{ $work->trainer }}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <dl class="param">
                                                                    <dt>Workshop Date & Time: </dt>
                                                                    <dd>{{ \Carbon\Carbon::parse($work->datetime)->format('d F, Y') }}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <dl class="param">
                                                                    <dt>Venue: </dt>
                                                                    <dd>{{ $work->venue }}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-primary">
                                                                        <h5 class="text-white text-center">Registered Students</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered  table-hover ">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-uppercase">Student Name</th>
                                                                                        <th class="text-uppercase">Email</th>
                                                                                        <th class="text-uppercase">Phone</th>
                                                                                        <th class="text-uppercase">City</th>
                                                                                        <th class="text-uppercase">CNIC</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody >
                                                                                    @foreach ($work->workshop_students as $index => $stu)
                                                                                    <tr>
                                                                                        <td>{{ $stu->name }}</td>
                                                                                        <td>{{ $stu->email }}</td>
                                                                                        <td>{{ $stu->number }}</td>
                                                                                        <td>{{ $stu->city }}</td>
                                                                                        <td>{{ $stu->cnic }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>
                                                        </div>
                                                    </section>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <p class="text-danger">There is no Workshops created!</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    @endforeach



@endsection

@section('scripts')

<script>

document.addEventListener("DOMContentLoaded", function() {
    // Get the data arrays from the server
    var dataDistributedCountsByMonth = @json(array_values($dataDistributedCountsByMonth));
    var dueDataCountsByMonth = @json(array_values($dueDataCountsByMonth));
    var enrollStudentCountsByMonth = @json(array_values($enrollStudentCountsByMonth));

    var chart = c3.generate({
        bindto: '#chart-combination', // id of chart wrapper
        data: {
            columns: [
                // Add the Total Data dynamically
                ['Total Data'].concat(dataDistributedCountsByMonth),
                // Add Enroll Student Data dynamically
                ['Enrollments'].concat(enrollStudentCountsByMonth),
                // Add the Due Data dynamically
                ['Due Data'].concat(dueDataCountsByMonth)
            ],
            type: 'bar', // default type of chart
            types: {
                'Enrollments': 'spline',
            },
            groups: [
                ['Total Data', 'Due Data'] // Grouping bars together
            ],
            colors: {
                'Total Data': '#1f77b4',  // Color for Total Data
                'Enrollments': '#2ca02c', // Color for Enrollments (data3)
                'Due Data': '#d62728'     // Color for Due Data
            },
            names: {
                // name of each series
                'Total Data': 'Total Data',
                'Enrollments': 'Enrollments', // Name for data3
                'Due Data': 'Due Data'        // Name for Due Data
            }
        },
        axis: {
            x: {
                type: 'category',
                // name of each category
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }
        },
        bar: {
            width: 16
        },
        legend: {
            show: true // show legend
        },
        padding: {
            bottom: 0,
            top: 0
        }
    });
});

</script>

<script>
var csrData = @json($csrData);
var csrEnrollData = @json($csrEnrollData);
var csrPartialEnrollData = @json($csrPartialEnrollData); // Add this line to fetch partial enrollment data

$(document).ready(function() {
    for (const [csrName, years] of Object.entries(csrData)) {
        for (const [year, months] of Object.entries(years)) {
            var totalData = ['Total Data'];
            var uncalledData = ['Uncalled'];
            var enrollData = ['Enroll'];
            var partialEnrollData = ['Partial Enroll']; // Add this line
            var categories = [];

            for (let month = 1; month <= 12; month++) {
                totalData.push(months['total'][month] || 0);
                uncalledData.push(months['uncalled'][month] || 0);
                enrollData.push((csrEnrollData[csrName] && csrEnrollData[csrName][year] && csrEnrollData[csrName][year][month]) || 0);
                partialEnrollData.push((csrPartialEnrollData[csrName] && csrPartialEnrollData[csrName][year] && csrPartialEnrollData[csrName][year][month]) || 0); // Add this line
                categories.push(new Date(2000, month - 1).toLocaleString('default', { month: 'long' }));
            }

            var chart = c3.generate({
                bindto: '#chart-combinationcsr-' + csrName + '-' + year,
                data: {
                    columns: [totalData, uncalledData, enrollData, partialEnrollData], // Include partialEnrollData
                    types: {
                        ['Enroll']: 'line',
                        ['Total Data']: 'bar',
                        ['Uncalled']: 'bar',
                        ['Partial Enroll']: 'line' // Set partial enrollment data as a line chart
                    },
                    colors: {
                        ['Partial Enroll']: 'red' // Set color for partial enrollment line
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        categories: categories
                    }
                },
                bar: {
                    width: 16
                },
                legend: {
                    show: true
                },
                padding: {
                    bottom: 0,
                    top: 0
                }
            });

        }
    }
});


</script>

@endsection