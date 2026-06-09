@extends('layoutmodule::admin.main')

@section('title')
    {{ __('messages.settings') }}
@endsection

@push('styles')
@endpush

@section('content')
    <div class="card-header">
        <h4>{{ __('messages.edit_setting') }}</h4>
    </div>
    <form class="" method="POST" action='{{ route('admin.settings.update', $setting->id) }}'
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $setting->id }}">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <label class="form-label" for="key">{{ __('messages.key') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="key" name="key"
                        value="{{ old('key', $setting->key) }}" placeholder="{{ __('messages.key') }}">
                    @error('key')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label" for="value">{{ __('messages.value') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="value" name="value"
                        value="{{ old('value', $setting->value) }}" placeholder="{{ __('messages.value') }}">
                    @error('value')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>


        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary me-2"> {{ __('messages.save') }} </button>
        </div>
    </form>
    @push('scripts')
    @endpush
@endsection
