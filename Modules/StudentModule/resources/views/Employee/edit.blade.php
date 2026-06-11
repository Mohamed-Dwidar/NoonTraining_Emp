@extends('layoutmodule::layouts.layout_main')

@section('title') تعديل طالب @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-edit"></i> &nbsp; تعديل بيانات الطالب</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('admin.students.update', $student->id) }}">
                @csrf

                {{-- Employee responsible --}}
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="branchSelect">الفرع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <select id="branchSelect" name="branch_id" class="form-control">
                                <option value="">-- اختر الفرع --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id', $student->employee->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
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
                        <label for="employee_id">الموظف المسؤول <span class="text-danger">*</span></label>
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

                {{-- Student info --}}
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <label for="name">اسم الطالب <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <label for="mobile">رقم الجوال <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="mobile" name="mobile"
                                   class="form-control @error('mobile') is-invalid @enderror"
                                   value="{{ old('mobile', $student->mobile) }}">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="course_name">اسم الكورس <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="course_name" name="course_name"
                                   class="form-control @error('course_name') is-invalid @enderror"
                                   value="{{ old('course_name', $student->course_name) }}">
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="row">
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="total_amount">المبلغ الإجمالي <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="number" id="total_amount" name="total_amount" min="0"
                                   class="form-control @error('total_amount') is-invalid @enderror"
                                   value="{{ old('total_amount', $student->total_amount) }}">
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="paid_amount">المبلغ المدفوع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="number" id="paid_amount" name="paid_amount" min="0"
                                   class="form-control @error('paid_amount') is-invalid @enderror"
                                   value="{{ old('paid_amount', $student->paid_amount) }}">
                            @error('paid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label>المتبقي</label>
                        <div class="form-group">
                            <span id="remainingDisplay" class="form-control text-center bg-light">
                                {{ number_format($student->remainingAmount()) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="payment_method">طريقة الدفع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="payment_method" name="payment_method"
                                   class="form-control @error('payment_method') is-invalid @enderror"
                                   value="{{ old('payment_method', $student->payment_method) }}">
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="payment_date">تاريخ الدفع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="payment_date" name="payment_date"
                                   class="form-control @error('payment_date') is-invalid @enderror"
                                   value="{{ old('payment_date', $student->payment_date) }}">
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Previous student --}}
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <label for="previous_student_of">طالب سابق لدى</label>
                        <div class="form-group">
                            <input type="text" id="previous_student_of" name="previous_student_of"
                                   class="form-control @error('previous_student_of') is-invalid @enderror"
                                   value="{{ old('previous_student_of', $student->previous_student_of) }}"
                                   placeholder="اختياري – اسم الجهة السابقة">
                            @error('previous_student_of')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-1">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">إلغاء</a>
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

    var branchSelect     = document.getElementById('branchSelect');
    var deptSelect       = document.getElementById('deptSelect');
    var empSelect        = document.getElementById('employee_id');
    var totalInput       = document.getElementById('total_amount');
    var paidInput        = document.getElementById('paid_amount');
    var remainingDisplay = document.getElementById('remainingDisplay');

    var oldBranchId = "{{ old('branch_id', $student->employee->branch_id ?? '') }}";
    var oldDeptId   = "{{ old('department_id', $student->employee->department_id ?? '') }}";
    var oldEmpId    = "{{ old('employee_id', $student->employee_id) }}";

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

    function updateRemaining() {
        var total = parseInt(totalInput.value) || 0;
        var paid  = parseInt(paidInput.value)  || 0;
        remainingDisplay.textContent = (total - paid).toLocaleString('ar-SA');
    }

    branchSelect.addEventListener('change', function () { populateDepts(this.value, ''); });
    deptSelect.addEventListener('change',   function () { populateEmployees(this.value, ''); });
    totalInput.addEventListener('input',    updateRemaining);
    paidInput.addEventListener('input',     updateRemaining);

    if (oldBranchId) populateDepts(oldBranchId, oldDeptId);
    updateRemaining();
})();
</script>
@endpush
