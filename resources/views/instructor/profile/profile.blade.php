@extends('instructor.layouts.app')
@section('content')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>User Profile</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="fa fa-dashboard"></i></a></li>                            
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">User Profile</li>
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

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="body">
                        <ul class="nav nav-tabs">                                
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Settings">Profile</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#billings">Change Password</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">

                        <div class="tab-pane active" id="Settings">

                            <form action="{{ route('instructor.update.profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="body">
                                    <h6>Profile Photo</h6>
                                    <div class="media">
                                        <div class="media-left m-r-15">
                                            @if(auth()->user()->img )
                                                <img src="{{ asset('storage/'.auth()->user()->img) }}" class="user-photo media-object" alt="User" id="userImage" style="width: 140px;
                                                height: 140px;
                                                border: 1px solid #eee;
                                                border-radius: 20px;">
                                            @else
                                                <img src="{{ asset('assets/images/user.png') }}" class="user-photo media-object" alt="User" id="userImage" style="width: 140px; height: 140px; border: 1px solid #eee; border-radius: 20px;">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <br> <em>Image should be at least 140x140.</em></p>
                                            <button type="button" class="btn btn-default" onclick="triggerFileInput()">Change Photo</button>
                                            <input type="file" name="img" id="fileInput" class="sr-only" onchange="displaySelectedFile()">
                                          
                                            <script>
                                                function triggerFileInput() {
                                                    // Trigger the click event on the file input
                                                    document.getElementById('fileInput').click();
                                                }
                                    
                                                function displaySelectedFile() {
                                                    // Display the selected file name
                                                    var fileInput = document.getElementById('fileInput');
                                                    var userImage = document.getElementById('userImage');
                                    
                                                    if (fileInput.files && fileInput.files[0]) {
                                                        var reader = new FileReader();
                                    
                                                        reader.onload = function (e) {
                                                            // Set the src attribute of the userImage
                                                            userImage.src = e.target.result;
                                                        };
                                    
                                                        // Read the selected file as a data URL
                                                        reader.readAsDataURL(fileInput.files[0]);
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-md-6">
                                            <div class="form-group">   
                                                <label>Full Name</label>                                             
                                                <input type="text" required name="name" value="{{ auth()->user()->name }}" class="form-control" placeholder="Full Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">   
                                                <label>Email</label>                                             
                                                <input type="email" required name="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">   
                                                <label>Phone</label>                                             
                                                <input type="number" readonly name="mob" value="{{ auth()->user()->mob ?? '' }}" class="form-control" placeholder="Enter Contact">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4 text-center">
                                            <button type="submit" class="btn btn-primary">UPDATE</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                        <div class="tab-pane" id="billings">
                           
                            <div class="body">
                                <form action="{{ route('instructor.update.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="form-group">   
                                                <label>Current Password</label>                                             
                                                <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                                                @if(session('error'))
                                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">   
                                                <label>New Password</label>                                             
                                                <input type="password" class="form-control" name="password"  placeholder="New Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">   
                                                <label>Confirm Password</label>                                             
                                                <input type="password" class="form-control" name="password_confirmation"  placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4 text-center">
                                            <button type="submit" class="btn btn-primary">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection