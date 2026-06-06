@extends('layoutmodule::layouts.layout_main')

@section('title') تعديل إجازة @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-edit"></i> &nbsp; تعديل إجازة</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('admin.leaves.update', $leave->id) }}">
                @csrf

                <div class="row">
                    {{-- Month (controls date range) --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="monthPicker">الشهر</label>
                        <div class="form-group">
                            <input type="month" id="monthPicker" class="form-control"
                                   value="{{ old('month', $month) }}">
                            <small class="text-muted">يحدد نطاق التواريخ المسموح به أدناه</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Branch --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="branchSelect">الفرع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="branchSelect" name="branch_id" class="form-control">
                                <option value="">-- اختر الفرع --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id', $leave->employee?->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Department --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="deptSelect">القسم <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="deptSelect" name="department_id"
                                    class="form-control" disabled>
                                <option value="">-- اختر الفرع أولاً --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Employee --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="employee_id">الموظف <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="employee_id" name="employee_id"
                                    class="form-control @error('employee_id') is-invalid @enderror"
                                    disabled>
                                <option value="">-- اختر القسم أولاً --</option>
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Leave type --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="type">نوع الإجازة <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="type" name="type"
                                    class="form-control @error('type') is-invalid @enderror">
                                <option value="">-- اختر النوع --</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}" {{ old('type', $leave->type) == $t ? 'selected' : '' }}>
                                        {{ $t }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Start date --}}
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="start_date">تاريخ البداية <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="start_date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $leave->start_date->format('Y-m-d')) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- End date --}}
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="end_date">تاريخ النهاية <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="end_date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $leave->end_date->format('Y-m-d')) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Days (read-only display) --}}
                    <div class="col-lg-1 col-sm-12 col-6">
                        <label>عدد الأيام</label>
                        <div class="form-group">
                            <span id="daysDisplay" class="form-control text-center bg-light">{{ $leave->days }} يوم</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Reason --}}
                    <div class="col-lg-6 col-sm-12">
                        <label for="reason">السبب</label>
                        <div class="form-group">
                            <textarea id="reason" name="reason" rows="3"
                                      class="form-control @error('reason') is-invalid @enderror">{{ old('reason', $leave->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-1">
                    <a href="{{ route('admin.leaves.index', ['month' => $month]) }}"
                       class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var deptsByBranch = @json(
        $departments->groupBy('branch_id')->map(fn($g) =>
            $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()
        )
    );
    var empsByDept = @json(
        $employees->groupBy('department_id')->map(fn($g) =>
            $g->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()
        )
    );

    var branchSelect = document.getElementById('branchSelect');
    var deptSelect   = document.getElementById('deptSelect');
    var empSelect    = document.getElementById('employee_id');
    var monthPicker  = document.getElementById('monthPicker');
    var startInput   = document.getElementById('start_date');
    var endInput     = document.getElementById('end_date');
    var daysDisplay  = document.getElementById('daysDisplay');

    var initialBranchId = "{{ old('branch_id', $leave->employee?->branch_id) }}";
    var initialDeptId   = "{{ old('department_id', $leave->employee?->department_id) }}";
    var initialEmpId    = "{{ old('employee_id', $leave->employee_id) }}";

    // ── Populate departments ─────────────────────────────────────────────
    function populateDepts(branchId, selectedDeptId) {
        var list = deptsByBranch[branchId] || [];
        if (!branchId || list.length === 0) {
            deptSelect.innerHTML = '<option value="">-- اختر الفرع أولاً --</option>';
            deptSelect.disabled  = true;
            resetEmployees();
            return;
        }
        var opts = '<option value="">-- اختر القسم --</option>';
        list.forEach(function (d) {
            var sel = String(d.id) === String(selectedDeptId) ? ' selected' : '';
            opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
        });
        deptSelect.innerHTML = opts;
        deptSelect.disabled  = false;

        if (selectedDeptId) {
            populateEmployees(selectedDeptId, initialEmpId);
        } else {
            resetEmployees();
        }
    }

    // ── Populate employees ───────────────────────────────────────────────
    function populateEmployees(deptId, selectedEmpId) {
        var list = empsByDept[deptId] || [];
        if (!deptId || list.length === 0) {
            empSelect.innerHTML = '<option value="">لا يوجد موظفون في هذا القسم</option>';
            empSelect.disabled  = true;
            return;
        }
        var opts = '<option value="">-- اختر الموظف --</option>';
        list.forEach(function (e) {
            var sel = String(e.id) === String(selectedEmpId) ? ' selected' : '';
            opts += '<option value="' + e.id + '"' + sel + '>' + e.name + '</option>';
        });
        empSelect.innerHTML = opts;
        empSelect.disabled  = false;
    }

    function resetEmployees() {
        empSelect.innerHTML = '<option value="">-- اختر القسم أولاً --</option>';
        empSelect.disabled  = true;
    }

    // ── Events ───────────────────────────────────────────────────────────
    branchSelect.addEventListener('change', function () {
        populateDepts(this.value, '');
    });

    deptSelect.addEventListener('change', function () {
        populateEmployees(this.value, '');
    });

    // ── Initial load: restore saved/existing values ──────────────────────
    if (initialBranchId) {
        populateDepts(initialBranchId, initialDeptId);
    }

    // ── Date range constraint from selected month ────────────────────────
    function applyMonthBounds(monthVal) {
        if (!monthVal) return;
        var parts    = monthVal.split('-');
        var year     = parseInt(parts[0]);
        var month    = parseInt(parts[1]);
        var firstDay = monthVal + '-01';
        var lastDay  = new Date(year, month, 0).toISOString().slice(0, 10);

        startInput.min = firstDay;
        startInput.max = lastDay;
        endInput.min   = firstDay;
        endInput.max   = lastDay;

        if (startInput.value && (startInput.value < firstDay || startInput.value > lastDay)) {
            startInput.value = '';
        }
        if (endInput.value && (endInput.value < firstDay || endInput.value > lastDay)) {
            endInput.value = '';
        }
        updateDays();
    }

    monthPicker.addEventListener('change', function () { applyMonthBounds(this.value); });
    applyMonthBounds(monthPicker.value);

    // ── Auto-calculate days ──────────────────────────────────────────────
    function updateDays() {
        var s = startInput.value;
        var e = endInput.value;
        if (s && e && e >= s) {
            var diff = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
            daysDisplay.textContent = diff + ' يوم';
        } else {
            daysDisplay.textContent = '—';
        }
        if (s) endInput.min = s;
    }

    startInput.addEventListener('change', updateDays);
    endInput.addEventListener('change', updateDays);
    updateDays();
})();
</script>
@endpush
