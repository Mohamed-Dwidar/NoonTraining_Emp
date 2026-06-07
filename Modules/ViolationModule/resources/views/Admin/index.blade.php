@extends('layoutmodule::layouts.layout_main')

@section('title')
    أنواع المخالفات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <h3><i class="fa fa-exclamation-triangle"></i> &nbsp; أنواع المخالفات</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12" style="text-align: left;">
                        <a class="btn btn-success round btn-min-width mr-1 mb-1"
                            href="{{ route(Auth::getDefaultDriver() . '.violations.create') }}" role="button">تسجيل نوع
                            مخالفة جديد</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($violations->isEmpty())
                    <p class="text-center py-4 mt-2 text-muted">لا توجد أنواع مخالفات مضافة</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>اسم المخالفة</th>
                                    <th>الوصف</th>
                                    <th>الخصومات حسب التكرار</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($violations as $i => $violation)
                                    <tr>
                                        <td><strong>{{ $violation->name }}</strong></td>
                                        <td style="max-width:200px; white-space:normal;">
                                            {{ $violation->description ?? '—' }}
                                        </td>
                                        <td>
                                            @foreach ($violation->repeats as $repeat)
                                                <span class="badge badge-secondary mr-1">
                                                    المرة
                                                    {{ $repeat->repeat_number }}{{ $loop->last && $repeat->repeat_number >= 5 ? '+' : '' }}:
                                                    {{ number_format($repeat->deduction_amount, 0) }} ر.س
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.violations.edit', $violation->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.violations.destroy', $violation->id) }}"
                                                class="d-inline" onsubmit="return confirm('هل تريد حذف هذه المخالفة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
