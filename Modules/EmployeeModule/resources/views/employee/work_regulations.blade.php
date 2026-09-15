@extends('layoutmodule::layouts.layout_main')

@section('title')
    لائحة العمل
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between w-100">

                <h3><i class="fa fa-file-text-o"></i> &nbsp; لائحة العمل @if ($branch)
                        — {{ $branch->name }}
                    @endif
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-body">

                @if ($branch && $branch->work_regulations)
                    <div class="work-regulations-content m-3">
                        <div class="mb-3" style="text-align:left">
                            <a href="{{ route('employee.work_regulations.pdf') }}" class="btn btn-success">
                                <i class="fa fa-file-pdf-o"></i> تحميل PDF
                            </a>
                        </div>
                        {!! $branch->work_regulations !!}
                    </div>
                @else
                    <div class="work-regulations-content">
                        <br>
                        <p class="text-center text-muted mt-4" style="text-align: center">لا توجد لائحة عمل مضافة لفرعك
                            حالياً
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
