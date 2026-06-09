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
                                            <div class="col-md-2 mb-1">
                                                <select name="status" class="form-control filter-select">
                                                    <option value="">-- كل الحالات --</option>
                                                    <option value="active"     {{ request('status') === 'active'     ? 'selected' : '' }}>نشط</option>
                                                    <option value="suspended"  {{ request('status') === 'suspended'  ? 'selected' : '' }}>موقوف</option>
                                                    {{-- <option value="on_leave"   {{ request('status') === 'on_leave'   ? 'selected' : '' }}>إجازة</option> --}}
                                                    <option value="resigned"   {{ request('status') === 'resigned'   ? 'selected' : '' }}>مستقيل</option>
                                                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>منهي الخدمة</option>
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
                                            <th>الحالة</th>
                                            <th>عمولة الطلاب</th>
                                            <th style="width: 220px">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($employees->count())
                                            @foreach ($employees as $employee)
                                                @php
                                                    $rowStyle = match($employee->status) {
                                                        'suspended'  => 'background-color:#ffeab9; color:#000;',
                                                        'resigned'   => 'background-color:#f8d7da;',
                                                        'terminated' => 'background-color:#dc3545; color:#fff;',
                                                        default      => '',
                                                    };
                                                @endphp
                                                <tr style="{{ $rowStyle }}">
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
                                                        <select class="status-select form-control form-control-sm"
                                                            data-id="{{ $employee->id }}"
                                                            data-url="{{ route(Auth::getDefaultDriver() . '.employees.update-status', $employee->id) }}">
                                                            <option value="active"      {{ $employee->status === 'active'      ? 'selected' : '' }}>نشط</option>
                                                            <option value="suspended"   {{ $employee->status === 'suspended'   ? 'selected' : '' }}>موقوف</option>
                                                            {{-- <option value="on_leave"    {{ $employee->status === 'on_leave'    ? 'selected' : '' }}>إجازة</option> --}}
                                                            <option value="resigned"    {{ $employee->status === 'resigned'    ? 'selected' : '' }}>مستقيل</option>
                                                            <option value="terminated"  {{ $employee->status === 'terminated'  ? 'selected' : '' }}>منهي الخدمة</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if ($employee->branch && $employee->branch->type === 'training')
                                                            {{ number_format($employee->stu_commission, 2) }} ر٫س
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.show', $employee->id) }}"
                                                            role="button">عرض</a>

                                                        <a class="btn btn-sm btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.employees.edit', $employee->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.employees.destroy', $employee->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الموظف ؟')">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">لا يوجد موظفون</td>
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

    const statusRowStyles = {
        active:     { background: '',        color: ''      },
        on_leave:   { background: '',        color: ''      },
        suspended:  { background: '#ffeab9', color: '#000'  },
        resigned:   { background: '#f8d7da', color: ''      },
        terminated: { background: '#dc3545', color: '#fff'  },
    };

    function applyRowColor(tr, status) {
        const style = statusRowStyles[status] || statusRowStyles.active;
        tr.style.backgroundColor = style.background;
        tr.style.color            = style.color;
    }

    document.querySelectorAll('.status-select').forEach(function (select) {

        select.addEventListener('change', function () {
            const url      = this.dataset.url;
            const newStatus = this.value;
            const tr       = this.closest('tr');
            const select   = this;

            select.disabled    = true;
            tr.style.opacity   = '0.4';
            tr.style.transition = 'opacity 0.2s';

            fetch(url, {
                method:  'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    applyRowColor(tr, newStatus);
                } else {
                    alert(data.message);
                    select.value = select.dataset.prevValue;
                }
            })
            .catch(() => alert('حدث خطأ، يرجى المحاولة لاحقاً'))
            .finally(() => {
                select.disabled  = false;
                tr.style.opacity = '1';
            });
        });

        select.addEventListener('mousedown', function () {
            this.dataset.prevValue = this.value;
        });
    });


</script>
@endpush
