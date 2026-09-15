@extends('layoutmodule::layouts.layout_main')

@section('title')
    تقرير أداء — {{ $employee->name }}
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2 d-flex align-items-center justify-content-between">
            <h3><i class="fa fa-user"></i> &nbsp; تقرير أداء — {{ $employee->name }}</h3>
            <a href="{{ route('admin.reports.employee-performance') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-right"></i> رجوع للتقرير العام
            </a>
        </div>

        @include('layoutmodule::layouts.flash')

        {{-- ─── Filter bar ─── --}}
        <div class="card mb-2">
            <div class="card-body py-2 px-2">
                <form method="GET" action="{{ route('admin.reports.employee-performance.show', $employee->id) }}">
                    <div class="row align-items-end">
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
                                    <h3 class="blue">{{ number_format($students) }}</h3>
                                    <h5>الطلاب</h5>
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
                                    <h3 class="orange">{{ number_format($admissions) }}</h3>
                                    <h5>التسجيلات / الدخول</h5>
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
                                    <h3 class="green">{{ number_format($revenue, 2) }}</h3>
                                    <h5>الإيرادات (ر.س)</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Monthly breakdown ─── --}}
        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">الأداء الشهري (آخر 6 أشهر)</h5></div>
                    <div class="card-body"><canvas id="trendChart" height="220"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5 col-12">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">تفصيل الأشهر</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr class="head">
                                        <th>الشهر</th>
                                        <th>الطلاب</th>
                                        <th>الإيرادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthly as $m)
                                        <tr>
                                            <td>{{ $m->month }}</td>
                                            <td>{{ number_format($m->students) }}</td>
                                            <td>{{ number_format($m->revenue, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Underlying records ─── --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">سجلات الطلاب في الفترة المحددة ({{ $records->count() }})</h5>
            </div>
            <div class="card-body p-0">
                @if ($records->isEmpty())
                    <p class="text-center text-muted py-4">لا توجد سجلات في هذه الفترة</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>اسم الطالب</th>
                                    <th>رقم الجوال</th>
                                    <th>رقم الهوية</th>
                                    <th>الكورس</th>
                                    <th>الإجمالي</th>
                                    <th>المدفوع</th>
                                    <th>تاريخ الدفع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->mobile }}</td>
                                        <td>{{ $record->national_id }}</td>
                                        <td>{{ $record->course_name }}</td>
                                        <td>{{ number_format($record->total_amount) }}</td>
                                        <td>{{ number_format($record->paid_amount) }}</td>
                                        <td>{{ $record->payment_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
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
            syncPeriodFields();

            var trendLabels = @json($monthly->pluck('month'));
            var trendStudents = @json($monthly->pluck('students'));
            var trendRevenue = @json($monthly->pluck('revenue'));

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
        })();
    </script>
@endpush
