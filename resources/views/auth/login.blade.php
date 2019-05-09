@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><img style="width: 50px; height: 50px;" src="./img/favicon.png" alt="lead logo">
        <b>LEAD</b>System</a>
    </div>
    <!-- /.login-logo -->

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
    
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group has-feedback">
                <input id="username" type="text" placeholder="Username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('email') }}" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                
            </div>
            <div class="form-group has-feedback">
                <input id="password" type="password" placeholder="Password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row float-right">
                <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </form>
    </div>

</div>
@endsection


