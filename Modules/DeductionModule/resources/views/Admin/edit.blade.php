@extends('layoutmodule::layouts.layout_main')

@section('title')
    تعديل خصم
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <h3><i class="fa fa-edit"></i> &nbsp; تعديل خصم</h3>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-content">
                <form class="card-form side-form" method="POST"
                    action="{{ route('admin.deductions.update', $deduction->id) }}">
                    @csrf

                    @php
                        $currentType = old('type', $deduction->type ?? 'custom');
                    @endphp

                    {{-- Employee selection --}}
                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="branchSelect">الفرع <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <select id="branchSelect" name="branch_id" class="form-control">
                                    <option value="">-- اختر الفرع --</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $deduction->employee?->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="deptSelect">القسم <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <select id="deptSelect" name="department_id" class="form-control" disabled>
                                    <option value="">-- اختر الفرع أولاً --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="employee_id">الموظف <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <select id="employee_id" name="employee_id"
                                    class="form-control @error('employee_id') is-invalid @enderror" disabled>
                                    <option value="">-- اختر القسم أولاً --</option>
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Month --}}
                    <div class="row">
                        <div class="col-lg-3 col-sm-12 col-6">
                            <label for="month">الشهر <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <input type="month" id="month" name="month"
                                    class="form-control @error('month') is-invalid @enderror"
                                    value="{{ old('month', $deduction->month) }}">
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Type toggle --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <input type="hidden" name="type" id="typeInput" value="{{ $currentType }}">
                            <div class="btn-group" role="group">
                                <button type="button" id="btnCustom"
                                    class="btn {{ $currentType === 'custom' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                    <i class="fa fa-pencil"></i> &nbsp; مبلغ مخصص
                                </button>
                                <button type="button" id="btnViolation"
                                    class="btn {{ $currentType === 'violation' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    <i class="fa fa-exclamation-triangle"></i> &nbsp; مخالفة
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Violation section --}}
                    <div id="violationSection" style="{{ $currentType === 'violation' ? '' : 'display:none;' }}">
                        <div class="row">
                            <div class="col-lg-2 col-sm-12">
                                <label for="violation_id">المخالفة <span class="text-danger">*</span>
                                    <span id="repeatBadge" class="badge badge-info-red"
                                        style="font-size:0.85rem; padding:5px 12px; margin-bottom:8px; {{ $deduction->violation_repeat_number ? '' : 'display:none;' }}">
                                        المرة <span id="repeatNum">{{ $deduction->violation_repeat_number ?? 1 }}</span>
                                    </span>
                                </label>
                                <div class="form-group">
                                    <select id="violation_id" name="violation_id"
                                        class="form-control @error('violation_id') is-invalid @enderror">
                                        <option value="">-- اختر المخالفة --</option>
                                        @foreach ($violations as $v)
                                            <option value="{{ $v->id }}"
                                                {{ old('violation_id', $deduction->violation_id) == $v->id ? 'selected' : '' }}>
                                                {{ $v->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('violation_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reason --}}
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <label for="reason">السبب <span id="reasonStar" class="text-danger"
                                    style="{{ $currentType === 'violation' ? 'display:none;' : '' }}">*</span></label>
                            <div class="form-group">
                                <textarea id="reason" name="reason" rows="3" class="form-control @error('reason') is-invalid @enderror">{{ old('reason', $deduction->reason) }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 col-6">
                            <label for="amount">
                                <label for="amount">مبلغ الخصم (ر.س)
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="form-group">
                                    <input type="number" id="amount" name="amount" step="0.01" min="0"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $deduction->amount) }}">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                        </div>
                    </div>

                    <div class="col-12 mt-1">
                        <a href="{{ route('admin.deductions.index', ['month' => $month]) }}"
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
        (function() {
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($g) => $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));
            var empsByDept = @json($employees->groupBy('department_id')->map(fn($g) => $g->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()));

            var violationAmountUrl = "{{ route('admin.deductions.violation-amount') }}";
            var deductionId = {{ $deduction->id }};

            var branchSelect = document.getElementById('branchSelect');
            var deptSelect = document.getElementById('deptSelect');
            var empSelect = document.getElementById('employee_id');
            var monthInput = document.getElementById('month');
            var typeInput = document.getElementById('typeInput');
            var btnCustom = document.getElementById('btnCustom');
            var btnViolation = document.getElementById('btnViolation');
            var violationSection = document.getElementById('violationSection');
            var violationSelect = document.getElementById('violation_id');
            var repeatBadge = document.getElementById('repeatBadge');
            var repeatNum = document.getElementById('repeatNum');
            var reasonInput = document.getElementById('reason');
            var amountInput = document.getElementById('amount');
            var reasonStar = document.getElementById('reasonStar');

            var initialBranchId = "{{ old('branch_id', $deduction->employee?->branch_id) }}";
            var initialDeptId = "{{ old('department_id', $deduction->employee?->department_id) }}";
            var initialEmpId = "{{ old('employee_id', $deduction->employee_id) }}";

            // ── Type toggle ──────────────────────────────────────────────
            function setType(type) {
                typeInput.value = type;
                if (type === 'violation') {
                    btnViolation.className = 'btn btn-danger';
                    btnCustom.className = 'btn btn-outline-secondary';
                    violationSection.style.display = '';
                    reasonStar.style.display = 'none';
                } else {
                    btnCustom.className = 'btn btn-secondary';
                    btnViolation.className = 'btn btn-outline-danger';
                    violationSection.style.display = 'none';
                    repeatBadge.style.display = 'none';
                    reasonStar.style.display = '';
                }
            }

            btnCustom.addEventListener('click', function() {
                setType('custom');
            });
            btnViolation.addEventListener('click', function() {
                setType('violation');
                fetchViolationAmount();
            });

            // ── Branch / Dept / Employee cascades ────────────────────────
            function populateDepts(branchId, selectedDeptId) {
                var list = deptsByBranch[branchId] || [];
                if (!branchId || list.length === 0) {
                    deptSelect.innerHTML = '<option value="">-- اختر الفرع أولاً --</option>';
                    deptSelect.disabled = true;
                    resetEmployees();
                    return;
                }
                var opts = '<option value="">-- اختر القسم --</option>';
                list.forEach(function(d) {
                    var sel = String(d.id) === String(selectedDeptId) ? ' selected' : '';
                    opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
                });
                deptSelect.innerHTML = opts;
                deptSelect.disabled = false;
                if (selectedDeptId) populateEmployees(selectedDeptId, initialEmpId);
                else resetEmployees();
            }

            function populateEmployees(deptId, selectedEmpId) {
                var list = empsByDept[deptId] || [];
                if (!deptId || list.length === 0) {
                    empSelect.innerHTML = '<option value="">لا يوجد موظفون في هذا القسم</option>';
                    empSelect.disabled = true;
                    return;
                }
                var opts = '<option value="">-- اختر الموظف --</option>';
                list.forEach(function(e) {
                    var sel = String(e.id) === String(selectedEmpId) ? ' selected' : '';
                    opts += '<option value="' + e.id + '"' + sel + '>' + e.name + '</option>';
                });
                empSelect.innerHTML = opts;
                empSelect.disabled = false;
            }

            function resetEmployees() {
                empSelect.innerHTML = '<option value="">-- اختر القسم أولاً --</option>';
                empSelect.disabled = true;
            }

            branchSelect.addEventListener('change', function() {
                populateDepts(this.value, '');
                fetchViolationAmount();
            });
            deptSelect.addEventListener('change', function() {
                populateEmployees(this.value, '');
                fetchViolationAmount();
            });
            empSelect.addEventListener('change', fetchViolationAmount);
            monthInput.addEventListener('change', fetchViolationAmount);
            violationSelect.addEventListener('change', fetchViolationAmount);

            if (initialBranchId) populateDepts(initialBranchId, initialDeptId);

            // ── Violation amount AJAX ─────────────────────────────────────
            function fetchViolationAmount() {
                if (typeInput.value !== 'violation') return;
                var vId = violationSelect.value;
                if (!vId) {
                    repeatBadge.style.display = 'none';
                    return;
                }

                var empId = empSelect.value;
                var month = monthInput.value || "{{ now()->format('Y-m') }}";
                var url = violationAmountUrl + '?violation_id=' + vId + '&month=' + month + '&exclude_id=' +
                    deductionId;
                if (empId) url += '&employee_id=' + empId;

                repeatBadge.style.display = 'none';

                fetch(url)
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        repeatNum.textContent = data.repeat_number;
                        repeatBadge.style.display = 'inline-block';
                        if (amountInput.dataset.autoFilled === '1') {
                            amountInput.value = data.amount;
                        }
                        if (reasonInput.dataset.autoFilled === '1' && data.violation_name) {
                            reasonInput.value = data.violation_name;
                        }
                    });
            }

            amountInput.addEventListener('input', function() {
                this.dataset.autoFilled = '0';
            });
            reasonInput.addEventListener('input', function() {
                this.dataset.autoFilled = '0';
            });

            // On edit, existing values are already filled — don't auto-overwrite
            amountInput.dataset.autoFilled = '0';
            reasonInput.dataset.autoFilled = '0';

            setType(typeInput.value);
            if (typeInput.value === 'violation') {
                var vId = violationSelect.value;
                var empId = "{{ $deduction->employee_id }}";
                if (vId && empId) {
                    repeatBadge.style.display = '';
                }
            }
        })();
    </script>
@endpush
