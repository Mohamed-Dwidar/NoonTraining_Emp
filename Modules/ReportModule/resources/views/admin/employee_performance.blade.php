@extends('layoutmodule::layouts.layout_main')

@section('title')
    تقارير أداء الموظفين
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <h3><i class="fa fa-bar-chart"></i> &nbsp; تقارير أداء الموظفين</h3>
        </div>

        @include('layoutmodule::layouts.flash')

        {{-- ─── Filter bar ─── --}}
        <div class="card mb-2">
            <div class="card-body py-2 px-2">
                <form method="GET" action="{{ route('admin.reports.employee-performance') }}">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="d-block mb-1"><strong>الفترة</strong></label>
                            <select name="period" id="periodSelect" class="form-control">
                                <option value="current_month" {{ $filters['period'] == 'current_month' ? 'selected' : '' }}>الشهر الحالي</option>
                                <option value="previous_month" {{ $filters['period'] == 'previous_month' ? 'selected' : '' }}>الشهر السابق</option>
                                <option value="month" {{ $filters['period'] == 'month' ? 'selected' : '' }}>شهر محدد</option>
                                <option value="custom" {{ $filters['period'] == 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="monthField" style="display:none;">
                            <label class="d-block mb-1"><strong>الشهر</strong></label>
                            <input type="month" name="month" class="form-control" value="{{ $filters['month'] ?? now()->format('Y-m') }}">
                        </div>
                        <div class="col-md-2 custom-date-field" style="display:none;">
                            <label class="d-block mb-1"><strong>من تاريخ</strong></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? $from }}">
                        </div>
                        <div class="col-md-2 custom-date-field" style="display:none;">
                            <label class="d-block mb-1"><strong>إلى تاريخ</strong></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? $to }}">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="d-block mb-1"><strong>الفرع</strong></label>
                            <select name="branch_id" id="branchSelect" class="form-control">
                                <option value="">-- كل الفروع --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $filters['branch_id'] == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="d-block mb-1"><strong>القسم</strong></label>
                            <select name="department_id" id="deptSelect" class="form-control">
                                <option value="">-- كل الأقسام --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $filters['department_id'] == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الموظف</strong></label>
                            <select name="employee_id" id="empSelect" class="form-control">
                                <option value="">-- كل الموظفين --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $filters['employee_id'] == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="d-block mb-1"><strong>&nbsp;</strong></label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter"></i> تطبيق
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

         <div class="content-header mb-2">
            <h4>
                  الفترة المعروضة: <strong>من : {{ $from }}</strong> &nbsp;—&nbsp; <strong>الي : {{ $to }}</strong>
            </h4>
        </div>

        {{-- ─── KPI cards ─── --}}
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-graduation-cap blue font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    <h3 class="blue">{{ number_format($kpis['students']) }}</h3>
                                    <h5>إجمالي الطلاب</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-sign-in orange font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    <h3 class="orange">{{ number_format($kpis['admissions']) }}</h3>
                                    <h5>إجمالي التسجيلات </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-money green font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    <h3 class="green">{{ number_format($kpis['revenue'], 2) }}</h3>
                                    <h5>إجمالي الإيرادات (ر.س)</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($rows->isEmpty())
            <div class="card">
                <div class="card-body">
                    <p class="text-center text-muted py-4">لا توجد بيانات في الفترة المحددة</p>
                </div>
            </div>
        @else
            {{-- ─── Charts ─── --}}
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">الطلاب حسب الموظف</h5></div>
                        <div class="card-body"><canvas id="studentsChart" height="220"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">الإيرادات حسب الموظف</h5></div>
                        <div class="card-body"><canvas id="revenueChart" height="220"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">التسجيلات حسب الموظف</h5></div>
                        <div class="card-body"><canvas id="admissionsChart" height="220"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">الأداء الشهري (آخر 6 أشهر)</h5></div>
                        <div class="card-body"><canvas id="trendChart" height="220"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- ─── Employee performance table ─── --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">أداء الموظفين</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>الترتيب</th>
                                    <th>الموظف</th>
                                    <th>الفرع / القسم</th>
                                    <th>الطلاب</th>
                                    <th>التسجيلات</th>
                                    <th>الإيرادات</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $i => $row)
                                    <tr>
                                        <td>
                                            @if ($i === 0)
                                                <span class="badge badge-success">#1 <i class="fa fa-trophy"></i></span>
                                            @elseif ($i === $rows->count() - 1 && $rows->count() > 1)
                                                <span class="badge badge-danger">#{{ $i + 1 }}</span>
                                            @else
                                                #{{ $i + 1 }}
                                            @endif
                                        </td>
                                        <td class="strong">{{ $row->employee_name }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $row->branch_name }}
                                                @if ($row->department_name !== '—')
                                                    &nbsp;/&nbsp; {{ $row->department_name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>{{ number_format($row->students) }}</td>
                                        <td>{{ number_format($row->admissions) }}</td>
                                        <td>{{ number_format($row->revenue, 2) }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-info"
                                                href="{{ route('admin.reports.employee-performance.show', [$row->employee_id, 'period' => $filters['period'], 'month' => $filters['month'], 'date_from' => $filters['date_from'], 'date_to' => $filters['date_to']]) }}">
                                                <i class="fa fa-list-alt"></i> التفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ─── Rankings ─── --}}
            <div class="row">
                @foreach ([
                    'students'   => 'الطلاب',
                    'admissions' => 'التسجيلات',
                    'revenue'    => 'الإيرادات',
                ] as $metric => $label)
                    <div class="col-lg-4 col-12 m-2">
                        <div class="card">
                            <div class="card-header"><h6 class="mb-0">ترتيب حسب {{ $label }}</h6></div>
                            <div class="card-body  m-2">
                                <h6 class="text-success"><i class="fa fa-arrow-up"></i> الأعلى</h6>
                                <ul class="list-unstyled mb-3">
                                    @foreach ($rankings[$metric]['top'] as $r)
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>{{ $r->employee_name }}</span>
                                            <strong>{{ number_format($metric === 'revenue' ? $r->revenue : $r->$metric, $metric === 'revenue' ? 2 : 0) }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                                <h6 class="text-danger"><i class="fa fa-arrow-down"></i> الأقل</h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($rankings[$metric]['bottom'] as $r)
                                        <li class="d-flex justify-content-between border-bottom py-1">
                                            <span>{{ $r->employee_name }}</span>
                                            <strong>{{ number_format($metric === 'revenue' ? $r->revenue : $r->$metric, $metric === 'revenue' ? 2 : 0) }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // Toggle filter fields based on selected period
            var periodSelect = document.getElementById('periodSelect');
            var monthField = document.getElementById('monthField');
            var customFields = document.querySelectorAll('.custom-date-field');

            function syncPeriodFields() {
                monthField.style.display = periodSelect.value === 'month' ? '' : 'none';
                customFields.forEach(function(el) {
                    el.style.display = periodSelect.value === 'custom' ? '' : 'none';
                });
            }
            periodSelect.addEventListener('change', syncPeriodFields);

            // ── Cascading branch → department → employee ──
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($g) => $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));
            var empsByDept = @json($employees->groupBy('department_id')->map(fn($g) => $g->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()));
            var empsByBranch = @json($employees->groupBy('branch_id')->map(fn($g) => $g->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()));

            var branchSelect = document.getElementById('branchSelect');
            var deptSelect = document.getElementById('deptSelect');
            var empSelect = document.getElementById('empSelect');
            var selectedDept = {{ $filters['department_id'] ? (int) $filters['department_id'] : 'null' }};
            var selectedEmp = {{ $filters['employee_id'] ? (int) $filters['employee_id'] : 'null' }};

            function populateEmps(deptId, branchId) {
                var opts = '<option value="">-- كل الموظفين --</option>';
                var list = deptId ? (empsByDept[deptId] || []) : (branchId ? (empsByBranch[branchId] || []) : []);
                list.forEach(function(e) {
                    var sel = (selectedEmp && e.id === selectedEmp) ? ' selected' : '';
                    opts += '<option value="' + e.id + '"' + sel + '>' + e.name + '</option>';
                });
                empSelect.innerHTML = opts;
            }

            function populateDepts(branchId) {
                var opts = '<option value="">-- كل الأقسام --</option>';
                var list = deptsByBranch[branchId] || [];
                list.forEach(function(d) {
                    var sel = (selectedDept && d.id === selectedDept) ? ' selected' : '';
                    opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
                });
                deptSelect.innerHTML = opts;
                populateEmps(deptSelect.value, branchId);
            }

            branchSelect.addEventListener('change', function() {
                selectedDept = null;
                selectedEmp = null;
                populateDepts(this.value);
            });

            deptSelect.addEventListener('change', function() {
                selectedEmp = null;
                populateEmps(this.value, branchSelect.value);
            });

            if (branchSelect.value) populateDepts(branchSelect.value);
            syncPeriodFields();

            @if ($rows->isNotEmpty())
            // Chart.js v2 charts
            var labels = @json($rows->pluck('employee_name'));
            var studentsData = @json($rows->pluck('students'));
            var admissionsData = @json($rows->pluck('admissions'));
            var revenueData = @json($rows->pluck('revenue'));

            new Chart(document.getElementById('studentsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{ label: 'الطلاب', data: studentsData, backgroundColor: '#2196F3' }]
                },
                options: { legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
            });

            new Chart(document.getElementById('revenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{ label: 'الإيرادات', data: revenueData, backgroundColor: '#4CAF50' }]
                },
                options: { legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
            });

            new Chart(document.getElementById('admissionsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{ label: 'التسجيلات / الدخول', data: admissionsData, backgroundColor: '#FF9800' }]
                },
                options: { legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true } }] } }
            });

            var trendLabels = @json($trend->pluck('month'));
            var trendStudents = @json($trend->pluck('students'));
            var trendRevenue = @json($trend->pluck('revenue'));

            new Chart(document.getElementById('trendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label: 'الطلاب', data: trendStudents, borderColor: '#2196F3', fill: false, yAxisID: 'y-students' },
                        { label: 'الإيرادات', data: trendRevenue, borderColor: '#4CAF50', fill: false, yAxisID: 'y-revenue' }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [
                            { id: 'y-students', position: 'left', ticks: { beginAtZero: true } },
                            { id: 'y-revenue', position: 'right', ticks: { beginAtZero: true } }
                        ]
                    }
                }
            });
            @endif
        })();
    </script>
@endpush
