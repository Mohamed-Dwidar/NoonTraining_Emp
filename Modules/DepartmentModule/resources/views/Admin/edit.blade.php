@extends('layoutmodule::layouts.layout_main')

@section('title')
    تعديل القسم
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-edit"></i>
                    تعديل القسم
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
                                        action="{{ route(Auth::getDefaultDriver() . '.departments.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="id" value="{{ $department->id }}">

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">اسم القسم</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name', $department->name) }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="branch_id">الفرع</label>
                                                <div class="form-group">
                                                    <select class="form-control @error('branch_id') is-invalid @enderror"
                                                        id="branch_id" name="branch_id">
                                                        <option value="">اختر الفرع</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" {{ old('branch_id', $department->branch_id) == $branch->id ? 'selected' : '' }}>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('branch_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.departments.index') }}"
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
