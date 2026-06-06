@extends('layoutmodule::layouts.layout_main')

@section('title') إضافة مكافأة @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-plus-circle"></i> &nbsp; إضافة مكافأة جديدة</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('admin.bonuses.store') }}">
                @csrf

                <div class="row">
                    {{-- Branch --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="branchSelect">الفرع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="branchSelect" name="branch_id" class="form-control">
                                <option value="">-- اختر الفرع --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                    {{-- Month --}}
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="month">الشهر <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="month" id="month" name="month"
                                   class="form-control @error('month') is-invalid @enderror"
                                   value="{{ old('month', $month) }}">
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="amount">المبلغ (ر.س) <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="number" id="amount" name="amount"
                                   step="0.01" min="0"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Reason --}}
                    <div class="col-lg-6 col-sm-12">
                        <label for="reason">السبب <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <textarea id="reason" name="reason" rows="3"
                                      class="form-control @error('reason') is-invalid @enderror">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-1">
                    <a href="{{ route('admin.bonuses.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ</button>
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

    var oldBranchId = "{{ old('branch_id') }}";
    var oldDeptId   = "{{ old('department_id') }}";
    var oldEmpId    = "{{ old('employee_id') }}";

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
        if (selectedDeptId) populateEmployees(selectedDeptId, oldEmpId);
        else resetEmployees();
    }

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

    branchSelect.addEventListener('change', function () { populateDepts(this.value, ''); });
    deptSelect.addEventListener('change',   function () { populateEmployees(this.value, ''); });

    if (oldBranchId) populateDepts(oldBranchId, oldDeptId);
})();
</script>
@endpush
