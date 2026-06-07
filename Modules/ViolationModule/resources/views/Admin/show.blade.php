@extends('layoutmodule::layouts.layout_main')

@section('title')
    بيانات الموظف
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-user"></i>
                    &nbsp;
                    بيانات الموظف
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $violation->name }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="w-25">اسم الموظف</th>
                                        <td>{{ $violation->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>الوظيفة</th>
                                        <td>{{ $violation->job }}</td>
                                    </tr>
                                    <tr>
                                        <th>القسم</th>
                                        <td>{{ $violation->department ? $violation->department->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الفرع</th>
                                        <td>{{ $violation->branch ? $violation->branch->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الراتب الأساسي</th>
                                        <td>{{ number_format($violation->basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>أيام العمل الشهرية</th>
                                        <td>{{ $violation->monthly_working_days }} يوم</td>
                                    </tr>
                                    <tr>
                                        <th>ساعات العمل اليومية</th>
                                        <td>{{ $violation->daily_working_hours }} ساعة</td>
                                    </tr>
                                    <tr>
                                        <th>إجمالي ساعات العمل الشهرية</th>
                                        <td>{{ $violation->total_working_hours }} ساعة</td>
                                    </tr>
                                    <tr>
                                        <th>الراتب اليومي</th>
                                        <td>{{ number_format($violation->daily_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>سعر الساعة</th>
                                        <td>{{ number_format($violation->hourly_salary, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route(Auth::getDefaultDriver() . '.violations.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i> رجوع
                            </a>
                            <a href="{{ route(Auth::getDefaultDriver() . '.violations.edit', $violation->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
