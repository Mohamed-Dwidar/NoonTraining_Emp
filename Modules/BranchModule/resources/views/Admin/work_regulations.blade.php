@extends('layoutmodule::layouts.layout_main')

@section('title')
    لائحة العمل - {{ $branch->name }}
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between w-100">
                <h3><i class="fa fa-file-text-o"></i> &nbsp; لائحة العمل — {{ $branch->name }}</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <form method="POST" action="{{ route('admin.branches.work-regulations.update', $branch->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-1 col-1"></div>
                                <div class="col-lg-8 col-sm-12 col-xs-12 col-8">
                                    <textarea class="form-control tinymce" id="work_regulations" name="work_regulations" rows="5">{!! $branch->work_regulations !!}</textarea>
                                </div>


                                <div class="col-lg-1 col-1"></div>
                                <div class="col-lg-5 col-5">
                                    <a href="{{ route(Auth::getDefaultDriver() . '.branches.index') }}"
                                        class="btn btn-secondary">عودة</a>
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript"
        src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/init-tinymce.js') }}"></script>
@endpush
