@extends('layouts.app')
@section('content')
    {{-- <div class="main-wrapper">
        <div class="account-content" id="account-content"> --}}
            {{-- <a href="{{ route('form/job/list') }}" class="btn btn-primary apply-btn">Apply Job</a> --}}
           
                {{-- message --}}
                {!! Toastr::message() !!}
                <div class="lside-login">
                    <div class="img-main">
                        <div class="log-img">
                            <img src="{{ URL::to('assets/img/logo5.png') }}" alt="">
                        </div>
                        <div class="img-content">Build and nature a group of complementary and innovative businesses, whilst ensuring sustainability in value and wealth creation to all our stakeholders.
                        </div>
                    </div>
                </div>
                <div class="rside-login">
                    <div class="login-form">
                    <h1 class="login">Login</h1>
                    <h4 class="login-sub">Access to our dashboard</h4>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label>Enter your email here</label>
                                <input type="text" class="field form-control placeholder-text @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="james@gmail.com" >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Enter your Password</label>
                                    </div>
                                </div>
                                {{-- <div class="password-container">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••">
                                    <div class="eye-icon" onclick="togglePasswordVisibility(this)">
                                        <!-- Black and white eye icon -->
                                        &#128065;
                                    </div>
                                </div>
                                 --}}
                                <input type="password" class="field form-control @error('password') is-invalid @enderror" name="password" placeholder="• • • • • • • •">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label></label>
                                    </div>
                                    <div class="col-auto">
                                        <a class="text-muted " href="{{ route('forget-password') }}">
                                            Forgot password?
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn field" id="login" type="submit">Login</button>
                            </div>
                            <div class="account-footer">
                                <strong><p>Don't have an account yet? <a href="{{ route('register') }}">Register</a></p></strong>
                            </div>
                        </form>
                    </div>
                    
                </div>
                {{-- <script>
                function togglePasswordVisibility(icon) {
                    const passwordInput = icon.previousElementSibling;
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                }
                </script> --}}
                <!-- Account Logo -->
                {{-- <div class="account-logo">
                    <a href="index.html"><img src="{{ URL::to('assets/img/logo4.png') }}" alt="Soeng Souy"></a>
                </div> --}}
                <!-- /Account Logo -->
                {{-- <div class="account-box" id="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Login</h3>
                        <p class="account-subtitle">Access to our dashboard</p>
                        <!-- Account Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Password</label>
                                    </div>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label></label>
                                    </div>
                                    <div class="col-auto">
                                        <a class="text-muted" href="{{ route('forget-password') }}">
                                            Forgot password?
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Login</button>
                            </div>
                            <div class="account-footer">
                                <strong><p>Don't have an account yet? <a href="{{ route('register') }}">Register</a></p></strong>
                            </div>
                        </form>
                        <!-- /Account Form -->
                    </div>
                </div> --}}
{{--             
        </div>
    </div> --}}
@endsection
