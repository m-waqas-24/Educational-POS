@extends('accounts.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2> Students List</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Students</li>
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

        <div class="card">
            <div class="body">
                <form action="{{ route('search.accounts') }}" method="GET" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <label>Range</label>                                    
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" value="{{ $from ?? '' }}" class="input-sm form-control" name="from" autocomplete="off">
                                <span class="input-group-addon mx-2">To</span>
                                <input type="text" value="{{ $to ?? '' }}" class="input-sm form-control" name="to" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Select Course</label>
                            <select name="course" class="form-control form-select" id="">
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" >{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search mr-2"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row clearfix">

            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        @if($from && $to)
                            <p>Showing filtered results from - <b>{{ \Carbon\Carbon::parse($from)->format('d F Y') }}</b> to <b>{{ \Carbon\Carbon::parse($to)->format('d F Y') }}</b> </p>
                        @else
                            <p>Showing all results</p>
                        @endif
						<div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase">Course</th>
                                        <th class="text-uppercase">Batch</th>
                                        <th class="text-uppercase">Name</th>
                                        <th class="text-uppercase">Course Fee</th>
                                        <th class="text-uppercase">Card</th>
                                        <th class="text-uppercase">Discount</th>
                                        <th class="text-uppercase">Total Fee</th>
                                        @for($i=1; $i<=10; $i++)
                                            <th class="text-uppercase">{{$i}}st Installment Mode 1</th>
                                            <th class="text-uppercase">Amount</th>
                                            <th class="text-uppercase">{{$i}}st Installment Mode 2</th>
                                            <th class="text-uppercase">Amount</th>
                                            <th class="text-uppercase">Date</th>
                                        @endfor
                                        <th class="text-uppercase">Balance</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    @foreach($studentCourse as $index => $studentCourse)
                                        @php
                                            $totalFee = ($studentCourse->fee + $studentCourse->discount) + $studentCourse->student->card - $studentCourse->discount ;
                                        @endphp
                                        @if($from && $to)
                                            @php
                                                $filteredPayments = $studentCourse->coursePayments->whereBetween('payment_date_first', [$from, $to]);
                                            @endphp
                                            @if(count($filteredPayments) > 0)
                                                <tr>
                                                    <td>{{ $studentCourse->course->name }}</td> 
                                                    <td>{{ $studentCourse->batch->number }}</td> 
                                                    <td>{{ $studentCourse->student->name }}</td> 
                                                    <td>{{ $studentCourse->fee + $studentCourse->discount }}</td> 
                                                    <td>{{ $studentCourse->student->card }}</td>
                                                    <td>{{ $studentCourse->discount }}</td> 
                                                    <td>{{ $totalFee }}</td>
                                                    
                                                    @php
                                                        $installmentTotal = 0;
                                                    @endphp
                                                    @for($i = 1; $i <= 10; $i++)
                                                        @php
                                                            $paymentExists = isset($filteredPayments[$i - 1]);
                                                            $pay = $paymentExists ? $filteredPayments[$i - 1] : null;
                                                            $first = $paymentExists && $pay->mode_first ? $pay->payment_first : '';
                                                            $second = $paymentExists && $pay->mode_second ? $pay->payment_second : '';
                                                            $date = $paymentExists && $pay->mode_first ? \Carbon\Carbon::parse($pay->payment_date_first)->format('d F Y') : '';
                                                
                                                            if ($paymentExists && $pay->mode_first) {
                                                                $installmentTotal += $pay->payment_first;
                                                            }
                                                            if ($paymentExists && $pay->mode_second) {
                                                                $installmentTotal += $pay->payment_second;
                                                            }
                                                        @endphp
                                                        <td>{{ $paymentExists && $pay->mode_first ? $pay->modeOne->name : '' }}</td> 
                                                        <td>{{ $first }}</td> 
                                                        <td>{{ $paymentExists && $pay->mode_second ? $pay->modeTwo->name : '' }}</td> 
                                                        <td>{{ $second }}</td> 
                                                        <td>{{ $date }}</td>
                                                    @endfor
                                                
                                                    <td>{{ $totalFee - $installmentTotal }}</td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td>{{ $studentCourse->course->name }}</td> 
                                                <td>{{ $studentCourse->batch->number }}</td> 
                                                <td>{{ $studentCourse->student->name }}</td> 
                                                <td>{{ $studentCourse->fee + $studentCourse->discount }}</td> 
                                                <td>{{ $studentCourse->student->card  }}</td>
                                                <td>{{ $studentCourse->discount }}</td> 
                                                <td>{{ $totalFee }}</td>
                                                
                                                @php
                                                    $installmentTotal = 0;
                                                @endphp
                                                @for($i = 1; $i <= 10; $i++)
                                                    @php
                                                        $paymentExists = isset($studentCourse->coursePayments[$i - 1]);
                                                        $pay = $paymentExists ? $studentCourse->coursePayments[$i - 1] : null;
                                                        $first = $paymentExists && $pay->mode_first ? $pay->payment_first : '';
                                                        $second = $paymentExists && $pay->mode_second ? $pay->payment_second : '';
                                                        $date = $paymentExists && $pay->mode_first ? \Carbon\Carbon::parse($pay->payment_date_first)->format('d F Y') : '';
                                            
                                                        if ($paymentExists && $pay->mode_first) {
                                                            $installmentTotal += $pay->payment_first;
                                                        }
                                                        if ($paymentExists && $pay->mode_second) {
                                                            $installmentTotal += $pay->payment_second;
                                                        }
                                                    @endphp
                                                    <td>{{ $paymentExists && $pay->mode_first ? $pay->modeOne->name : '' }}</td> 
                                                    <td>{{ $first }}</td> 
                                                    <td>{{ $paymentExists && $pay->mode_second ? $pay->modeTwo->name : '' }}</td> 
                                                    <td>{{ $second }}</td> 
                                                    <td>{{ $date }}</td>
                                                @endfor
                                            
                                                <td>{{ $totalFee - $installmentTotal }}</td>
                                            </tr>
                                        @endif
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