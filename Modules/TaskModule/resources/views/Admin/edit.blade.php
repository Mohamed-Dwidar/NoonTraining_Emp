@extends('layoutmodule::layouts.layout_main')

@section('title') تعديل مهمة @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-edit"></i> &nbsp; تعديل المهمة</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('admin.tasks.update', $task->id) }}">
                @csrf

                {{-- Employee selection --}}
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="branchSelect">الفرع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="branchSelect" name="branch_id" class="form-control">
                                <option value="">-- اختر الفرع --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id', $task->employee->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
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

                {{-- Title --}}
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label for="title">عنوان المهمة <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="title" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $task->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label for="details">التفاصيل</label>
                        <div class="form-group">
                            <textarea id="details" name="details" rows="3"
                                      class="form-control @error('details') is-invalid @enderror">{{ old('details', $task->details) }}</textarea>
                            @error('details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Dates + Status --}}
                <div class="row">
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="start_date">تاريخ البداية <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="start_date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $task->start_date->format('Y-m-d')) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="end_date">تاريخ النهاية <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="end_date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $task->end_date->format('Y-m-d')) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-12 col-6">
                        <label for="status">الحالة <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="status" name="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                @foreach($statuses as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('status', $task->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-1">
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">إلغاء</a>
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
    var startInput   = document.getElementById('start_date');
    var endInput     = document.getElementById('end_date');

    var oldBranchId = "{{ old('branch_id', $task->employee->branch_id ?? '') }}";
    var oldDeptId   = "{{ old('department_id', $task->employee->department_id ?? '') }}";
    var oldEmpId    = "{{ old('employee_id', $task->employee_id) }}";

    function populateDepts(branchId, selectedDeptId) {
        var list = deptsByBranch[branchId] || [];
        if (!branchId || list.length === 0) {
            deptSelect.innerHTML = '<option value="">-- اختر الفرع أولاً --</option>';
            deptSelect.disabled = true; resetEmployees(); return;
        }
        var opts = '<option value="">-- اختر القسم --</option>';
        list.forEach(function (d) {
            var sel = String(d.id) === String(selectedDeptId) ? ' selected' : '';
            opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
        });
        deptSelect.innerHTML = opts;
        deptSelect.disabled = false;
        if (selectedDeptId) populateEmployees(selectedDeptId, oldEmpId);
        else resetEmployees();
    }

    function populateEmployees(deptId, selectedEmpId) {
        var list = empsByDept[deptId] || [];
        if (!deptId || list.length === 0) {
            empSelect.innerHTML = '<option value="">لا يوجد موظفون</option>';
            empSelect.disabled = true; return;
        }
        var opts = '<option value="">-- اختر الموظف --</option>';
        list.forEach(function (e) {
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

    branchSelect.addEventListener('change', function () { populateDepts(this.value, ''); });
    deptSelect.addEventListener('change',   function () { populateEmployees(this.value, ''); });
    startInput.addEventListener('change',   function () { if (endInput.value < this.value) endInput.value = this.value; endInput.min = this.value; });

    if (oldBranchId) populateDepts(oldBranchId, oldDeptId);
})();
</script>
@endpush
