@extends('layoutmodule::layouts.layout_main')

@section('title')
    تقرير المرتبات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <h3><i class="fa fa-calendar-check-o"></i> &nbsp; تقرير المرتبات</h3>
        </div>

        @include('layoutmodule::layouts.flash')

        {{-- ─── Filter bar ─── --}}
        <div class="card mb-2">
            <div class="card-body py-2 px-2">
                <form method="GET" action="{{ route('admin.payrolls.index') }}" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الشهر</strong></label>
                            <input type="month" name="month" class="form-control" value="{{ $month }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الفرع</strong></label>
                            <select name="branch_id" id="branchSelect" class="form-control" required>
                                <option value="">-- اختر الفرع --</option>
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
                        <div class="col-md-1">
                            <label class="d-block mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @if ($branchId)
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input type="text" id="empSearch" class="form-control" placeholder="بحث باسم الموظف...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($payrolls->isEmpty())
                        <p class="text-center py-4 mt-2">لا يوجد موظفون في هذا الفرع</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0" id="payrollTable">
                                <thead>
                                    <tr class="head">
                                        <th>اسم الموظف</th>
                                        <th>الوظيفة</th>
                                        <th>أيام الحضور</th>
                                        <th>أيام الغياب</th>
                                        <th>المرتب الاساسي</th>
                                        <th>الخصومات</th>
                                        <th>المكافات</th>
                                        <th>عمولة الطلاب</th>
                                        <th>المرتب النهائي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payrolls as $payroll)
                                        @php $emp = $payroll->employee; @endphp
                                        <tr class="emp-row" data-name="{{ mb_strtolower($emp->name ?? '') }}">
                                            <td>
                                                <div class="strong">{{ $emp->name ?? '—' }}</div>
                                                <small class="text-muted">
                                                    {{ $emp->branch->name ?? '' }}
                                                    @if ($emp->department)
                                                        &nbsp;/&nbsp; {{ $emp->department->name }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $emp->job ?? '—' }}</td>
                                            <td>{{ $payroll->days_present }}</td>
                                            <td>{{ $payroll->days_absent }}</td>
                                            <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                                            <td>{{ number_format($payroll->deductions, 2) }}</td>
                                            <td>{{ number_format($payroll->bonuses, 2) }}</td>
                                            <td>{{ number_format($payroll->students_commission, 2) }}</td>
                                            <td>{{ number_format($payroll->total_salary, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $payrolls->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // ── Departments by branch ──────────────────────────────────────────────
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($group) => $group->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));

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
                deptSelect.disabled = list.length === 0;
            }

            branchSelect.addEventListener('change', function() {
                selectedDept = null; // reset dept on branch change
                populateDepts(this.value);
            });

            // Populate on initial load if a branch is already selected
            if (branchSelect.value) {
                populateDepts(branchSelect.value);
            }

            var searchInput = document.getElementById('empSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var q = this.value.toLowerCase().trim();
                    document.querySelectorAll('.emp-row').forEach(function(row) {
                        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
                    });
                });
            }
        })();
    </script>
@endpush
