@extends('layoutmodule::layouts.layout_main')

@section('title') الحضور والإجازات @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-calendar-check-o"></i> &nbsp; الحضور والإجازات</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    {{-- ─── Filter bar ─── --}}
    <div class="card mb-2">
        <div class="card-body py-2 px-2">
            <form method="GET" action="{{ route('admin.attendances.index') }}" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="d-block mb-1"><strong>الشهر</strong></label>
                        <input type="month" name="month" class="form-control"
                               value="{{ $month }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block mb-1"><strong>الفرع</strong></label>
                        <select name="branch_id" id="branchSelect" class="form-control" required>
                            <option value="">-- اختر الفرع --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ $branchId == $branch->id ? 'selected' : '' }}>
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

    @if($branchId)
    {{-- ─── Attendance form ─── --}}
    <form method="POST" action="{{ route('admin.attendances.store') }}" id="attendanceForm">
        @csrf
        <input type="hidden" name="month"     value="{{ $month }}">
        <input type="hidden" name="branch_id" value="{{ $branchId }}">

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" id="empSearch" class="form-control"
                                   placeholder="بحث باسم الموظف...">
                        </div>
                    </div>
                    <div class="col-md-6 text-left">
                        <button type="button" class="btn btn-success"
                                onclick="confirmSave()">
                            <i class="fa fa-save"></i> &nbsp; حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if($attendances->isEmpty())
                    <p class="text-center py-4 mt-2">لا يوجد موظفون في هذا الفرع</p>
                @else
                <div class="table-responsive">
                    <table class="table mb-0" id="attendanceTable">
                        <thead>
                            <tr class="head">
                                <th>اسم الموظف</th>
                                <th>أيام العمل الشهرية</th>
                                <th>أيام الحضور</th>
                                <th>أيام الغياب</th>
                                <th>نسبة الحضور</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($attendances as $att)
                            @php $emp = $att->employee; @endphp
                            <tr class="emp-row" data-name="{{ mb_strtolower($emp->name ?? '') }}">
                                <td>
                                    <div class="strong">{{ $emp->name ?? '—' }}</div>
                                    <small class="text-muted">
                                        {{ $emp->branch->name ?? '' }}
                                        @if($emp->department) &nbsp;/&nbsp; {{ $emp->department->name }} @endif
                                    </small>
                                </td>
                                <td>
                                    <input type="number"
                                           class="form-control input-sm working-days"
                                           name="attendance[{{ $emp->id }}][monthly_working_days]"
                                           value="{{ $att->monthly_working_days }}"
                                           min="1" max="31"
                                           data-emp="{{ $emp->id }}"
                                           style="width:75px">
                                </td>
                                <td>
                                    <input type="number"
                                           class="form-control input-sm days-present"
                                           name="attendance[{{ $emp->id }}][days_present]"
                                           value="{{ $att->days_present }}"
                                           min="0"
                                           data-emp="{{ $emp->id }}"
                                           style="width:75px">
                                </td>
                                <td>
                                    <span class="days-absent-display" id="absent-{{ $emp->id }}">
                                        {{ $att->days_absent }}
                                    </span>
                                </td>
                                <td>
                                    <span class="rate-display" id="rate-{{ $emp->id }}">
                                        {{ $att->attendance_rate }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-badge {{ $att->status === 'غياب متكرر' ? 'badge-danger' : 'badge-success' }}"
                                          id="status-{{ $emp->id }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            @if(!$attendances->isEmpty())
            <div class="card-footer text-left">
                <button type="button" class="btn btn-success"
                        onclick="confirmSave()">
                    <i class="fa fa-save"></i> &nbsp; حفظ التغييرات
                </button>
            </div>
            @endif
        </div>
    </form>
    @endif

</div>

{{-- ─── Confirm modal ─── --}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="margin-top:180px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحفظ</h5>
            </div>
            <div class="modal-body text-center py-3">
                <i class="fa fa-question-circle fa-3x text-warning mb-2 d-block"></i>
                <p class="mb-0">هل أنت متأكد من حفظ بيانات الحضور لهذا الشهر؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success"
                        onclick="document.getElementById('attendanceForm').submit()">
                    <i class="fa fa-check"></i> نعم، احفظ
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ── Departments by branch ──────────────────────────────────────────────
    var deptsByBranch = @json(
        $departments->groupBy('branch_id')->map(fn($group) =>
            $group->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()
        )
    );

    var branchSelect = document.getElementById('branchSelect');
    var deptSelect   = document.getElementById('deptSelect');
    var selectedDept = {{ $deptId ? (int)$deptId : 'null' }};

    function populateDepts(branchId) {
        var opts = '<option value="">-- كل الأقسام --</option>';
        var list = deptsByBranch[branchId] || [];
        list.forEach(function (d) {
            var sel = (selectedDept && d.id === selectedDept) ? ' selected' : '';
            opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
        });
        deptSelect.innerHTML = opts;
        deptSelect.disabled  = list.length === 0;
    }

    branchSelect.addEventListener('change', function () {
        selectedDept = null; // reset dept on branch change
        populateDepts(this.value);
    });

    // Populate on initial load if a branch is already selected
    if (branchSelect.value) {
        populateDepts(branchSelect.value);
    }

    // ── Attendance calculation ─────────────────────────────────────────────
    var THRESHOLD = 3; // أيام الغياب >= 3 → غياب متكرر

    function recalc(empId) {
        var workingInput  = document.querySelector('.working-days[data-emp="' + empId + '"]');
        var presentInput  = document.querySelector('.days-present[data-emp="' + empId + '"]');
        var absentDisplay = document.getElementById('absent-' + empId);
        var rateDisplay   = document.getElementById('rate-'   + empId);
        var statusBadge   = document.getElementById('status-' + empId);

        if (!workingInput || !presentInput) return;

        var working = Math.max(1, parseInt(workingInput.value) || 1);
        var present = parseInt(presentInput.value) || 0;

        if (present > working) { present = working; presentInput.value = working; }
        if (present < 0)       { present = 0;       presentInput.value = 0; }

        var absent   = working - present;
        var rate     = ((present / working) * 100).toFixed(2);
        var repeated = absent >= THRESHOLD;

        absentDisplay.textContent = absent;
        rateDisplay.textContent   = rate + '%';
        statusBadge.textContent   = repeated ? 'غياب متكرر' : 'منتظم';
        statusBadge.className     = 'badge status-badge ' + (repeated ? 'badge-danger' : 'badge-success');
    }

    document.querySelectorAll('.working-days, .days-present').forEach(function (input) {
        input.addEventListener('input', function () { recalc(this.dataset.emp); });
    });

    var searchInput = document.getElementById('empSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('.emp-row').forEach(function (row) {
                row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
            });
        });
    }

    window.confirmSave = function () { $('#confirmModal').modal('show'); };
})();
</script>
@endpush
