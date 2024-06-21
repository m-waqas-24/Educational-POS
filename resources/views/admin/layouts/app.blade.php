<!doctype html>
<html lang="en">

<head>
<title></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="author" content="">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/charts-c3/plugin.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">   
<link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/fullcalendar.min.css') }}">


<!-- MAIN Project CSS file -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

<link rel="stylesheet" href="{{ asset('assets/vendor/morrisjs/morris.css') }}" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/chatapp.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/multi-select/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/nouislider/nouislider.min.css') }}" />


<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/nestable/jquery-nestable.css') }}"/>


</head>

</head>

<body data-theme="light" class="font-nunito">

    @if(session('errors') && count(session('errors')) > 0)
    <script>
       var errorMessages = @json(session('errors')->all()); 
       var errorMessageString = '<ul>';
       errorMessages.forEach(function(errorMessage) {
          errorMessageString += '<li>' + errorMessage + '</li>';
       });
       errorMessageString += '</ul>';
       Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Error!',
          html: errorMessageString,
          showConfirmButton: false,
          timer: 12000,
          customClass: {
             title: 'my-custom-font-size'
          }
       });
    </script>
    <style>
       .my-custom-font-size {
          font-size: 18px;
       }
    </style>
    @endif
    
    
    @if(session('success'))
    <script>
       Swal.fire({
       position: 'center',
       icon: 'success',
       title: '{{ session('success') }}',
       showConfirmButton: false,
       timer: 12000,
       customClass: {
       title: 'my-custom-font-size'
    }
    });
    </script>
    <style>
       .my-custom-font-size {
          font-size: 18px; 
       }
    </style>
    @endif

<div id="wrapper" class="theme-cyan">

    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="{{ asset('assets/images/logo-icon.svg') }}" width="48" height="48" alt=""></div>
            <p>Please wait...</p>
        </div>
    </div>

    <!-- Top navbar div start -->
    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-brand">
                <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-bars"></i></button>
                <button type="button" class="btn-toggle-fullwidth"><i class="fa fa-bars"></i></button>
                @if(getUserType() == 'superadmin')
                    <a href="{{ route('admin.dashboard') }}">NIAIS</a>                
                @else
                    <a href="{{ route('admin.dashboard') }}">NIAIS</a>                
                @endif
            </div>
            
            <div class="navbar-right">
                <form id="navbar-search" class="navbar-form search-form">
                    <input value="" class="form-control" placeholder="Search here..." type="text">
                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                </form>                

                <div id="navbar-menu">
                    <ul class="nav navbar-nav">
                       
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- main left menu -->
    <div id="left-sidebar" class="sidebar">
        <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-arrow-left"></i></button>
        <div class="sidebar-scroll">
            <div class="user-account">
                @if(auth()->user()->img)
                    <img src="{{ asset('storage/'.auth()->user()->img) }}" class="rounded-circle user-photo" alt="User Profile Picture">
                @else
                    <img src="{{ asset('assets/images/user.png') }}" class="rounded-circle user-photo" alt="User Profile Picture">
                @endif
                <div class="dropdown">
                    <span>Welcome,</span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{ Auth::user()->name }}</strong></a>
                    <ul class="dropdown-menu dropdown-menu-right account">
                        <li><a href="{{ route('admin.profile') }}"><i class="fa fa-user"></i>My Profile</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                      <i class="fa fa-power-off"></i>  {{ __('Logout') }} 
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </li>
                    </ul>
                </div>                
                <hr>
            </div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu">Menu</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="fa fa-gear"></i></a></li>               
            </ul>
                
            <!-- Tab panes -->
            <div class="tab-content padding-0">
                <div class="tab-pane active" id="menu">
                    <nav id="left-sidebar-nav" class="sidebar-nav">
                        <ul class="metismenu li_animation_delay">
                            @if(getUserType() == 'superadmin' || getUserType() == 'admin')
                            @if(getUserType() == 'superadmin')
                                    <li class="{{ request()->is('admin/-dashboard') ? ' active' : '' }}">
                                        <a href="{{ route('admin.dashboard') }}" ><i class="fa fa-dashboard"></i>Dashboard</a>
                                    </li>
                                @else
                                    <li class="{{ request()->is('admin/dashboard') ? ' active' : '' }}">
                                        <a href="{{ route('admin.dashboard') }}" ><i class="fa fa-dashboard"></i>Dashboard</a>
                                    </li>
                                @endif
                                <li class="{{ request()->input('status') == 2 || request()->input('status') == 1 ? 'active' : '' }}">
                                    <a href="#Tables" class="has-arrow"><i class="fa fa-users"></i><span>All Students</span></a>
                                    <ul>
                                        <li class="{{ request()->input('status') == 2 ? 'active' : '' }}">
                                            <a href="{{ route('admin.index.students', ['status' => 2]) }}">Partial <span class="badge badge-danger float-right">{{ partialStudents() }}</span></a>
                                        </li>
                                        <li class="{{ request()->input('status') == 1 ? 'active' : '' }}">
                                            <a href="{{ route('admin.index.students', ['status' => 1]) }}">Paid <span class="badge badge-success float-right">{{ paidStudents() }}</span></a> 
                                        </li>
                                        <li class="{{ request()->is('admin/discontinued-students') ? 'active' : '' }}">
                                            <a href="{{ route('admin.discontinued.students') }}">Discontinued <span class="badge badge-success float-right">{{ getDiscontinuedStudent() }}</span></a> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="{{ request()->is('admin/hold-students') ? ' active' : '' }}"><a href="{{ route('admin.hold.students') }}" ><i class="fa fa-stop"></i>CSR Students Hold <span class="badge badge-success float-right">{{ holdStudents() }}</span> </a></li>
                                <li class="{{ request()->is('admin/distribution-records') ? ' active' : '' }}"><a href="{{ route('admin.distribute.record') }}" ><i class="fa fa-stop"></i>Distribution Records      </a></li>
                                @if(getUserType() == 'admin')
                                    <li class="{{ request()->is('admin/imported-data') ? ' active' : '' }}"><a href="{{ route('admin.index.import-data') }}" ><i class="fa fa-area-chart"></i>Import Data</a></li>
                                    <li class="{{ request()->is('admin/duplicate-data') ? ' active' : '' }}"><a href="{{ route('admin.duplicate.import-data') }}" ><i class="fa fa-map"></i>Duplicate Data</a></li>
                                    <li class="{{ request()->is('admin/distribution') ? ' active' : '' }}"><a href="{{ route('admin.distribute.index') }}" ><i class="fa fa-lock"></i>Distribute Data</a></li>
                                    <li class="{{ request()->is('admin/instructors') ? ' active' : '' }}"><a href="{{ route('admin.index.instructors') }}" ><i class="fa fa-list"></i>Instructors</a></li>
                                    <li class="{{ request()->is('admin/csr-reports') ? ' active' : '' }}"><a href="{{ route('admin.csr.reports') }}" ><i class="fa fa-list"></i>CSR Reports</a></li>
                                    <li class="{{ request()->is('admin/all-tasks') ? ' active' : '' }}"><a href="{{ route('admin.index.task') }}" ><i class="fa fa-list"></i>Batch Tasks</a></li>
                                  
                                @endif
                                <li class="{{ request()->is('admin/csrs') ? ' active' : '' }}"><a href="{{ route('admin.csr.index') }}" ><i class="fa fa-user"></i>CSR Management</a></li>
                                <li class="{{ request()->is('admin/batches') ? ' active' : '' }}"><a href="{{ route('admin.index.batches') }}" ><i class="fa fa-th-list"></i>Batches</a></li>
                                {{-- <li class="{{ request()->is('admin/orientations') ? ' active' : '' }}"><a href="{{ route('admin.index.orientations') }}" ><i class="fa fa-link"></i>Orientations</a></li> --}}
                            @endif
                            @if(getUserType() == 'superadmin')
                                <li class="{{ request()->is('admin/banks') ? ' active' : '' }}"><a href="{{ route('admin.index.bank') }}" ><i class="fa fa-money"></i>Banks</a></li>
                                {{-- <li class="{{ request()->is('admin/banks') ? ' active' : '' }}"><a href="" ><i class="fa fa-money"></i>Workshops</a></li> --}}
                            @endif

                            @if(auth()->user()->type == 'csr')
                            <li class="{{ request()->is('admin/dashboard') ? ' active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}" ><i class="fa fa-dashboard"></i>Dashboard</a>
                            </li>
                            <li class="{{ request()->input('status') == 2 || request()->input('status') == 1 ? 'active' : '' }}">
                                <a href="#Tables" class="has-arrow"><i class="fa fa-users"></i><span>All Students</span></a>
                                <ul>
                                    <li class="{{ request()->input('status') == 2 ? 'active' : '' }}">
                                        <a href="{{ route('admin.index.students', ['status' => 2]) }}">Partial Students <span class="badge badge-warning float-right">{{ partialStudents() }}</span></a>
                                    </li>
                                    <li class="{{ request()->input('status') == 1 ? 'active' : '' }}">
                                        <a href="{{ route('admin.index.students', ['status' => 1]) }}">Paid Students <span class="badge badge-warning float-right">{{ paidStudents() }}</span></a> 
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('admin/hold-students') ? ' active' : '' }}"><a href="{{ route('admin.hold.students') }}" ><i class="fa fa-stop"></i>CSR Students Hold <span class="badge badge-success float-right">{{ holdStudents() }}</span> </a></li>
                            <li class="{{ request()->is('admin/csr-students-data') ? ' active' : '' }}"><a href="{{ route('admin.csr-data.index') }}" ><i class="fa fa-user"></i>Students Data</a></li>

                            @endif
                            @if((auth()->user()->type == 'csr' && Auth::user()->role_id == 1 && Auth::user()->permission == 1)  || getUserType() == 'superadmin' || getUserType() == 'admin')
                            <li class="{{ request()->is('admin/followupdata-remarks') ? ' active' : '' }}"><a href="{{ route('admin.data.remarks') }}" ><i class="fa fa-lock"></i>QA Section</a></li>
                            @endif
                            <li class="{{ request()->is('admin/courses') ? ' active' : '' }}"><a href="{{ route('admin.index.course') }}" ><i class="fa fa-clipboard"></i>Courses</a></li>
                            <li class="{{ request()->is('admin/all-requests') ? ' active' : '' }}"><a href="{{ route('admin.index.requests') }}" ><i class="fa fa-user"></i>Team Requests</a></li>
                            <li class="{{ request()->is('admin/enroll-form') ? ' active' : '' }}"><a href="{{ route('admin.csr.enroll-student') }}" ><i class="fa fa-user-plus"></i>Enroll Student Manually</a></li>

                        </ul>
                    </nav>
                </div>
                <div class="tab-pane" id="setting">
                    <h6>Choose Skin</h6>
                    <ul class="choose-skin list-unstyled">
                        <li data-theme="purple"><div class="purple"></div></li>
                        <li data-theme="blue"><div class="blue"></div></li>
                        <li data-theme="cyan" class="active"><div class="cyan"></div></li>
                        <li data-theme="green"><div class="green"></div></li>
                        <li data-theme="orange"><div class="orange"></div></li>
                        <li data-theme="blush"><div class="blush"></div></li>
                        <li data-theme="red"><div class="red"></div></li>
                    </ul>

                    <ul class="list-unstyled font_setting mt-3">
                        <li>
                            <label class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="font" value="font-nunito" checked="">
                                <span class="custom-control-label">Nunito Google Font</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="font" value="font-ubuntu">
                                <span class="custom-control-label">Ubuntu Font</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="font" value="font-raleway">
                                <span class="custom-control-label">Raleway Google Font</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="font" value="font-IBMplex">
                                <span class="custom-control-label">IBM Plex Google Font</span>
                            </label>
                        </li>
                    </ul>

                    <ul class="list-unstyled mt-3">
                        <li class="d-flex align-items-center mb-2">
                            <label class="toggle-switch theme-switch">
                                <input type="checkbox">
                                <span class="toggle-switch-slider"></span>
                            </label>
                            <span class="ml-3">Enable Dark Mode!</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <label class="toggle-switch theme-rtl">
                                <input type="checkbox">
                                <span class="toggle-switch-slider"></span>
                            </label>
                            <span class="ml-3">Enable RTL Mode!</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <label class="toggle-switch theme-high-contrast">
                                <input type="checkbox">
                                <span class="toggle-switch-slider"></span>
                            </label>
                            <span class="ml-3">Enable High Contrast Mode!</span>
                        </li>
                    </ul>                    
                    <hr>
                </div>   
            </div>          
        </div>
    </div>

    <div class="right_icon_bar">
        <ul>
            <li><a href="app-inbox.html"><i class="fa fa-envelope"></i></a></li>
            <li><a href="app-chat.html"><i class="fa fa-comments"></i></a></li>
            <li><a href="app-calendar.html"><i class="fa fa-calendar"></i></a></li>
            <li><a href="file-dashboard.html"><i class="fa fa-folder"></i></a></li>
            <li><a href="app-contact.html"><i class="fa fa-id-card"></i></a></li>
            <li><a href="blog-list.html"><i class="fa fa-globe"></i></a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li>
            <li><a href="javascript:void(0);" class="right_icon_btn"><i class="fa fa-angle-right"></i></a></li>
        </ul>
    </div>
    
    @yield('content')


    @php
        forgetStudentCourseIdAfterDelay();
    @endphp

</div>

<!-- Javascript -->
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>    
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<!-- page vendor js file -->
<script src="{{ asset('assets/bundles/apexcharts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> 

<!-- page js file -->
<script src="{{ asset('assets/js/university/index.js') }}"></script>

<!-- page js file -->
<script src="{{ asset('assets/js/pages/charts/c3.js') }}"></script>

<script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>

<script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('assets/vendor/jquery-validation/jquery.validate.js') }}"></script> 
<script src="{{ asset('assets/vendor/jquery-steps/jquery.steps.js') }}"></script> 
<script src="{{ asset('assets/js/pages/forms/form-wizard.js') }}"></script>
<!-- Javascript -->

<script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script> <!-- Morris Plugin Js --> 

<!-- page js file -->
<script src="{{ asset('assets/js/pages/charts/morris.js') }}"></script>

<!-- page vendor js file -->
<script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/js/index.js') }}"></script>

<script src="{{ asset('assets/js/pages/charts/apex.js') }}"></script>

<script src="{{ asset('assets/vendor/nestable/jquery.nestable.js') }}"></script> <!-- Jquery Nestable -->
<script src="{{ asset('assets/bundles/c3.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script> <!-- Jquery Knob-->

<!-- page js file -->
<script src="{{ asset('assets/js/pages/ui/sortable-nestable.js') }}"></script>
<script src="{{ asset('assets/js/index6.js') }}"></script>


<!-- page js file -->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/ui/dialogs.js') }}"></script>

<script src="{{ asset('assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>  
<script src="{{ asset('assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>  
<script src="{{ asset('assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>  
<script src="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<script src="{{ asset('assets/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>  
<script src="{{ asset('assets/vendor/nouislider/nouislider.js') }}"></script>  
<script src="{{ asset('assets/bundles/fullcalendarscripts.bundle.js') }}"></script><!--/ calender javascripts --> 
<script src="{{ asset('assets/vendor/fullcalendar/fullcalendar.js') }}"></script><!--/ calender javascripts --> 
<script src="{{ asset('assets/js/pages/calendar.js') }}"></script>

    

@yield('scripts')

</body>
</html>