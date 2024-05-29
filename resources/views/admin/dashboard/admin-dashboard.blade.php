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
                    <h2>Dashbaord</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashbaord</li>
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
                                <h2 class="text-white" style="color: #fff !important;">{{ $csr->name }}</h2>
                                <section>
                                    <div class="row clearfix row-deck">
                                        <div class="col-md-4">
                                            <div class="card top_widget">
                                                <div class="body">
                                                    <div class="icon"><i class="mdi mdi-cellphone"></i></div>
                                                    <div class="content">
                                                        <div class="text mb-2 text-uppercase">Today Calls</div>
                                                        <h4 class="number mb-0">{{ \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->whereDate('called_at', \Carbon\Carbon::today()->toDateString())->count() }}</h4>
                                                        <small class="text-muted">Analytics for Today</small>
                                                    </div>
                                                </div>
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
                                                        {{  \App\Models\Admin\CsrStudent::where('csr_id', $csr->id)->count(); }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Combination chart for {{ $csr->name }}</h2>
                                                </div>
                                                <div class="body">
                                                    @foreach(array_keys($csrData[$csr->name]) as $year)
                                                        <div id="chart-combinationcsr-{{ $csr->name }}-{{ $year }}" style="height: 16rem"></div>
                                                    @endforeach
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

$(document).ready(function(){
    for (const [csrName, years] of Object.entries(csrData)) {
        for (const [year, months] of Object.entries(years)) {
            var data = [csrName];
            var categories = [];

            for (let month = 1; month <= 12; month++) {
                data.push(months[month] || 0);
                categories.push(new Date(2000, month - 1).toLocaleString('default', { month: 'long' }));
            }

            var chart = c3.generate({
                bindto: '#chart-combinationcsr-' + csrName + '-' + year,
                data: {
                    columns: [data],
                    type: 'bar'
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