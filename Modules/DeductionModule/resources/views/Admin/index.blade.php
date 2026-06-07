@extends('layoutmodule::layouts.layout_main')

@section('title')
    الجزاءات والخصومات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <h3><i class="fa fa-minus-circle"></i> &nbsp; الجزاءات والخصومات</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        {{-- ─── Filter bar ─── --}}
        <div class="card mb-2">
            <div class="card-body py-2 px-2">
                <form method="GET" action="{{ route('admin.deductions.index') }}" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الشهر</strong></label>
                            <input type="month" name="month" class="form-control" value="{{ $month }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الفرع</strong></label>
                            <select name="branch_id" id="branchSelect" class="form-control">
                                <option value="">-- كل الفروع --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>القسم</strong></label>
                            <select name="department_id" id="deptSelect" class="form-control">
                                <option value="">-- كل الأقسام --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="d-block mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> عرض
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Deductions table ─── --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12" style="text-align: left;">
                        <a class="btn btn-success round btn-min-width mr-1 mb-1"
                            href="{{ route('admin.deductions.create', ['month' => $month]) }}" role="button">تسجيل خصم
                            جديد</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($deductions->isEmpty())
                    <p class="text-center py-4 mt-2 text-muted">لا توجد خصومات لهذا الشهر</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>#</th>
                                    <th>الموظف</th>
                                    <th>الشهر</th>
                                    <th>النوع</th>
                                    <th>المبلغ</th>
                                    <th>السبب / المخالفة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deductions as $i => $deduction)
                                    @php $emp = $deduction->employee; @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="strong">{{ $emp->name ?? '—' }}</div>
                                            <small class="text-muted">
                                                {{ $emp->branch->name ?? '' }}
                                                @if ($emp->department)
                                                    &nbsp;/&nbsp; {{ $emp->department->name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>{{ $deduction->month }}</td>
                                        <td>
                                            @if ($deduction->type === 'violation')
                                                <span class="badge badge-danger">
                                                    <i class="fa fa-exclamation-triangle"></i> مخالفة
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-pencil"></i> مخصص
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ number_format($deduction->amount, 2) }} ر.س
                                                @if ($deduction->violation_repeat_number)
                                                    <br><small class="badge badge-info-red mr-1 px-1">المرة
                                                        {{ $deduction->violation_repeat_number ?? 1 }}</small>
                                                @endif
                                            </span>

                                        </td>
                                        <td style="max-width:220px; white-space:normal;">
                                            @if ($deduction->type === 'violation' && $deduction->violation)
                                                <strong>{{ $deduction->violation->name }}</strong>

                                                @if ($deduction->reason)
                                                    <br><small class="text-muted">{{ $deduction->reason }}</small>
                                                @endif
                                            @else
                                                {{ $deduction->reason ?? '—' }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deductions.edit', $deduction->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.deductions.destroy', $deduction->id) }}"
                                                class="d-inline" onsubmit="return confirm('هل تريد حذف هذه الخصم؟')">
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

@push('scripts')
    <script>
        (function() {
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($g) => $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));

            var branchSelect = document.getElementById('branchSelect');
            var deptSelect = document.getElementById('deptSelect');
            var selectedDept = {{ $deptId ? (int) $deptId : 'null' }};

            function populateDepts(branchId) {
                var opts = '<option value="">-- كل الأقسام --</option>';
                var list = deptsByBranch[branchId] || [];
                list.forEach(function(d) {
                    var sel = (selectedDept && d.id === selectedDept) ? ' selected' : '';
                    opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
                });
                deptSelect.innerHTML = opts;
            }

            branchSelect.addEventListener('change', function() {
                selectedDept = null;
                populateDepts(this.value);
            });

            if (branchSelect.value) populateDepts(branchSelect.value);
        })();
    </script>
@endpush
