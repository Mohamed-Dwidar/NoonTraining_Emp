@extends('layoutmodule::layouts.layout_main')

@php
    $settings = \Modules\SettingModule\app\Models\Setting::all();
    $emp_monthly_working_days = $settings->where('key', 'emp_monthly_working_days')->first()->value ?? 24;
    $emp_daily_working_hours = $settings->where('key', 'emp_daily_working_hours')->first()->value ?? 8;
    $base_student_commision = $settings->where('key', 'base_student_commision')->first()->value ?? 10;
@endphp

@section('title')
    إنشاء موظف جديد
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-plus-circle"></i>
                    إنشاء موظف جديد
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <form class="card-form side-form" method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.employees.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">اسم الموظف</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name') }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="email">البريد الإلكتروني</label>
                                                <div class="form-group">
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" value="{{ old('email') }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="password">كلمة المرور</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="password" name="password" autocomplete="new-password">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="job">الوظيفة</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('job') is-invalid @enderror"
                                                        id="job" name="job" value="{{ old('job') }}">
                                                    @error('job')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                <label for="branch_id">الفرع</label>
                                                <div class="form-group">
                                                    <select class="form-control @error('branch_id') is-invalid @enderror"
                                                        id="branch_id" name="branch_id">
                                                        <option value="">اختر الفرع</option>
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->id }}" data-type="{{ $branch->type }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('branch_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                <label for="department_id">القسم</label>
                                                <div class="form-group position-relative">
                                                    <select class="form-control @error('department_id') is-invalid @enderror"
                                                        id="department_id" name="department_id" disabled>
                                                        <option value="">اختر الفرع أولاً</option>
                                                    </select>
                                                    <span id="dept-loading" class="position-absolute"
                                                        style="top:8px; left:10px; display:none;">
                                                        <i class="fa fa-spinner fa-spin text-primary"></i>
                                                    </span>
                                                    @error('department_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="basic_salary">الراتب الأساسي</label>
                                                <div class="form-group">
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control @error('basic_salary') is-invalid @enderror"
                                                        id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}">
                                                    @error('basic_salary')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="monthly_working_days">أيام العمل الشهرية</label>
                                                <div class="form-group">
                                                    <input type="number" min="1" max="31"
                                                        class="form-control @error('monthly_working_days') is-invalid @enderror"
                                                        id="monthly_working_days" name="monthly_working_days" value="{{ old('monthly_working_days', $emp_monthly_working_days) }}">
                                                    @error('monthly_working_days')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="daily_working_hours">ساعات العمل اليومية</label>
                                                <div class="form-group">
                                                    <input type="number" min="1" max="24"
                                                        class="form-control @error('daily_working_hours') is-invalid @enderror"
                                                        id="daily_working_hours" name="daily_working_hours" value="{{ old('daily_working_hours', $emp_daily_working_hours) }}">
                                                    @error('daily_working_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="commission-row" style="display:none;">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="stu_commission">عمولة تسجيل الطلاب</label>
                                                <div class="form-group">
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control @error('stu_commission') is-invalid @enderror"
                                                        id="stu_commission" name="stu_commission" value="{{ old('stu_commission', $base_student_commision) }}">
                                                    @error('stu_commission')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="hired_at">تاريخ التعيين</label>
                                                <div class="form-group">
                                                    <input type="date"
                                                        class="form-control @error('hired_at') is-invalid @enderror"
                                                        id="hired_at" name="hired_at" value="{{ old('hired_at') }}">
                                                    @error('hired_at')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-1">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.employees.index') }}"
                                                class="btn btn-secondary">إلغاء</a>
                                            <button type="submit" class="btn btn-primary">حفظ</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const branchSelect     = document.getElementById('branch_id');
    const deptSelect       = document.getElementById('department_id');
    const deptLoading      = document.getElementById('dept-loading');
    const deptsByBranchBase = "{{ url('admin/employee/departments-by-branch') }}";
    const oldDeptId         = "{{ old('department_id') }}";

    function loadDepartments(branchId, selectedId) {
        deptSelect.disabled = true;
        deptSelect.innerHTML = '<option value="">جاري التحميل...</option>';
        deptLoading.style.display = 'inline';

        fetch(deptsByBranchBase + '/' + branchId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(function (departments) {
            deptLoading.style.display = 'none';
            if (departments.length === 0) {
                deptSelect.innerHTML = '<option value="">لا توجد أقسام لهذا الفرع</option>';
                return;
            }
            let html = '<option value="">اختر القسم</option>';
            departments.forEach(function (dept) {
                const selected = String(dept.id) === String(selectedId) ? 'selected' : '';
                html += `<option value="${dept.id}" ${selected}>${dept.name}</option>`;
            });
            deptSelect.innerHTML = html;
            deptSelect.disabled  = false;
        })
        .catch(function () {
            deptLoading.style.display = 'none';
            deptSelect.innerHTML = '<option value="">حدث خطأ، أعد المحاولة</option>';
        });
    }

    function updateCommissionVisibility() {
        const opt = branchSelect.options[branchSelect.selectedIndex];
        const isTraining = opt && opt.dataset.type === 'training';
        document.getElementById('commission-row').style.display = isTraining ? '' : 'none';
    }

    branchSelect.addEventListener('change', function () {
        updateCommissionVisibility();
        if (!this.value) {
            deptSelect.innerHTML = '<option value="">اختر الفرع أولاً</option>';
            deptSelect.disabled  = true;
            return;
        }
        loadDepartments(this.value, '');
    });

    // Restore state after validation failure
    if (branchSelect.value) {
        updateCommissionVisibility();
        loadDepartments(branchSelect.value, oldDeptId);
    }
})();
</script>
@endpush
