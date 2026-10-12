{{--
    Month dropdown (Y-m values). Used instead of <input type="month">, which Firefox renders without a calendar.
    Params: name, selected, id (optional), style (optional), required (optional), months_back (optional, default 24)
--}}
@php
    $selected   = $selected ?? now()->format('Y-m');
    $monthsBack = $months_back ?? 24;
    $months     = [];
    for ($i = 0; $i <= $monthsBack; $i++) {
        $months[] = now()->startOfMonth()->subMonths($i)->format('Y-m');
    }
    if ($selected && !in_array($selected, $months)) {
        $months[] = $selected;
        rsort($months);
    }
@endphp
<select name="{{ $name }}" @isset($id) id="{{ $id }}" @endisset class="form-control"
    @isset($style) style="{{ $style }}" @endisset @if (!empty($required)) required @endif>
    @foreach ($months as $m)
        <option value="{{ $m }}" {{ $m === $selected ? 'selected' : '' }}>
            {{ \Carbon\Carbon::parse($m . '-01')->locale('ar')->translatedFormat('F Y') }}
        </option>
    @endforeach
</select>
