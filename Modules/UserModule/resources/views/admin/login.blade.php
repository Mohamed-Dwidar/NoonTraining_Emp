
@extends('layoutmodule::users.login')

@section('title')
تسجيل دخول |المدراء
@endsection

@section('page-title')
تسجيل دخول |المدراء
@endsection

@section('content') 

<div class="card card-login border-grey border-lighten-3 m-0">
    <div class="card-header no-border">
        <div class="card-title text-xs-center">
            <div class="p-1">
                <a href="{{url('/')}}">
                    <img alt="branding logo" src="{{ url('assets/images/logo.png') }}"
                        data-expand="{{ url('assets/images/logo.png') }}"
                        data-collapse="{{ url('assets/images/logo.png') }}" class="brand-logo" style="height: 40px">
                </a>
            </div>
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
         <span>تسجيل دخول المدراء</span>
        </h6>
    </div>
    <div class="card-body collapse in">
        <div class="card-block">
                <form method="POST" action="{{ route('user.loginpost') }}" style="direction: rtl"> 
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-warning mb-2" role="alert">
                        <strong>Error!</strong>
                        {{$errors->first()}}
                    </div>
                    @endif
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input type="text" class="form-control form-control-lg input-lg" name="email" id="email" style="padding-right:40px"
                            value="{{ old('email') }}" placeholder="البريد الإلكتروني" required>
                        <div class="form-control-position">
                            <i class="icon-envelope-o"></i>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </fieldset>
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" class="form-control form-control-lg input-lg" id="user-password" style="padding-right:40px"
                            name="password" placeholder="كلمه المرور" required>
                        <div class="form-control-position">
                            <i class="icon-key3"></i>
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