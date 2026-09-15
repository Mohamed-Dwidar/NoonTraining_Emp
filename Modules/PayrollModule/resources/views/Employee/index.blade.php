@extends('layoutmodule::layouts.layout_main')

@section('title')
     مرتباتي
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <h3><i class="fa fa-money"></i> &nbsp; مرتباتي</h3>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="card">
            <div class="card-body p-0">
                @if ($payrolls->isEmpty())
                    <p class="text-center py-4 mt-2">لا توجد مرتبات مسجلة بعد</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>الشهر</th>
                                    <th>أيام الحضور</th>
                                    <th>أيام الغياب</th>
                                    <th>المرتب الاساسي</th>
                                    <th>الخصومات</th>
                                    <th>المكافات</th>
                                    <th>عمولة الطلاب</th>
                                    <th>المرتب النهائي</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td class="strong">{{ $payroll->month }}</td>
                                        <td>{{ $payroll->days_present }}<span style="color:#adb5bd"> / {{ $payroll->monthly_working_days }}</span></td>
                                        <td>{{ $payroll->days_absent }}</td>
                                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                                        <td>{{ number_format($payroll->deductions, 2) }}</td>
                                        <td>{{ number_format($payroll->bonuses, 2) }}</td>
                                        <td>{{ number_format($payroll->students_commission, 2) }}</td>
                                        <td>{{ number_format($payroll->total_salary, 2) }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-info payslip-btn"
                                                data-id="{{ $payroll->id }}"
                                                data-url="{{ route('employee.payrolls.payslip', $payroll->id) }}">
                                                <i class="fa fa-file-text-o"></i> كشف الراتب
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-secondary details-btn"
                                                data-id="{{ $payroll->id }}"
                                                data-url="{{ route('employee.payrolls.details', $payroll->id) }}">
                                                <i class="fa fa-list-alt"></i> التفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $payrolls->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>

    </div>

{{-- ========== Payslip Modal ========== --}}
<div class="modal fade" id="payslipModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#2c3e50; color:#fff;">
                <h5 class="modal-title"><i class="fa fa-file-text-o"></i> كشف الراتب</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="payslipModalBody">
                <div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
            </div>
            <div class="modal-footer">
                <a href="#" id="payslipPrintBtn" class="btn btn-primary">
                    <i class="fa fa-download"></i> تحميل PDF
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

{{-- ========== Details Modal ========== --}}
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#495057; color:#fff;">
                <h5 class="modal-title"><i class="fa fa-list-alt"></i> تفاصيل كشف الراتب &mdash; <span id="detailsEmpName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;"><span>&times;</span></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
            </div>
            <div class="modal-footer">
                <a href="#" id="detailsPdfBtn" class="btn btn-primary">
                    <i class="fa fa-download"></i> تحميل PDF
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function() {
            // ── Payslip modal ──
            document.querySelectorAll('.payslip-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var url  = this.dataset.url;
                    var body = document.getElementById('payslipModalBody');
                    body.innerHTML = '<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';
                    document.getElementById('payslipPrintBtn').href = '#';
                    $('#payslipModal').modal('show');

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        document.getElementById('payslipPrintBtn').href = d.print_url;
                        var commission = d.branch_type === 'training'
                            ? '<tr><td style="padding:8px 0;">عمولة الطلاب</td><td style="font-weight:600;color:#27ae60;">+ ' + d.students_commission + ' ر٫س</td></tr>'
                            : '';
                        body.innerHTML =
                            '<div style="direction:rtl;font-family:Tahoma,sans-serif;">'
                            + '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;background:#f5f7fa;padding:14px;border-radius:4px;margin-bottom:16px;">'
                            + '<div><span style="color:#888;font-size:.8rem;">اسم الموظف</span><br><strong>' + d.employee_name + '</strong></div>'
                            + '<div><span style="color:#888;font-size:.8rem;">الوظيفة</span><br><strong>' + d.employee_job + '</strong></div>'
                            + '<div><span style="color:#888;font-size:.8rem;">الفرع</span><br><strong>' + d.branch_name + '</strong></div>'
                            + '<div><span style="color:#888;font-size:.8rem;">القسم</span><br><strong>' + d.department_name + '</strong></div>'
                            + '<div><span style="color:#888;font-size:.8rem;">الشهر</span><br><strong>' + d.month + '</strong></div>'
                            + '</div>'
                            + '<table style="width:100%;border-collapse:collapse;">'
                            + '<tr style="background:#ecf0f1;font-weight:700;"><td style="padding:8px 0;">البيان</td><td>القيمة</td></tr>'
                            + '<tr><td style="padding:8px 0;">أيام الحضور</td><td>' + d.days_present + ' يوم</td></tr>'
                            + '<tr><td style="padding:8px 0;">أيام الغياب</td><td>' + d.days_absent + ' يوم</td></tr>'
                            + '<tr style="border-top:1px solid #ddd;"><td style="padding:8px 0;">الراتب الأساسي</td><td style="font-weight:600;">' + d.basic_salary + ' ر٫س</td></tr>'
                            + '<tr><td style="padding:8px 0;">الخصومات</td><td style="font-weight:600;color:#c0392b;">- ' + d.deductions + ' ر٫س</td></tr>'
                            + '<tr><td style="padding:8px 0;">المكافآت</td><td style="font-weight:600;color:#27ae60;">+ ' + d.bonuses + ' ر٫س</td></tr>'
                            + commission
                            + '<tr style="background:#2c3e50;color:#fff;"><td style="padding:10px 0;font-size:1.05rem;font-weight:700;">صافي الراتب</td><td style="font-size:1.15rem;font-weight:700;">' + d.total_salary + ' ر٫س</td></tr>'
                            + '</table></div>';
                    })
                    .catch(function() {
                        body.innerHTML = '<p class="text-danger text-center">حدث خطأ، أعد المحاولة</p>';
                    });
                });
            });

            // Details modal
            document.querySelectorAll('.details-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var url  = this.dataset.url;
                    var body = document.getElementById('detailsModalBody');
                    body.innerHTML = '<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';
                    document.getElementById('detailsEmpName').textContent = '';
                    document.getElementById('detailsPdfBtn').href = '#';
                    $('#detailsModal').modal('show');

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        document.getElementById('detailsEmpName').textContent = d.employee_name + ' - ' + d.month;
                        document.getElementById('detailsPdfBtn').href = d.pdf_url;

                        function emptyRow(cols, msg) {
                            return '<tr><td colspan="' + cols + '" class="text-center text-muted py-2">' + msg + '</td></tr>';
                        }

                        // summary card
                        var commissionRow = d.branch_type === 'training'
                            ? '<tr><td>عمولة الطلاب</td><td class="font-weight-bold text-success">+ ' + d.students_commission_total + ' ر.س</td><td colspan="2"></td></tr>'
                            : '';
                        var summary =
                            '<div class="card mb-3">'
                            + '<div class="card-header py-2 font-weight-bold" style="background:#2c3e50;color:#fff;">ملخص الراتب</div>'
                            + '<div class="card-body p-0"><table class="table table-sm mb-0">'
                            + '<tr><td>اسم الموظف</td><td class="font-weight-bold">' + d.employee_name + '</td><td>الوظيفة</td><td class="font-weight-bold">' + d.employee_job + '</td></tr>'
                            + '<tr><td>الفرع</td><td class="font-weight-bold">' + d.branch_name + '</td><td>القسم</td><td class="font-weight-bold">' + d.department_name + '</td></tr>'
                            + '<tr><td>الشهر</td><td class="font-weight-bold">' + d.month + '</td><td>أيام العمل</td><td class="font-weight-bold">' + d.monthly_working_days + ' يوم</td></tr>'
                            + '<tr><td>أيام الحضور</td><td class="font-weight-bold">' + d.days_present + ' يوم</td><td>أيام الغياب</td><td class="font-weight-bold">' + d.days_absent + ' يوم</td></tr>'
                            + '<tr><td>الراتب الأساسي</td><td class="font-weight-bold">' + d.basic_salary + ' ر.س</td><td>الخصومات</td><td class="font-weight-bold text-danger">- ' + d.deductions_total + ' ر.س</td></tr>'
                            + '<tr><td>المكافآت</td><td class="font-weight-bold text-success">+ ' + d.bonuses_total + ' ر.س</td><td colspan="2"></td></tr>'
                            + commissionRow
                            + '<tr style="background:#2c3e50;color:#fff;"><td colspan="3" class="font-weight-bold">صافي الراتب</td>'
                            + '<td class="font-weight-bold" style="font-size:1.05rem;">' + d.total_salary + ' ر.س</td></tr>'
                            + '</table></div></div>';

                        // bonuses
                        var bonusRows = d.bonuses.length
                            ? d.bonuses.map(function(b) { return '<tr><td>' + b.reason + '</td><td class="font-weight-bold text-success">+ ' + b.amount + ' ر.س</td></tr>'; }).join('')
                            : emptyRow(2, 'لا توجد مكافآت');

                        // deductions
                        var deductRows = d.deductions.length
                            ? d.deductions.map(function(dd) { return '<tr><td>' + dd.reason + '</td><td class="font-weight-bold text-danger">- ' + dd.amount + ' ر.س</td><td>' + dd.created_at + '</td></tr>'; }).join('')
                            : emptyRow(3, 'لا توجد خصومات');

                        // leaves
                        var leaveRows = d.leaves.length
                            ? d.leaves.map(function(l) { return '<tr><td>' + l.type + '</td><td>' + l.start + '</td><td>' + l.end + '</td><td>' + l.days + ' يوم</td><td>' + l.reason + '</td></tr>'; }).join('')
                            : emptyRow(5, 'لا توجد إجازات');

                        // students summary (training only)
                        var studentsSection = '';
                        if (d.branch_type === 'training') {
                            studentsSection =
                                '<h6 class="font-weight-bold mt-3 mb-1" style="color:#6f42c1;"><i class="fa fa-graduation-cap"></i> عمولة الطلاب</h6>'
                                + '<table class="table table-sm table-bordered mb-0">'
                                + '<thead class="thead-light"><tr><th>عدد الطلاب</th><th>العمولة لكل طالب</th><th>إجمالي العمولة</th></tr></thead>'
                                + '<tbody><tr>'
                                + '<td class="font-weight-bold">' + d.students_count + ' طالب</td>'
                                + '<td class="font-weight-bold">' + d.stu_commission_per + ' ر.س</td>'
                                + '<td class="font-weight-bold text-success">' + d.students_commission_total + ' ر.س</td>'
                                + '</tr></tbody></table>';
                        }

                        body.innerHTML =
                            '<div style="direction:rtl;">'
                            + summary
                            + '<h6 class="font-weight-bold mb-1" style="color:#27ae60;"><i class="fa fa-plus-circle"></i> المكافآت (' + d.bonuses.length + ')</h6>'
                            + '<table class="table table-sm table-bordered mb-3"><thead class="thead-light"><tr><th>السبب</th><th>المبلغ</th></tr></thead><tbody>' + bonusRows + '</tbody></table>'
                            + '<h6 class="font-weight-bold mb-1" style="color:#c0392b;"><i class="fa fa-minus-circle"></i> الخصومات (' + d.deductions.length + ')</h6>'
                            + '<table class="table table-sm table-bordered mb-3"><thead class="thead-light"><tr><th>السبب</th><th>المبلغ</th><th>تاريخ الأعتماد</th></tr></thead><tbody>' + deductRows + '</tbody></table>'
                            + '<h6 class="font-weight-bold mb-1" style="color:#17a2b8;"><i class="fa fa-calendar-minus-o"></i> الإجازات (' + d.leaves.length + ')</h6>'
                            + '<table class="table table-sm table-bordered mb-3"><thead class="thead-light"><tr><th>النوع</th><th>من</th><th>إلى</th><th>الأيام</th><th>السبب</th></tr></thead><tbody>' + leaveRows + '</tbody></table>'
                            + studentsSection
                            + '</div>';
                    })
                    .catch(function() {
                        body.innerHTML = '<p class="text-danger text-center">حدث خطأ، أعد المحاولة</p>';
                    });
                });
            });
        })();
    </script>
@endpush
