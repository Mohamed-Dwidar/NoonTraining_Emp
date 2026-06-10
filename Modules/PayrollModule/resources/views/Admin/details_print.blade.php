<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تفاصيل كشف الراتب</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Tahoma, sans-serif;
            background: #fff;
            color: #222;
            padding: 20px;
            direction: rtl;
            font-size: 12px;
        }

        .doc-header {
            background: #2c3e50;
            color: #fff;
            padding: 12px 16px;
            margin-bottom: 12px;
        }

        .doc-header h1 {
            font-size: 15px;
            margin-bottom: 3px;
        }

        .doc-header .sub {
            font-size: 11px;
        }

        .emp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .emp-table td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
            width: 25%;
            background: #f5f7fa;
        }

        .emp-table .lbl {
            color: #666;
            font-size: 10px;
            display: block;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .summary-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .summary-table .total-row td {
            background: #2c3e50;
            color: #fff;
            font-weight: bold;
            font-size: 13px;
        }

        .neg {
            color: #c0392b;
        }

        .pos {
            color: #27ae60;
        }

        .sec-title-green {
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            background: #d4edda;
            color: #155724;
            border-bottom: 2px solid #28a745;
            margin-bottom: 4px;
            margin-top: 12px;
        }

        .sec-title-red {
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            background: #f8d7da;
            color: #721c24;
            border-bottom: 2px solid #dc3545;
            margin-bottom: 4px;
            margin-top: 12px;
        }

        .sec-title-blue {
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            background: #d1ecf1;
            color: #0c5460;
            border-bottom: 2px solid #17a2b8;
            margin-bottom: 4px;
            margin-top: 12px;
        }

        .sec-title-purple {
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            background: #e2d9f3;
            color: #3d1a7a;
            border-bottom: 2px solid #6f42c1;
            margin-bottom: 4px;
            margin-top: 12px;
        }

        .dtable {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .dtable th {
            background: #ecf0f1;
            padding: 5px 8px;
            text-align: right;
            border: 1px solid #ccc;
        }

        .dtable td {
            padding: 5px 8px;
            border: 1px solid #eee;
        }

        .dtable .empty-row td {
            text-align: center;
            color: #aaa;
        }

        .amt-pos {
            font-weight: bold;
            color: #27ae60;
        }

        .amt-neg {
            font-weight: bold;
            color: #c0392b;
        }

        .commission-box {
            background: #d4edda;
            color: #155724;
            padding: 6px 10px;
            font-size: 12px;
            margin-top: 6px;
            border: 1px solid #c3e6cb;
        }

        .footer {
            margin-top: 14px;
            font-size: 10px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 6px;
        }
    </style>
</head>

<body>

    @php
        $isTraining = $emp?->branch?->type === 'training';
    @endphp

    <div class="doc-header">
        <h1>تفاصيل كشف الراتب</h1>
        <div class="sub">{{ $payroll->month }}</div>
    </div>

    {{-- Employee info --}}
    <table class="emp-table">
        <tr>
            <td colspan="2"><span class="lbl">اسم الموظف</span><strong>{{ $emp->name ?? '—' }}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><span class="lbl">الوظيفة</span><strong>{{ $emp->job ?? '—' }}</strong></td>
        </tr>
        <tr>
            <td><span class="lbl">الفرع</span><strong>{{ $emp->branch?->name ?? '—' }}</strong></td>
            <td><span class="lbl">القسم</span><strong>{{ $emp->department?->name ?? '—' }}</strong></td>
        </tr>
    </table>

    {{-- Salary summary --}}
    <table class="summary-table">
        <tr>
            <td>أيام العمل الشهرية</td>
            <td><strong>{{ $payroll->monthly_working_days }} يوم</strong></td>

            <td>أيام الحضور</td>
            <td><strong>{{ $payroll->days_present }} يوم</strong></td>

            <td>أيام الغياب</td>
            <td><strong>{{ $payroll->days_absent }} يوم</strong></td>
        </tr>
        <tr>
            <td>الراتب الأساسي</td>
            <td><strong>{{ number_format($payroll->basic_salary, 2) }} ر.س</strong></td>

            <td colspan="4"></td>
        </tr>
        <tr>
            <td>الخصومات</td>
            <td><strong class="neg">- {{ number_format($payroll->deductions, 2) }} ر.س</strong></td>
            <td>المكافآت</td>
            <td><strong class="pos">+ {{ number_format($payroll->bonuses, 2) }} ر.س</strong></td>
            @if ($isTraining)
                <td>عمولة الطلاب</td>
                <td><strong class="pos">+ {{ number_format($payroll->students_commission, 2) }} ر.س</strong></td>
            @else
                <td colspan="2"></td>
            @endif
        </tr>
        <tr class="total-row">
            <td colspan="5">صافي الراتب</td>
            <td>{{ number_format($payroll->total_salary, 2) }} ر.س</td>
        </tr>
    </table>

    {{-- Bonuses --}}
    <div class="sec-title-green">المكافآت ({{ $bonuses->count() }})</div>
    <table class="dtable">
        <thead>
            <tr>
                <th>السبب</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bonuses as $b)
                <tr>
                    <td>{{ $b->reason }}</td>
                    <td class="amt-pos">+ {{ number_format($b->amount, 2) }} ر.س</td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="2">لا توجد مكافآت</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Deductions --}}
    <div class="sec-title-red">الخصومات ({{ $deductions->count() }})</div>
    <table class="dtable">
        <thead>
            <tr>
                <th>السبب</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deductions as $d)
                <tr>
                    <td>{{ $d->reason }}</td>
                    <td class="amt-neg">- {{ number_format($d->amount, 2) }} ر.س</td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="2">لا توجد خصومات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Leaves --}}
    <div class="sec-title-blue">الإجازات ({{ $leaves->count() }})</div>
    <table class="dtable">
        <thead>
            <tr>
                <th>النوع</th>
                <th>من</th>
                <th>إلى</th>
                <th>الأيام</th>
                <th>السبب</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leaves as $l)
                <tr>
                    <td>{{ $l->type }}</td>
                    <td>{{ $l->start_date ? $l->start_date->format('Y-m-d') : '—' }}</td>
                    <td>{{ $l->end_date ? $l->end_date->format('Y-m-d') : '—' }}</td>
                    <td>{{ $l->days }} يوم</td>
                    <td>{{ $l->reason ?? '—' }}</td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="5">لا توجد إجازات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Students — training only --}}
    @if ($isTraining)
        <div class="sec-title-purple">عمولة الطلاب</div>
        <table class="dtable">
            <thead>
                <tr>
                    <th>عدد الطلاب</th>
                    <th>العمولة لكل طالب</th>
                    <th>إجمالي العمولة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $students->count() }} طالب</strong></td>
                    <td><strong>{{ number_format((float) $emp->stu_commission, 2) }} ر.س</strong></td>
                    <td class="amt-pos">{{ number_format($payroll->students_commission, 2) }} ر.س</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">تم إصدار هذا التقرير بتاريخ {{ now()->format('Y-m-d') }}</div>

</body>

</html>
