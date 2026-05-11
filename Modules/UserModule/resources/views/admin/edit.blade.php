@extends('layoutmodule::layouts.main')

@section('title')
    تعديل مستخدم
@endsection

@section('content')
<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-user"></i> تعديل مستخدم</h3>
        </div>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <form class="card-form side-form" method="POST"
                                    action="{{ route(Auth::getDefaultDriver() . '.user.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ old('id', $user->id) }}">

                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label for="name">الاسم</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name', $user->name) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label for="email">البريد الإلكتروني</label>
                                            <div class="form-group">
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email', $user->email) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label for="password">كلمة المرور
                                                <small class="red"> ( اتركها فارغة إذا لم يتم التغيير )</small>
                                            </label>
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                        </div>
                                    </div>



<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
        <label><b>الصلاحيات</b></label>
        <div class="form-group">
            <div class="form-check">
                @foreach ($allPermissions as $perm)
                    <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                        <input type="checkbox" name="permissions[]" id="{{ $perm->name }}"
                            value="{{ $perm->name }}"
                            {{ in_array($perm->name, $userPermissions) ? 'checked' : '' }}>
                        <label for="{{ $perm->name }}">{{ $perm->label ?? $perm->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-1 col-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
