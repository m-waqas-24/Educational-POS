@extends('admin.layouts.app')
@section('content')
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Bootstrap Datepicker CSS and JS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h2>Distribution</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="fa fa-dashboard"></i></a></li>
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active">Distribution Students Data</li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex flex-row-reverse">
                            <div class="page_action">
                                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Import Data </button> --}}
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
                            <form action="{{ route('admin.distribute') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Select Date Range</label>
                                        <div class="input-daterange input-group" data-provide="datepicker">
                                            <input type="text" class="input-sm form-control" name="from"
                                                id="from" autocomplete="off">
                                            <span class="input-group-addon mx-2">To</span>
                                            <input type="text" class="input-sm form-control" name="to"
                                                id="to" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select required name="course" id="course" class="form-control">
                                                <option value="">Select Course</option>
                                                <option value="all">All Courses</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course }}">{{ $course }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Total Data</label>
                                            <input readonly value="0" type="text" id="totalDataDates"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Course Data</label>
                                            <input readonly value="0" type="text" id="totalDataCourse"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>CSRS</label>
                                        <div class="c_multiselect">
                                            <select id="multiselect1" name="csr[]" class="multiselect" multiple="multiple">
                                           
                                                @foreach ($csrs as $csr)
                                                    <option value="{{ $csr->id }}">{{ $csr->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4 text-center">
                                        <button type="submit" class="btn btn-sm btn-primary">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showCount(from, to, course) {
            var data = {
                _token: '{{ csrf_token() }}',
                from: from,
                to: to
            };

            if (course) {
                data.course = course;
            }

            $.ajax({
                url: '{{ route('admin.count.student') }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    var dateCount = response.studentsCountofDates;
                    var courseCount = response.studentsCountofCourse;
                    $('#totalDataDates').val(dateCount);
                    $('#totalDataCourse').val(courseCount);
                },
                error: function(error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        $(document).ready(function() {
            $('.input-daterange').datepicker();

            $('#from, #to, #course').on('change', function() {
                var from = $('#from').val();
                var to = $('#to').val();
                var course = $('#course').val();
                showCount(from, to, course);
            });
        });
    </script>
@endsection
