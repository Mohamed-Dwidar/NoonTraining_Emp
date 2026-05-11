@extends('layoutmodule::layouts.home_layout')

@section('title', 'تسجيل دخول الإدارة')

@section('content')
<div class="admin-login-container">
    <div class="admin-login-form">
        <div class="admin-login-header">
            <a href="{{ route('home') }}">
            <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="معهد نون للتدريب" class="admin-login-logo">
            </a>
            <h1 class="admin-login-title">تسجيل دخول الإدارة</h1>
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
            <form method="POST" action="{{ route('admin.loginpost') }}" aria-label="{{ __('Login') }}">
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
