@extends('layoutmodule::layouts.main')

@section('title')
تعديل موظف
@endsection

@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                تعديل موظف :: {{$admin->name}}
            </h3>
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
                                    action='{{ route("admin.admins.update") }}' enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ old('id', $admin->id) }}">

                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label for="name">اسم الموظف</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{old('name' ,$admin->name)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label for="email">البريد الألكتروني</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="email" name="email"
                                                    value="{{old('email' ,$admin->email)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label for="password">كلمة المرور
                                                &nbsp; &nbsp;
                                                <small style="color: red">اتركها فارغه في حاله عدم الحاجه للتغير</small>
                                            </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="password" name="password"
                                                    value="{{old('password')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 20px">
                                        <div class="col-lg-12 col-sm-12 col-xs-12 col-12">
                                            <label>الصلاحيات</label>
                                            <div class="form-group">
                                                @if(!empty($privileges))
                                                <?php
                                                $saved_privileges_ids = $admin->privileges->pluck('privilege_id')->toArray();
                                                //print_r($saved_videos_ids);
                                            ?>
                                                @foreach ($privileges as $privilege)
                                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                                    <label class="display-inline-block custom-control custom-checkbox">
                                                        <input type="checkbox" name="privileges[]"
                                                            class="custom-control-input" value="{{$privilege->id}}"
                                                            @if(in_array($privilege->id,
                                                        old('privileges',$saved_privileges_ids)))
                                                        checked @endif >
                                                        <span class="custom-control-indicator"></span>
                                                        <span
                                                            class="custom-control-description ml-0">{{$privilege->label}}</span>
                                                    </label>
                                                </div>
                                                @endforeach
                                                @endif
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
