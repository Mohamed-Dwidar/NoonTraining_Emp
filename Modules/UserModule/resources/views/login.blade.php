
@extends('layoutmodule::layouts.home_layout')

@section('title', 'تسجيل دخول الإدارة')

@section('content')
<div class="admin-login-container">
    <div class="admin-login-form">
        <div class="admin-login-header">
            <a href="{{ route('home') }}">
            <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="معهد نون للتدريب" class="admin-login-logo">
            </a>
            <h1 class="admin-login-title">تسجيل دخول المستخدمين</h1>
            {{-- <p class="admin-login-subtitle">مرحباً بك في نظام إدارة الاختبارات الإلكتروني</p> --}}
        </div>

        @if($errors->any() || session('error'))
        <div class="admin-alert alert-danger">
            @if(session('error'))
                {{ session('error') }}
            @else
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            @endif
        </div>
        @endif

        @isset($url)
            <form method="POST" action='{{ url("$url/login") }}' id="form-login">
        @else
            <form method="POST" action="{{ route('user.loginpost') }}" aria-label="{{ __('Login') }}">
        @endisset
            @csrf

            <div class="admin-form-group">
                <label for="email">البريد الإلكتروني</label>
                <div class="admin-input-wrapper">
                    <i class="admin-input-icon icon-head"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="admin-form-control @error('email') is-invalid @enderror"
                        placeholder="أدخل البريد الإلكتروني"
                        required
                    >
                </div>
                @if ($errors->has('email'))
                    <div class="admin-invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="admin-form-group">
                <label for="password">كلمة المرور</label>
                <div class="admin-input-wrapper">
                    <i class="admin-input-icon icon-key3"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="admin-form-control @error('password') is-invalid @enderror"
                        placeholder="أدخل كلمة المرور"
                        required
                    >
                </div>
                @if ($errors->has('password'))
                    <div class="admin-invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <button type="submit" class="admin-btn-primary">
                <i class="icon-unlock2"></i>
                دخول النظام
            </button>
        </form>
    </div>
</div>
@endsection








<?php /*

@extends('layoutmodule::admin.login')

@section('content')

<div class="card border-grey border-lighten-3 m-0">
    <div class="card-header no-border">
        <div class="card-title text-xs-center">
            <div class="p-1">
                <a href="{{url('/')}}">
                    <img src="{{url('/assets/images/logo.png')}}" alt="main logo" style="width: 100px;">
                </a>
            </div>
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
            <span>برنامج الاختبارات</span></h6>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
                <span>تسجيل الدخول للبرنامج</span></h6>
    </div>
    <div class="card-body collapse in">
        <div class="card-block">
            @isset($url)
            <form method="POST" action='{{ url("$url/login") }}' id="form-login">
                @else
                <form method="POST" action="{{ route('user.loginpost') }}" aria-adminel="{{ __('Login') }}">
                    @endisset
                    @csrf

                    @if ($errors->any())
                    <div class="alert alert-warning mb-2" role="alert">
                        <strong>خطـأ!</strong>
                        {{$errors->first()}}
                    </div>
                    @endif
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input type="text" class="form-control form-control-lg input-lg" name="email" id="email"
                            value="{{ old('email') }}" placeholder="البريد الألكتروني" required>
                        <div class="form-control-position">
                            <i class="icon-head"></i>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </fieldset>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" id="user-password"
                            name="password" placeholder="كلمة المرور" required>
                        <div class="form-control-position">
                            <i class="icon-key3"></i>
                        </div>
                    </fieldset>
                    <fieldset class="form-group row">
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                            {{-- <fieldset>
                                <input type="checkbox" id="remember-me" class="chk-remember">
                                <label for="remember-me"> Remember Me</label>
                            </fieldset> --}}
                        </div>
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right">
                            {{-- <a href="recover-password.html" class="card-link">Forgot Password?</a> --}}
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="icon-unlock2"></i>
                        {{ __('دخول') }}
                    </button>
                </form>
        </div>
    </div>
</div>
@endsection
*/ ?>
