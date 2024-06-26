@extends('admin.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Data Distribution Record List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Distribution Record</li>
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

            <div class="col-md-12">
                <div class="card">
                    <div class="body">
                        <form action="{{ route('admin.filter.distribute-record') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-md-8">
                                    <label>Range</label>  
                                    <div class="input-daterange input-group" data-provide="datepicker">
                                        <input type="text" value="{{ @$from ?? '' }}" class="input-sm form-control" name="from" autocomplete="off">
                                        <span class="input-group-addon mx-2">To</span>
                                        <input type="text" value="{{ @$to ?? '' }}" class="input-sm form-control" name="to" autocomplete="off">
                                    </div>
                                </div>
                                
                                <div class=" mt-4">
                                    <button type="submit" class="btn btn-primary mt-2 btn-sm"><i class="fa fa-search mr-2"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Total Data</th>
                                        <th class="text-uppercase">CSR</th>
                                        <th class="text-uppercase">From</th>
                                        <th class="text-uppercase">To</th>
                                        <th class="text-uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $rep)
                                    <tr>
                                        <td>{{ $rep->total }}</td>
                                        <td>
                                            @php
                                                $teamData = json_decode($rep->team, true);
                                            @endphp
                                            @if($teamData)
                                                @foreach($teamData as $csrId => $studentCount)
                                                    @php
                                                        $csr = \App\Models\User::find($csrId); // Retrieve CSR by ID
                                                    @endphp
                                                    @if($csr)
                                                        <span class="badge badge-primary">{{ $csr->name }}: {{ $studentCount }}</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($rep->from)->format('d F, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rep->to)->format('d F, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rep->created_at)->format('d F, Y / h:i:A') }}</td>
                                        
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


