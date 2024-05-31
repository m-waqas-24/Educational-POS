@extends('admin.layouts.app')
@section('content')

        <!-- mani page content body part -->
        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h2>Analytical</h2>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                                <li class="breadcrumb-item">Dashboard</li>
                                <li class="breadcrumb-item active">Analytical</li>
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="d-flex flex-row-reverse">
                                <div class="page_action">
                                    @if(auth()->user()->type == 'csr')
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-search mr-2"></i> Search Student </button>
                                    @endif
                                </div>
                                <div class="p-2 d-flex">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="row clearfix row-deck">

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card top_widget">
                            <div class="body">
                                <div class="icon"><i class="mdi mdi-cellphone"></i> </div>
                                <div class="content">
                                    <div class="text mb-2 text-uppercase">Today Calls</div>
                                    <h4 class="number mb-0">{{ $totalCallToday }}</h4>
                                    <small class="text-muted">Analytics for Today</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($actionStatus as $status)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card top_widget">
                                <div class="body">
                                    <div class="icon"><i class="{{ $status->icon }}"></i> </div>
                                    <div class="content">
                                        <div class="text mb-2 text-uppercase">{{ $status->name }}</div>
                                        <h4 class="number mb-0">{{ $status->getCallCount() }}</h4>
                                        <small class="text-muted">Analytics for Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card top_widget">
                            <div class="body">
                                <div class="icon"><i class="mdi mdi-cellphone"></i> </div>
                                <div class="content">
                                    <div class="text mb-2 text-uppercase">Due Data CSR</div>
                                    <h4 class="number mb-0">{{ $students }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card top_widget">
                            <div class="body">
                                <div class="icon"><i class="mdi mdi-cellphone"></i> </div>
                                <div class="content">
                                    <div class="text mb-2 text-uppercase">Total CSR Data</div>
                                    <h4 class="number mb-0">{{ $totalData }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                @if(auth()->user()->type == 'csr')
                <div class="row clearfix row-deck">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header">
                                <h2>FOLLOW UP</h2>
                            </div>
                            <div class="body">
                                @php
                                    $classes = ['btn-primary', 'btn-secondary', 'btn-info', 'btn-danger', 'btn-warning', 'btn-success', 'btn-light', 'btn-dark', 'btn-link'];
                                @endphp
                            
                                @foreach ($actionStatus as $index => $status)
                                    @php
                                        $classIndex = $index % count($classes);
                                    @endphp
                                
                                    {{-- <button type="button" ></button> --}}
                                    <a href="{{ route('admin.filter.action.status',$status->id) }}" class="btn {{ $classes[$classIndex] }} mb-4"> {{ $status->name }} </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="body">
                                @foreach($csrs as $csr)
                                @if(Auth::user()->type == 'csr' && Auth::user()->id != $csr->id)
                                    @continue
                                @endif
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
                                @endforeach
                            </div>
                        </div>                
                    </div>
                </div>
                @endif
            
            </div>
        </div>
    

    
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="title text-white" id="largeModalLabel">Search Student</h4>
                    </div>
                    <form action="{{ route('admin.filter.student') }}" method="GET">
                        @csrf
                        <div class="modal-body"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Student Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Student Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Student CNIC</label>
                                        <input type="number" class="form-control" name="cnic" placeholder="CNIC">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

    @section('scripts')

   
<script>
    var csrData = @json($csrData);
    var csrEnrollData = @json($csrEnrollData);
    var csrPartialEnrollData = @json($csrPartialEnrollData); // Add this line to fetch partial enrollment data
    
    $(document).ready(function() {
        for (const [csrName, years] of Object.entries(csrData)) {
            for (const [year, months] of Object.entries(years)) {
                var totalData = ['Total'];
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
                            ['Total']: 'bar',
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