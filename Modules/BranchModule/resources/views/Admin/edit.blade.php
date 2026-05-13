@extends('layoutmodule::layouts.layout_main')

@section('title')
    تعديل الفرع
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-edit"></i>
                    تعديل الفرع
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
                                        action="{{ route(Auth::getDefaultDriver() . '.branches.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="id" value="{{ $branch->id }}">

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">اسم الفرع</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name', $branch->name) }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="type">نوع الفرع</label>
                                                <div class="form-group">
                                                    <select class="form-control @error('type') is-invalid @enderror"
                                                        id="type" name="type">
                                                        <option value="">اختر نوع الفرع</option>
                                                        <option value="normal" {{ old('type', $branch->type) == 'normal' ? 'selected' : '' }}>فرع عام</option>
                                                        <option value="training" {{ old('type', $branch->type) == 'training' ? 'selected' : '' }}>معهد تدريب</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="address">العنوان</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        id="address" name="address" value="{{ old('address', $branch->address) }}">
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.branches.index') }}"
                                                class="btn btn-secondary">إلغاء</a>
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
