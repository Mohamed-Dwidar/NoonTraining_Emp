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

        <div class="card">

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody>
                                @forelse($settings as $setting)
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
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            {{ __('messages.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($settings->isNotEmpty())
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                @endif
            </form>
        </div>

    </div>
@endsection

@push('scripts')
@endpush




<?php /*
@extends('layoutmodule::layouts.layout_main')

@section('title')
    {{ __('messages.settings') }}
@endsection

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ __('messages.settings') }}</h4>
                {{-- <a class="btn btn-primary" href="{{ route('admin.settings.create') }}">
                <i class="ti ti-plus me-1"></i>{{ __('messages.add_new') }}
            </a> --}}
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>{{ __('messages.save') }}
                </button>
            </div>
        </div>



        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    {{-- <thead>
                        <tr>
                            <th style="width:100px"></th>
                            <th>{{ __('messages.key') }}</th>
                        </tr>
                    </thead> --}}
                    <tbody>
                        @forelse($settings as $setting)
                            <tr>
                                <td style="width:100px">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="settings[{{ $setting->id }}]" name="settings[{{ $setting->id }}]"
                                            value="1" {{ $setting->value == '1' ? 'checked' : '' }}
                                            style="width:2.5rem; height:1.3rem; cursor:pointer;">
                                    </div>
                                </td>
                                <td><label
                                        for="settings[{{ $setting->id }}]">{{ $setting->label ?? $setting->key }}</label>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    {{ __('messages.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($settings->isNotEmpty())
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>{{ __('messages.save') }}
                </button>
            </div>
        @endif

    </form>
@endsection

*/
?>
