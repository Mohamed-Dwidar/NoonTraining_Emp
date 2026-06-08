<table class="table mb-0">
    <thead>
        <tr class="head">
            <th style="font-weight: bold;text-align: right">المستخدم</th>
            <th style="font-weight: bold;text-align: right">المستخدم</th>
            <th style="font-weight: bold;text-align: right">الفرع</th>
            <th style="font-weight: bold;text-align: right">نوع العملية</th>
            <th style="font-weight: bold;text-align: right">وصف العملية</th>
            <th style="font-weight: bold;text-align: right">الوقت / التاريخ</th>

        </tr>
    </thead>

    <tbody>
        @foreach ($logs as $log)
            <tr>
               <td>
                    {{ $log->created_at->format('Y-m-d | H:i') }}
                </td>
                <td class="strong">
                    {{ $log->userable->name }}
                </td>
                <td>
                    @if ($log->userable && $log->userable->branch)
                        {{ $log->userable->branch->name }}
                    @else
                        ---
                    @endif
                </td>
                <td>
                    {{ $log->action }}
                </td>

                <td>
                    {{ $log->description }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
