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
                            <h4 class="card-title">{{ $leave->name }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="w-25">اسم الموظف</th>
                                        <td>{{ $leave->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>الوظيفة</th>
                                        <td>{{ $leave->job }}</td>
                                    </tr>
                                    <tr>
                                        <th>القسم</th>
                                        <td>{{ $leave->department ? $leave->department->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الفرع</th>
                                        <td>{{ $leave->branch ? $leave->branch->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الراتب الأساسي</th>
                                        <td>{{ number_format($leave->basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>أيام العمل الشهرية</th>
                                        <td>{{ $leave->monthly_working_days }} يوم</td>
                                    </tr>
                                    <tr>
                                        <th>ساعات العمل اليومية</th>
                                        <td>{{ $leave->daily_working_hours }} ساعة</td>
                                    </tr>
                                    <tr>
                                        <th>إجمالي ساعات العمل الشهرية</th>
                                        <td>{{ $leave->total_working_hours }} ساعة</td>
                                    </tr>
                                    <tr>
                                        <th>الراتب اليومي</th>
                                        <td>{{ number_format($leave->daily_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>سعر الساعة</th>
                                        <td>{{ number_format($leave->hourly_salary, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route(Auth::getDefaultDriver() . '.leaves.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i> رجوع
                            </a>
                            <a href="{{ route(Auth::getDefaultDriver() . '.leaves.edit', $leave->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
