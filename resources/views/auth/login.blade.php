<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Login - NIAIS ONSITE CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('loginassets/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('loginassets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('loginassets/fonts/flaticon/font/flaticon.css') }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('assets/myimages/favicon.png') }}" type="image/x-icon" >

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('loginassets/css/style.css') }}">

</head>
<body id="top">
<div class="page_loader"></div>

<!-- Login 30 start -->
<div class="login-30 tab-box">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 bg-img">
                <div class="informeson">
                    <div class="logo">
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('assets/myimages/logo-white.png') }}"  alt="logo">
                        </a>
                    </div>
                    <h2><span>NIAIS ONSITE CRM</span></h2>
                    <p style="text-align: justify !important">Welcome to our CRM platform designed with care for our valued Customer Service Representatives (CSRs)! Thank you for being a part of our mission to redefine excellence in customer service.</p>
                    <div class="btn-section">
                        {{-- <a href="login-30.html" class="btn-theme-3">Login</a>
                        <a href="register-30.html" class="btn-theme-2">Register</a> --}}
                    </div>
                </div>
                <div class="social-box">
                    <ul class="social-list clearfix">
                        {{-- <li><a href="#" class="facebook-bg"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" class="twitter-bg"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#" class="google-bg"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#" class="linkedin-bg"><i class="fa fa-linkedin"></i></a></li> --}}
                    </ul>
                </div>
                <div id="bg">
                    <canvas></canvas>
                    <canvas></canvas>
                    <canvas></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 form-section">
                <div class="login-inner-form">
                    <div class="details">
                        <div class="logo-2">
                            <a href="{{ route('login') }}">
                                <img src="{{ asset('loginassets/img/logos/logo-2.png') }}" alt="logo">
                            </a>
                        </div>
                        <h1 class="text-center">Welcome!</h1>
                        <h3 class="text-center">Sign Into Your Account!</h3>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="first_field" class="form-label float-start">Email address</label>
                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}" placeholder="Email Address" aria-label="Email Address">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group clearfix">
                                <label for="second_field" class="form-label float-start">Password</label>
                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="off" placeholder="Password" aria-label="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="checkbox form-group clearfix">
                                <div class="form-check float-start">
                                    <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="rememberme">
                                    <label class="form-check-label" for="rememberme">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <button type="submit" class="btn btn-lg btn-primary btn-theme"><span>Login</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login 30 end -->

<!-- External JS libraries -->
<script src="{{ asset('loginassets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('loginassets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('loginassets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('loginassets/js/app.js') }}"></script>
<!-- Custom JS Script -->

</body>

</html>
