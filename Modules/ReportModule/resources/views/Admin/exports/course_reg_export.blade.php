<table class="table mb-0">
    <thead>
        <tr class="head">
            <th style="font-weight: bold">الطالب</th>
            <th style="font-weight: bold">الدورة</th>
            <th style="font-weight: bold">رقم الهوية</th>
            <th style="font-weight: bold">تاريخ انتهاء الهوية</th>
            <th style="font-weight: bold">الفرع</th>
            <th style="font-weight: bold">الجوال</th>
            <th style="font-weight: bold">البريد الإلكتروني</th>
            <th style="font-weight: bold">تاريخ الميلاد</th>
            <th style="font-weight: bold">قطاع العمل</th>
            <th style="font-weight: bold">المؤهل الدراسي</th>
            <th style="font-weight: bold">السعر المتفق عليه</th>
            <th style="font-weight: bold">المدفوع</th>
            <th style="font-weight: bold">الباقي</th>
            <th style="font-weight: bold; text-align: center">الحالة</th>
            <th>مسجل عن طريق</th>
            <th style="font-weight: bold; text-align: center">استلم الشهادة</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($courses_regs as $reg)
            <tr>
                <td class="strong">
                    <label for="{{ $reg->id }}">
                        <a
                            href="{{ route(Auth::getDefaultDriver() . '.students.view', $reg->student->id) }}">{{ $reg->student->name }}</a>
                    </label>
                </td>
                <td class="strong">
                    <label>
                        <a
                            href="{{ route(Auth::getDefaultDriver() . '.students.view', $reg->student->id) }}">{{ $reg->course->FullName }}</a>
                    </label>
                </td>
                <td>
                    <label>{{ $reg->student->id_nu }}</label>
                </td>
                <td>
                    <label>{{ $reg->student->id_expire_date ? \Carbon\Carbon::parse($reg->student->id_expire_date)->format('d-m-Y') : '' }}</label>
                </td>
                <td>
                    <label>{{ $reg->course->branch->name }}</label>
                </td>
                <td>
                    <label>{{ $reg->student->phone1 }}</label>
                </td>
                <td>
                    <label>{{ $reg->student->email }}</label>
                </td>
                <td>
                    <label>{{ $reg->student->birthdate ? $reg->student->birthdate : '' }}</label>
                </td>
                <td>
                    <label>{{ $reg->student->company}}</label>
                </td>
                <td>
                    <label>{{ $reg->student->academic_qualification }}</label>
                </td>
                <td>
                    {{ $reg->price }}
                </td>
                <td>{{ number_format($reg->coursePaidAmount, 2) }}</td>
                <td>
                    @if ($reg->is_free == 1)
                        0.00
                    @else
                        {{ number_format($reg->price - $reg->coursePaidAmount, 2) }}
                    @endif
                </td>
                <td class="align-center" style="font-weight:bold; background-color: {{ $reg->status->color }} ">
                    @if ($reg->is_leave == 1)
                        [ مغادر ] &nbsp;&nbsp;&nbsp;
                    @endif
                    {{ $reg->status->status }}
                </td>
                <td>
                    <label> {{ $reg->registered_by }}</label>
                </td>
                <td class="align-center">
                    @if ($reg->is_recive_cert == 0)
                        لا
                    @else
                        نعم
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
