@extends('layoutmodule::layouts.layout_main')

@section('title')
    الأقسام
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-building"></i>
                    &nbsp;
                    الأقسام
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
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.departments.create') }}" role="button">إنشاء قسم جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم القسم</th>
                                            <th>الفرع</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($departments->count())
                                            @foreach($departments as $department)
                                                <tr>
                                                    <td class="strong">{{ $department->name }}</td>
                                                    <td>{{ $department->branch ? $department->branch->name : 'لا يوجد فرع' }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.departments.edit', $department->id) }}"
                                                            role="button">تعديل</a>

                                                        <form action="{{ route(Auth::getDefaultDriver() . '.departments.destroy', $department->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا القسم ؟')">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="3" class="text-center">لا توجد أقسام</td></tr>
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
