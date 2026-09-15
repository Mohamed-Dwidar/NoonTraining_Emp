@extends('layoutmodule::layouts.layout_main')

@section('title')
    العمولات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-percent"></i>
                    &nbsp;
                    العمولات
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
                                        href="{{ route('admin.commissions.create') }}"
                                        role="button">إضافة عمولة جديدة</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم العمولة</th>
                                            <th>النوع</th>
                                            <th>القيمة</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($commissions as $commission)
                                            <tr>
                                                <td class="strong">{{ $commission->name }}</td>
                                                <td>{{ $commission->type == 'fixed' ? 'قيمة ثابتة' : 'نسبة مئوية' }}</td>
                                                <td>{{ $commission->value }}{{ $commission->type == 'percentage' ? ' %' : ' ر.س' }}</td>
                                                <td>
                                                    <a class="btn btn-warning"
                                                        href="{{ route('admin.commissions.edit', $commission->id) }}"
                                                        role="button">تعديل</a>

                                                    <form
                                                        action="{{ route('admin.commissions.destroy', $commission->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('هل انت متأكد انك تريد حذف هذه العمولة ؟')">حذف</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">لا توجد عمولات</td>
                                            </tr>
                                        @endforelse
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
