{{--
    Month dropdown (Y-m values) from 2026-01 up to the current month, newest first.
    Used instead of <input type="month">, which Firefox renders without a calendar.
    Params (all optional): name, selected, id, class, style, required
--}}
@php
    $currentMonth = now()->format('Y-m');
    $selected     = $selected ?? $currentMonth;
    // Future months are never offered; fall back to the current month.
    if ($selected > $currentMonth) {
        $selected = $currentMonth;
    }
    $months = [];
    for ($m = $currentMonth; $m >= '2026-01'; $m = date('Y-m', strtotime($m . '-01 -1 month'))) {
        $months[] = $m;
    }
    // Keep an older existing value outside the range (e.g. an older record being edited).
    if ($selected && !in_array($selected, $months)) {
        $months[] = $selected;
        rsort($months);
    }
@endphp
<select @isset($name) name="{{ $name }}" @endisset @isset($id) id="{{ $id }}" @endisset
    class="form-control {{ $class ?? '' }}" @isset($style) style="{{ $style }}" @endisset
    @if (!empty($required)) required @endif>
    @foreach ($months as $m)
        <option value="{{ $m }}" {{ $m === $selected ? 'selected' : '' }}>
            {{ \Carbon\Carbon::parse($m . '-01')->locale('ar')->translatedFormat('F Y') }}
        </option>
    @endforeach
</select>
