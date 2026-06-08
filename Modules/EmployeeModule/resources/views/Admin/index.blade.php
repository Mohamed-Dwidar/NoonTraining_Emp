@extends('layoutmodule::layouts.layout_main')

@section('title')
    الموظفون
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-users"></i>
                    &nbsp;
                    الموظفون
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-end">
                                <div class="col-lg-10">
                                    <form id="filterForm" method="GET"
                                        action="{{ route(Auth::getDefaultDriver() . '.employees.index') }}">
                                        <div class="row align-items-end">
                                            <div class="col-md-3 mb-1">
                                                <input type="text" name="search" id="filter-search" class="form-control"
                                                    placeholder="بحث بالاسم أو البريد الإلكتروني أو الوظيفة"
                                                    value="{{ request('search') }}">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <select name="branch_id" class="form-control filter-select">
                                                    <option value="">-- كل الفروع --</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <select name="department_id" class="form-control filter-select"
                                                    {{ request('branch_id') ? '' : 'disabled' }}>
                                                    <option value="">-- كل الأقسام --</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                            {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-1">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i> بحث
                                                </button>
                                                <a href="{{ route(Auth::getDefaultDriver() . '.employees.index') }}"
                                                    class="btn btn-secondary">
                                                    <i class="fa fa-times"></i> إعادة تعيين
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-2 mb-2" style="text-align: left;">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.employees.create') }}"
                                        role="button">تسجيل موظف جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم الموظف</th>
                                            <th>الفرع / القسم</th>
                                            <th>الوظيفة</th>
                                            <th>الراتب الأساسي</th>
                                            <th>أيام  / ساعات العمل الشهرية</th>
                                            <th>الأجر اليومي</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($employees->count())
                                            @foreach ($employees as $employee)
                                                <tr>
                                                    <td class="strong">
                                                        <a
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.show', $employee->id) }}">
                                                            {{ $employee->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $employee->branch ? $employee->branch->name : '-' }} / {{ $employee->department ? $employee->department->name : '-' }}
                                                    <td>{{ $employee->job }}</td>
                                                    <td>{{ number_format($employee->basic_salary, 2) }} ر٫س</td>
                                                    <td>{{ $employee->monthly_working_days }} يوم (  {{ $employee->daily_working_hours }} ساعة لليوم)</td>
                                                    <td>{{ number_format($employee->daily_salary, 2) }} ر٫س</td>
                                                    <td>
                                                        <a class="btn btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.show', $employee->id) }}"
                                                            role="button">عرض</a>

                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.edit', $employee->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.employees.destroy', $employee->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الموظف ؟')">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">لا يوجد موظفون</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $employees->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    const branchSelect     = document.querySelector('select[name="branch_id"]');
    const departmentSelect = document.querySelector('select[name="department_id"]');
    const selectedDept     = "{{ request('department_id') }}";

    branchSelect.addEventListener('change', function () {
        const branchId = this.value;

        departmentSelect.innerHTML = '<option value="">-- كل الأقسام --</option>';

        if (!branchId) {
            departmentSelect.disabled = true;
            return;
        }

        fetch('/admin/employee/departments-by-branch/' + branchId)
            .then(res => res.json())
            .then(departments => {
                departments.forEach(function (dept) {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    if (dept.id == selectedDept) option.selected = true;
                    departmentSelect.appendChild(option);
                });
                departmentSelect.disabled = false;
            });
    });
</script>
@endpush
