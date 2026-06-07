@extends('layoutmodule::layouts.layout_main')

@section('title')
    الفروع
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-building"></i>
                    &nbsp;
                    الفروع
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
                                        href="{{ route(Auth::getDefaultDriver() . '.branches.create') }}"
                                        role="button">تسجيل فرع
                                        جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم الفرع</th>
                                            <th>نوع الفرع</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($branches->count())
                                            @foreach ($branches as $branch)
                                                <tr>
                                                    <td class="strong">{{ $branch->name }}</td>
                                                    <td>{{ $branch->type == 'normal' ? 'فرع عام' : 'معهد تدريب' }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.branches.edit', $branch->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.branches.destroy', $branch->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الفرع ؟')">حذف</button>
                                                        </form>
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
