<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف راتب</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Tahoma, sans-serif;
            background: #fff;
            color: #222;
            padding: 30px;
            direction: rtl;
            font-size: 13px;
        }
        .payslip {
            max-width: 680px;
            margin: 0 auto;
            border: 2px solid #2c3e50;
        }
        .payslip-header {
            background: #2c3e50;
            color: #fff;
            padding: 16px 20px;
        }
        .payslip-header h1 { font-size: 18px; margin-bottom: 4px; }
        .payslip-header .month { font-size: 13px; opacity: .85; }
        .emp-info {
            padding: 14px 20px;
            background: #f5f7fa;
            border-bottom: 1px solid #dde;
        }
        .emp-info table { width: 100%; border-collapse: collapse; }
        .emp-info td { padding: 4px 6px; font-size: 13px; width: 50%; }
        .emp-info .label { color: #666; font-size: 11px; display: block; }
        .section-title {
            background: #ecf0f1;
            padding: 7px 20px;
            font-weight: bold;
            font-size: 12px;
            color: #2c3e50;
            border-bottom: 1px solid #dde;
        }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table td { padding: 8px 20px; border-bottom: 1px solid #eee; font-size: 13px; }
        .detail-table td.amount { text-align: left; font-weight: bold; }
        .detail-table .deduction .amount { color: #c0392b; }
        .detail-table .bonus .amount     { color: #27ae60; }
        .total-row { background: #2c3e50; color: #fff; }
        .total-row td { padding: 12px 20px; font-size: 15px; font-weight: bold; color: #fff; }
        .total-row td.amount { font-size: 17px; color: #fff; text-align: left; }
        .footer {
            padding: 10px 20px;
            font-size: 11px;
            color: #888;
            text-align: center;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

@php
    $emp        = $payroll->employee;
    $isTraining = $emp?->branch?->type === 'training';
@endphp

<div class="payslip">

    <div class="payslip-header">
        <h1>كشف الراتب</h1>
        <div class="month">{{ $payroll->month }} &nbsp;</div>
    </div>

    <div class="emp-info">
        <table>
            <tr>
                <td>
                    <span class="label">اسم الموظف</span>
                    <strong>{{ $emp->name ?? '—' }}</strong>
                </td>
                <td>
                    <span class="label">الوظيفة</span>
                    <strong>{{ $emp->job ?? '—' }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">الفرع</span>
                    <strong>{{ $emp->branch?->name ?? '—' }}</strong>
                </td>
                <td>
                    <span class="label">القسم</span>
                    <strong>{{ $emp->department?->name ?? '—' }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">الحضور والغياب</div>
    <table class="detail-table">
        <tr>
            <td>أيام العمل الشهرية</td>
            <td class="amount">{{ $payroll->monthly_working_days }} يوم</td>
        </tr>
        <tr>
            <td>أيام الحضور</td>
            <td class="amount">{{ $payroll->days_present }} يوم</td>
        </tr>
        <tr>
            <td>أيام الغياب</td>
            <td class="amount">{{ $payroll->days_absent }} يوم</td>
        </tr>
    </table>

    <div class="section-title">تفاصيل الراتب</div>
    <table class="detail-table">
        <tr>
            <td>الراتب الأساسي</td>
            <td class="amount">{{ number_format($payroll->basic_salary, 2) }} ر.س</td>
        </tr>
        <tr class="deduction">
            <td>الخصومات</td>
            <td class="amount">- {{ number_format($payroll->deductions, 2) }} ر.س</td>
        </tr>
        <tr class="bonus">
            <td>المكافآت</td>
            <td class="amount">+ {{ number_format($payroll->bonuses, 2) }} ر.س</td>
        </tr>
        @if ($isTraining)
        <tr class="bonus">
            <td>عمولة الطلاب</td>
            <td class="amount">+ {{ number_format($payroll->students_commission, 2) }} ر.س</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>صافي الراتب</td>
            <td class="amount">{{ number_format($payroll->total_salary, 2) }} ر.س</td>
        </tr>
    </table>

    <div class="footer">
        تم إصدار هذا الكشف بتاريخ {{ now()->format('Y-m-d') }}
    </div>

</div>

</body>
</html>
