@extends('layoutmodule::layouts.main')

@section('title')
تغيير كلمة المرور
@endsection

@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="icon-key4"></i>
                تغيير كلمة المرور
            </h3>
            {{-- <a href="admin.html">admins / Add Admin</a> --}}
        </div>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div class="row">
                            <div class="col-5">
                                <h2> Admin Information</h2>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-12 col-12">

                                <form class="card-form side-form" method="POST"
                                    action='{{ route("user.updatePassword") }}'>
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label class="control-label" for="old_password">كلمة المرور القديمة</label>
                                            <div class="form-group">
                                                <input type="password" id="old_password" name="old_password"
                                                    class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label class="control-label" for="password">كلمة المرور الجديدة</label>
                                            <div class="form-group">
                                                <input type="password" id="password" name="password"
                                                    class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label class="control-label" for="password_confirmation">تأكيد كلمة المرور
                                                الجديدة</label>
                                            <div class="form-group">
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" class="form-control" value="">
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


@push('scripts')
<script type="text/javascript"
    src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/init-tinymce.js') }}"></script>
@endpush


@endsection
