@extends('admin.layouts.app')
@section('content')

    <!-- mani page content body part -->
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <h2>Student Forecast</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                            <li class="breadcrumb-item active">Edit Student </li>
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
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                   
                        <div class="body">
                            <form id="wizard_with_validation" action="{{ route('admin.update.student', $stuCourse->student->id) }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
                                <h3>Account Information</h3>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="form-label">Student Name</label>
                                            <input required type="text" class="form-control" name="name" value="{{ $stuCourse ? $stuCourse->student->name : "" }}" placeholder=" Name">
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input required type="email" class="form-control" name="email" value="{{ $stuCourse ? $stuCourse->student->user->email : "" }}" placeholder="Email">
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                              <label class="form-label">CNIC</label>
                                              <input required type="text" pattern="\d*" maxlength="13" class="form-control" name="cnic" value="{{ $stuCourse ? $stuCourse->student->cnic : '' }}" placeholder="CNIC(Without dashes)">
                                          </div>
                                        </div>
                                      <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Whatsapp</label>
                                            <input  type="number"   class="form-control" name="whatsapp" value="{{ $stuCourse ? $stuCourse->student->whatsapp : '' }}"  placeholder="Whatsapp(Without dashes)">
                                        </div>
                                    </div> 
                                    @if(getUserType() == 'admin' || getUserType() == 'superadmin')
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Select Csr</label>
                                                <select class="form-select form-control" name="csr">
                                                <option value="">Select....</option>
                                                @foreach($csrs as $csr)
                                                    <option value="{{ $csr->id }}" {{ $stuCourse->student->csr_id == $csr->id ? 'selected' : '' }}>{{ $csr->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                        <div class="col-md-3">
                                          <div class="form-group">
                                              <label class="form-label">Qualifications</label>
                                              <select required class="form-select form-control" name="qualification_id">
                                                <option value="">Select Qualification</option>
                                                @foreach($qualifications as $qua)
                                                    <option value="{{ $qua->id }}" {{ $stuCourse->student->qualification_id == $qua->id ? 'selected' : '' }}>{{ $qua->name }} ({{ @$qua->parent->name }})</option>
                                                @endforeach
                                              </select>
                                            </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <label class="form-label">City</label>
                                              <select required class="form-select form-control" name="city_id">
                                                <option value="">Select City</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}"  {{ $stuCourse->student->city_id == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          <label class="form-label">Source</label>
                                          <select required class="form-select form-control" name="source">
                                            <option value="">Select Source</option>
                                              @foreach($sources as $source)
                                                  <option value="{{ $source->id }}" {{ $stuCourse->student->source_id == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                                              @endforeach
                                          </select>
                                        </div>
                                      </div>
                                  </div>
                                </fieldset>
                                <h3>Course Information</h3>
                               

                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Courses</label>
                                                <select required class="form-select  form-control courseSelectt" name="course">
                                                    <option value="">Select Course</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ $stuCourse->course_id == $course->id ? 'selected' : '' }}  data-fee="{{ $course->fee }}" data-card-fee="{{ $course->card_fee }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Fee</label>
                                                <input type="number" class="form-control courseFee" placeholder="Fees" readonly>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Discount</label>
                                                <input type="number" class="form-control discount" name="discount[]" min="0" value="0" placeholder="Discount Fee (Optional)" required>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Batch</label>
                                                <select required class="form-select form-control" name="batch_id">
                                                    <option value="">Select Batch</option>
                                                    @foreach($batches as $batch)
                                                        <option value="{{ $batch->id }}" {{ $stuCourse->batch_id == $batch->id ? 'selected' : '' }} >{{ $batch->number }} - {{ $batch->course->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Student Card Fee</label>
                                                <input type="number" readonly class="form-control studentCardFee" name="card[]" value="0" min="0" required>
                                            </div>
                                        </div> --}}
                                     
 
                                    </div>
                                    <div id="courseContainer">
                                 
                                    </div>
          
                                    {{-- <div class="col-md-12 text-right">
                                         <button type="button" class="btn btn-warning btn-sm" id="addCourseBtn">
                                             <i class="fa fa-plus"></i> Add Course
                                         </button>
                                    </div> --}}
                                 </fieldset>
                              
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

        $(document).ready(function () {
     
            // Trigger file input when the upload button is clicked
            $(document).on('click', '.uploadButton', function () {
                $(this).prev('.receiptInput').click();
            });

            // Update button text and style when a file is selected
            $(document).on('change', '.receiptInput', function () {
                var input = this;
                var uploadButton = $(this).next('.uploadButton');
                handleFileInputChange(input, uploadButton);
            });

            // Function to handle file input change
            function handleFileInputChange(input, uploadButton) {
                if (input.files && input.files.length) {
                    var fileName = input.files[0].name;
                    uploadButton.text('Receipt Uploaded');
                    uploadButton.removeClass('btn-warning').addClass('btn-success');
                } else {
                    uploadButton.text('Please Upload receipt!');
                    uploadButton.removeClass('btn-success').addClass('btn-warning');
                }
            }
        
            $(document).on('change', '.courseSelectt', function () {
        var courseId = $(this).val();
        var batchSelect = $(this).closest('.row').find('[name="batch_id[]"]'); // Select batch within the same row

        var batches = {!! json_encode($batches) !!};

        var filteredBatches = batches.filter(function (batch) {
            return batch.course_id == courseId;
        });

        batchSelect.empty();
        if (filteredBatches.length > 0) {
            $.each(filteredBatches, function (index, batch) {
                var batchName = batch.course && batch.course.name ? batch.course.name : 'Unknown';
                batchSelect.append($('<option>', {
                    value: batch.id,
                    text: batch.number + ' (' + batchName + ')'
                }));
            });
        } else {
            batchSelect.append($('<option>', {
                value: '',
                text: 'No Batches Available'
            }));
        }
    });
    

            // Function to handle course selection change
            function handleCourseSelectionChange() {
                var selectedCourse = $(this).find(":selected");
                var fee = parseFloat(selectedCourse.data("fee")) || 0; // Get course fee
                var cardFee = parseFloat(selectedCourse.data("card-fee")) || 0; // Get card fee
                var row = $(this).closest(".row");
                var courseFeeInput = row.find(".courseFee");
                var discountInput = row.find(".discount");
                var studentCardFeeInput = row.find(".studentCardFee");

                // Update the student card fee input with the card fee for the selected course
                studentCardFeeInput.val(cardFee);

                updateTotalFee(); // Initial update

                // Bind the update function to discount and studentCardFee inputs
                discountInput.on("keyup", updateTotalFee);
                studentCardFeeInput.on("keyup", updateTotalFee);

                // Function to update the total fee
                function updateTotalFee() {
                    var discount = parseFloat(discountInput.val()) || 0;
                    var studentCardFee = parseFloat(studentCardFeeInput.val()) || 0;
                    var updatedFee = fee + studentCardFee - discount;

                    courseFeeInput.val(updatedFee.toFixed(2));
                }
            }

            // Bind the course selection change event to elements with class .courseSelectt
            $(".courseSelectt").on("change", handleCourseSelectionChange);

            $(document).on('change', '.courseSelect', function () {
                var courseId = $(this).val();
                var batchSelect = $(this).closest('.row').find('[name="batch_id[]"]'); // Select batch within the same row

                var batches = {!! json_encode($batches) !!};

                var filteredBatches = batches.filter(function (batch) {
                    return batch.course_id == courseId;
                });

                batchSelect.empty();
                if (filteredBatches.length > 0) {
                    $.each(filteredBatches, function (index, batch) {
                        batchSelect.append($('<option>', {
                            value: batch.id,
                            text: batch.number + ' (' + batch.course.name + ')'
                        }));
                    });
                    
                } else {
                    batchSelect.append($('<option>', {
                        value: '',
                        text: 'No Batches Available'
                    }));
                }
                
            });

        // Event handler for course selection change
        $(document).on("change", ".courseSelect", function() {
            var selectedCourse = $(this).find(":selected");
            var fee = parseFloat(selectedCourse.data("fee")) || 0;
            var row = $(this).closest(".row");
            var courseFeeInput = row.find(".courseFee");
            var discount = parseFloat(row.find(".discountt").val()) || 0;
            var updatedFee = fee - discount;
            courseFeeInput.val(updatedFee.toFixed(2));
        });

        // Event handler for discount change
        $(document).on("keyup", ".discountt", function() {
            var row = $(this).closest(".row");
            var selectedCourse = row.find(".courseSelect :selected");
            var fee = parseFloat(selectedCourse.data("fee")) || 0;
            var courseFeeInput = row.find(".courseFee");
            var discount = parseFloat($(this).val()) || 0;
            var updatedFee = fee - discount;
            courseFeeInput.val(updatedFee.toFixed(2));
        });

        function appendCourseSelectRow() {
            var newRow = '<hr>' +
                '<div class="row mt-2">' +
                '<div class="col-md-3">' +
                '<div class="form-group">' +
                '<label class="form-label">Courses</label>' +
                '<select class="form-select form-control courseSelect" name="courses[]">' +
                '<option value="">Select Course</option> @foreach($courses as $course) <option value="{{ $course->id }}" data-fee="{{ $course->fee }}">{{ $course->name }}</option> @endforeach' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="form-group">' +
                '<label class="form-label">Fee</label>' +
                '<input type="number" class="form-control courseFee" name="course_fee[]" placeholder="Fees" readonly>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="form-group">' +
                '<label class="form-label">Discount</label>' +
                '<input type="number" class="form-control discountt" name="discount[]"  min="0" value="0" placeholder="Discount">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="form-group">' +
                '<label class="form-label">Batch</label>' +
                '<select required class="form-select form-control batchSelect" name="batch_id[]">' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="form-group">' +
                '<label class="form-label">Payment Date</label>' +
                '<input type="date" class="form-control" name="payment_date_first[]" value="{{ \Carbon\Carbon::today()->toDateString() }}" required>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-9">' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<label class="form-label">Payment Details</label>' +
                '<div class="input-group mb-3">' +
                '<select required class="form-select form-control" name="mode_first[]" id="inputGroupSelect02">' +
                '<option value="">Select Payment Mode</option> @foreach($modes as $mode) <option value="{{ $mode->id }}" >{{ $mode->name }}</option> @endforeach' +
                '</select>' +
                '<input required type="number" class="form-control" name="payment_first[]" min="0" placeholder="Enter Received Amount">' +
                '<input type="file" name="first_receipt[]" class="receiptInput" style="display: none;">' +
                '<button type="button" class="btn btn-warning uploadButton">Please Upload receipt!</button>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-12">' +
                '<label class="form-label">Payment Details</label>' +
                '<div class="input-group mb-3">' +
                '<select class="form-select form-control" name="mode_second[]" id="inputGroupSelect02">' +
                '<option value="">Select Payment Mode</option> @foreach($modes as $mode) <option value="{{ $mode->id }}" >{{ $mode->name }} </option> @endforeach' +
                '</select>' +
                '<input type="number" class="form-control" name="payment_second[]" min="0" placeholder="Enter Received Amount">' +
                '<input type="file" name="second_receipt[]" class="receiptInput" style="display: none;">' +
                '<button type="button" class="btn btn-warning uploadButton">Please Upload receipt!</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<button type="button" class="btn btn-danger btn-sm deleteRowBtn">' +
                '<i class="fa fa-trash"></i>' +
                '</button>' +
                '</div>' +
                '</div>';

            $("#courseContainer").append(newRow);

            var addedCourseRow = $("#courseContainer .row").last();
            var courseSelect = addedCourseRow.find(".courseSelect");
            var batchSelect = addedCourseRow.find(".batchSelect");

            courseSelect.on("change", handleCourseSelecttChange);
            $(".deleteRowBtn").click(function () {
                $(this).closest(".row").remove();
            });
        }

        $("#addCourseBtn").click(function () {
            appendCourseSelectRow();
        });


    });
        
    </script>
    
    
    <script>
      document.addEventListener('input', function (e) {
          if (e.target.name === 'cnic') {
              e.target.value = e.target.value.replace(/\D/g, '');
    
              const maxLength = (e.target.name == 'cnic') ? 13 : 11;
    
              if (e.target.value.length !== maxLength) {
                  e.target.classList.add('is-invalid');
              } else {
                  e.target.classList.remove('is-invalid');
              }
          }
      });
    </script>
    

    @endsection