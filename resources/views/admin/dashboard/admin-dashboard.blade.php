@extends('admin.layouts.app')
@section('content')

<style>
    .wizard .content{
        min-height: 20px !important;
    }
</style>

@if($from || $to)
    @php
        $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
        $toDate = \Carbon\Carbon::parse($to)->endOfDay();
    @endphp
@endif

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
                    <div class="body">
                        <form action="{{ route('admin.dashboard') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-8">
                                    <label>Filter CSR Activity</label>  
                                    <div class="input-daterange input-group" data-provide="datepicker">
                                        <input type="text" value="" class="input-sm form-control" name="from" autocomplete="off">
                                        <span class="input-group-addon mx-2">To</span>
                                        <input type="text"  value="" class="input-sm form-control" name="to" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-primary "><i class="fa fa-search mr-2"></i> FILTER</button>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-warning btn-sm" name="filterdates" value="last_threedays"><i class="fa fa-calendar mr-2"></i>Last 3 Days</button>
                                    <button type="submit" class="btn btn-warning btn-sm" name="filterdates" value="last_week"><i class="fa fa-calendar mr-2"></i>Last Week</button>
                                    <button type="submit" class="btn btn-warning btn-sm" name="filterdates" value="current_month"><i class="fa fa-calendar mr-2"></i>Current Month</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="header">
                        @if($from && $to)
                            <h2>CSR ACTIVITY from {{ \Carbon\Carbon::parse($from)->format('d F, Y')  }} to {{ \Carbon\Carbon::parse($to)->format('d F, Y') }} </h2>
                        @else
                            <h2>CSR TODAY ACTIVITY  </h2>
                        @endif
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
                                                <a href="{{ route('admin.filter.action.status.today',  ['id' => null, 'csr' => $csr->id, 'from' => $from, 'to' => $to]) }}">
                                                    <div class="body">
                                                        <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                        <div class="content">
                                                            <div class="text mb-2 text-uppercase">@if($from && $to) Total @else Today @endif Calls</div>
                                                            @if(!$from && !$to)
                                                                <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->whereDate('called_at', \Carbon\Carbon::today()->toDateString())->count() }}</h4>
                                                            @else
                                                                <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->where('called_at', '>=', \Carbon\Carbon::parse($from)->startOfDay())
                                                                                            ->where('called_at', '<=', \Carbon\Carbon::parse($to)->endOfDay())->count() }}</h4>
                                                            @endif
                                                            <small class="text-muted">Analytics for Today</small>   
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        @foreach ($actionStatus as $status)
                                            <div class="col-md-4">
                                                <div class="card top_widget">
                                                    <a href="{{ route('admin.filter.action.status.today',  ['id' => $status->id, 'csr' => $csr->id, 'from' => $from, 'to' => $to]) }}">
                                                        <div class="body">
                                                            <div class="icon"><i class="{{ $status->icon }}"></i></div>
                                                            <div class="content">
                                                                <div class="text mb-2 text-uppercase">{{ $status->name }}</div>
                                                                @if(!$from && !$to)
                                                                <h4 class="number mb-0">{{ $status->CsrStudent()->where('csr_id', $csr->id)->whereDate('called_at', \Carbon\Carbon::today())->count() }}</h4>
                                                            @else
                                                                <h4 class="number mb-0">
                                                                    {{ $status->CsrStudent()
                                                                        ->where('csr_id', $csr->id)
                                                                        ->where('called_at', '>=', \Carbon\Carbon::parse($from)->startOfDay())
                                                                        ->where('called_at', '<=', \Carbon\Carbon::parse($to)->endOfDay())
                                                                        ->count() }}
                                                                </h4>
                                                            @endif
                                                            
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
                                                        {{-- <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where(['csr_id' => $csr->id, 'action_status_id' => 0 ])->count() }}</h4> --}}
                                                        @if(@$from || @$to)
                                                        <h4 class="number mb-0">
                                                            {{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)
                                                            ->where('action_status_id', 0)
                                                            ->whereHas('student', function ($query) use ($fromDate, $toDate) {
                                                                $query->whereBetween('datetime', [$fromDate, $toDate]);
                                                            })
                                                            ->count() }} 
                                                            </h4>
                                                        @else
                                                        <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where(['csr_id' => $csr->id, 'action_status_id' => 0 ])->count() }}</h4>
                                                        @endif
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
                                                            @if(@$from || @$to)
                                                            <h4 class="number mb-0">
                                                                {{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)  
                                                                ->whereHas('student', function ($query) use ($fromDate, $toDate) {
                                                                    $query->whereBetween('datetime', [$fromDate, $toDate]);
                                                                })
                                                                ->count() }} 
                                                        </h4>
                                                        @else
                                                        <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->count() }}</h4>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <a href="{{ route('admin.filter.csr-activity.students', ['csrId' => $csr->id, 'status_id' => 1, 'from' => $from, 'to' => $to]) }}">
                                                    <div class="body">
                                                        <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                        <div class="content">
                                                            <div class="text mb-2 text-uppercase">Total Paid Students</div>
                                                            @php
                                                                $currentYear = date('Y');
                                                                $courseCount = \App\Models\Student::query()
                                                                    ->selectRaw('COUNT(*) as course_count')
                                                                    ->join('student_courses as sc', 'students.id', '=', 'sc.student_id')
                                                                    ->join(DB::raw('(SELECT student_course_id, MIN(payment_date_first) AS first_payment_date
                                                                                    FROM student_course_payments
                                                                                    GROUP BY student_course_id) as scp'), 'sc.id', '=', 'scp.student_course_id')
                                                                    ->where('students.csr_id', $csr->id)
                                                                    ->where('sc.status_id', 1)
                                                                    ->whereYear('scp.first_payment_date', $currentYear);

                                                                // Apply date range filter if both $from and $to are provided
                                                                if ($from && $to) {
                                                                    $courseCount->whereDate('scp.first_payment_date', '>=', \Carbon\Carbon::parse($from)->startOfDay())
                                                                                ->whereDate('scp.first_payment_date', '<=', \Carbon\Carbon::parse($to)->startOfDay());
                                                                }

                                                                $courseCount = $courseCount->first();

                                                                $PaidcourseCount = $courseCount->course_count ?? 0;
                                                            @endphp

                                                            <h4 class="number mb-0">{{ $PaidcourseCount }}</h4>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <a href="{{ route('admin.filter.csr-activity.students', ['csrId' => $csr->id, 'status_id' => 2, 'from' => $from, 'to' => $to]) }}">
                                                    <div class="body">
                                                        <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                        <div class="content">
                                                            <div class="text mb-2 text-uppercase">Total Partial Students</div>
                                                            @php
                                                                $currentYear = date('Y');
                                                                $courseCount = \App\Models\Student::query()
                                                                    ->selectRaw('COUNT(*) as course_count')
                                                                    ->join('student_courses as sc', 'students.id', '=', 'sc.student_id')
                                                                    ->join(DB::raw('(SELECT student_course_id, MIN(payment_date_first) AS first_payment_date
                                                                                    FROM student_course_payments
                                                                                    GROUP BY student_course_id) as scp'), 'sc.id', '=', 'scp.student_course_id')
                                                                    ->where('students.csr_id', $csr->id)
                                                                    ->where('sc.status_id', 2)
                                                                    ->whereYear('scp.first_payment_date', $currentYear);

                                                                // Apply date range filter if both $from and $to are provided
                                                                if ($from && $to) {
                                                                    $courseCount->whereDate('scp.first_payment_date', '>=', \Carbon\Carbon::parse($from)->startOfDay())
                                                                                ->whereDate('scp.first_payment_date', '<=', \Carbon\Carbon::parse($to)->startOfDay());
                                                                }

                                                                $courseCount = $courseCount->first();

                                                                $PartialcourseCount = $courseCount->course_count ?? 0;
                                                            @endphp
                                                        
                                                        
                                                            <h4 class="number mb-0">{{ $PartialcourseCount }}</h4>
                                                        </div>
                                                    </div>
                                                </a>
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
            {{-- <div class="col-md-12">
                <div class="card">
                    <div class="body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div> --}}
            

        </div>            
    </div>
</div>




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