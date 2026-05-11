@extends('layoutmodule::layouts.main')

@section('title')
    اضافه مستخدم جديد
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-user"></i>
                    اضافه مستخدم جديد
                </h3>
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
                                        action='{{ route('admin.user.store') }}' enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">الاسم</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="email">البريد الإلكتروني</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="email" name="email"
                                                        value="{{ old('email') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 12 col-sm-12 col-xs-12 col-6">
                                                <label for="password">كلمة المرور</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="password"
                                                        name="password" value="{{ old('password') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                                <label><b>الصلاحيات</b></label>
                                                @php
                                                    $permissions = \Modules\PermissionModule\Entities\Permission::all();
                                                @endphp

                                                <div class="form-group">
                                                    <div class="form-check row">
                                                        @foreach ($permissions as $permission)
                                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                                <input type="checkbox" name="permissions[]"
                                                                    id="permission_{{ $permission->id }}"
                                                                    value="{{ $permission->id }}"
                                                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                <label
                                                                    for="permission_{{ $permission->id }}">{{ $permission->label }}</label>
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
