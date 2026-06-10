@extends('layoutmodule::layouts.layout_main')

@section('title')
    تغيير كلمة المرور
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header mb-2">
            <h3><i class="fa fa-plus-circle"></i> &nbsp; تغيير كلمة المرور</h3>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-content">
                <form class="card-form side-form" method="POST" action="{{ route('employee.updatePassword') }}">
                    @csrf

                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="old_password">كلمة المرور الحالية</label>
                            <div class="form-group">
                                <input type="text" id="old_password" name="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror"
                                    autocomplete="current-password">
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="password">كلمة المرور الجديدة</label>
                            <div class="form-group">
                                <input type="text" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <div class="form-group">
                                <input type="text" id="password_confirmation" name="password_confirmation"
                                    class="form-control" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-1">
                        &nbsp;
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
