@extends('layoutmodule::layouts.layout_main')

@section('title')
    لائحة العمل الداخلية
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-building"></i>
                    &nbsp;
                    لائحة العمل الداخلية
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>الفرع</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($branches->count())
                                            @foreach ($branches as $branch)
                                                <tr>
                                                    <td class="strong">{{ $branch->name }}</td>
                                                    <td>
                                                        <a class="btn btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.branches.work-regulations', $branch->id) }}"
                                                            role="button">
                                                            <i class="fa fa-file-text-o"></i> عرض لائحة العمل الداخلية
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">لا توجد فروع</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
