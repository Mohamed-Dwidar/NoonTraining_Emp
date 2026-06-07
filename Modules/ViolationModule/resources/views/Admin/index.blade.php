@extends('layoutmodule::layouts.layout_main')

@section('title') أنواع المخالفات @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <div class="d-flex align-items-center justify-content-between">
            <h3><i class="fa fa-exclamation-triangle"></i> &nbsp; أنواع المخالفات</h3>
            <a href="{{ route('admin.violations.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> &nbsp; إضافة مخالفة
            </a>
        </div>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-body p-0">
            @if($violations->isEmpty())
                <p class="text-center py-4 mt-2 text-muted">لا توجد أنواع مخالفات مضافة</p>
            @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr class="head">
                            <th>#</th>
                            <th>اسم المخالفة</th>
                            <th>الوصف</th>
                            <th>الخصومات حسب التكرار</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($violations as $i => $violation)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $violation->name }}</strong></td>
                            <td style="max-width:200px; white-space:normal;">
                                {{ $violation->description ?? '—' }}
                            </td>
                            <td>
                                @foreach($violation->repeats as $repeat)
                                    <span class="badge badge-secondary mr-1">
                                        المرة {{ $repeat->repeat_number }}{{ $loop->last && $repeat->repeat_number >= 5 ? '+' : '' }}:
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
                                      class="d-inline"
                                      onsubmit="return confirm('هل تريد حذف هذه المخالفة؟')">
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
