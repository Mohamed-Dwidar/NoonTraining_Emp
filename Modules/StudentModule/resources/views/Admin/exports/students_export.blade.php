<table class="table mb-0">
    <thead>
        <tr class="head">
            <th style="font-weight: bold">الطالب</th>
            <th style="font-weight: bold">رقم الجوال</th>
            <th style="font-weight: bold">اسم الدورة</th>
            <th style="font-weight: bold">المبلغ كامل</th>
            <th style="font-weight: bold">المدفوع</th>
            <th style="font-weight: bold">طريقة الدفع</th>
            <th style="font-weight: bold">تاريخ الدفع</th>
            <th style="font-weight: bold">الطالب سابقاً تبع مين</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)
            <tr>
                <td class="strong">
                    <label>
                        {{ $student->name }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ $student->mobile }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ $student->course_name }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ number_format($student->total_amount) }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ number_format($student->paid_amount) }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ $student->payment_method }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ $student->payment_date }}
                    </label>
                </td>
                <td class="strong">
                    <label>
                        {{ $student->previous_student_of }}
                    </label>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
