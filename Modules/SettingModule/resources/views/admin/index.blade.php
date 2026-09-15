@extends('layoutmodule::layouts.layout_main')

@section('title')
    اعدادات النظام العامة
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <h3><i class="fa fa-cogs"></i> &nbsp; اعدادات النظام العامة</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        @php
            $typeLabels = [
                'general'          => 'إعدادات عامة',
                // 'course_commision' => 'عمولات الدورات',
            ];
        @endphp

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            @forelse($settings->groupBy('type') as $type => $group)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $typeLabels[$type] ?? $type }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach ($group as $setting)
                                        <tr>
                                            <td style="width:180px">
                                                <div class="form-check form-switch">
                                                    <input type="number" id="settings[{{ $setting->key }}]"
                                                        name="settings[{{ $setting->key }}]" step="0.01" min="0"
                                                        class="form-control form-control-sm"
                                                        value="{{ old('settings[' . $setting->key . ']', $setting->value) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <label
                                                    for="settings[{{ $setting->key }}]">{{ $setting->label ?? $setting->key }}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center text-muted py-4">
                        {{ __('messages.no_data') }}
                    </div>
                </div>
            @endforelse

            @if ($settings->isNotEmpty())
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            @endif
        </form>

    </div>
@endsection

@push('scripts')
@endpush
