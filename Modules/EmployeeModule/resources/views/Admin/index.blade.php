@extends('layoutmodule::layouts.layout_main')

@section('title')
    الموظفون
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-users"></i>
                    &nbsp;
                    الموظفون
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-12" style="text-align: left;">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.employees.create') }}"
                                        role="button">تسجيل موظف
                                        جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم الموظف</th>
                                            <th>الوظيفة</th>
                                            <th>القسم</th>
                                            <th>الفرع</th>
                                            <th>الراتب الأساسي</th>
                                            <th>أيام العمل الشهرية</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($employees->count())
                                            @foreach ($employees as $employee)
                                                <tr>
                                                    <td class="strong">
                                                        <a
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.show', $employee->id) }}">
                                                            {{ $employee->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $employee->job }}</td>
                                                    <td>{{ $employee->department ? $employee->department->name : '-' }}</td>
                                                    <td>{{ $employee->branch ? $employee->branch->name : '-' }}</td>
                                                    <td>{{ number_format($employee->basic_salary, 2) }}</td>
                                                    <td>{{ $employee->monthly_working_days }}</td>
                                                    <td>
                                                        <a class="btn btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.show', $employee->id) }}"
                                                            role="button">عرض</a>

                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.edit', $employee->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.employees.destroy', $employee->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الموظف ؟')">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">لا يوجد موظفون</td>
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
