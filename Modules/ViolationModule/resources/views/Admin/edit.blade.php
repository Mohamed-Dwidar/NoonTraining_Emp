@extends('layoutmodule::layouts.layout_main')

@section('title') تعديل نوع مخالفة @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-edit"></i> &nbsp; تعديل نوع مخالفة</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('admin.violations.update', $violation->id) }}">
                @csrf

                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <label for="name">اسم المخالفة <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $violation->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <label for="description">الوصف</label>
                        <div class="form-group">
                            <textarea id="description" name="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $violation->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ─── Deduction tiers ─── --}}
                <hr>
                <h5 class="mb-3"><i class="fa fa-list-ol"></i> &nbsp; الخصم حسب عدد التكرار</h5>

                @error('repeats')
                    <div class="alert alert-danger py-1 mb-2">{{ $message }}</div>
                @enderror

                @php
                    $existingRepeats = old('repeats')
                        ? old('repeats')
                        : $violation->repeats->map(fn($r) => [
                            'repeat_number'    => $r->repeat_number,
                            'deduction_amount' => $r->deduction_amount,
                          ])->toArray();

                    if (empty($existingRepeats)) {
                        $existingRepeats = [['repeat_number' => 1, 'deduction_amount' => '']];
                    }
                @endphp

                <table style="border-collapse:separate; border-spacing:0 6px;">
                    <thead>
                        <tr>
                            <th style="padding:0 12px 4px 0; font-size:0.8rem; color:#6c757d; font-weight:500;">المرة</th>
                            <th style="padding:0 8px 4px 0; font-size:0.8rem; color:#6c757d; font-weight:500;">مبلغ الخصم</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="repeatsContainer">
                        @foreach($existingRepeats as $i => $row)
                        @php $isLast = $loop->last; @endphp
                        <tr class="repeat-row">
                            <td style="padding:0 12px 0 0; white-space:nowrap;">
                                <span class="badge badge-primary rpt-label"
                                      style="min-width:72px; font-size:0.8rem; padding:5px 10px; text-align:center;">
                                    المرة {{ $row['repeat_number'] }}{{ $isLast ? '+' : '' }}
                                </span>
                                <input type="hidden" name="repeats[{{ $i }}][repeat_number]"
                                       class="rpt-num-input" value="{{ $row['repeat_number'] }}">
                            </td>
                            <td style="padding:0 8px 0 0;">
                                <div class="input-group input-group-sm" style="width:160px;">
                                    <input type="number" name="repeats[{{ $i }}][deduction_amount]"
                                           class="form-control rpt-amount"
                                           step="0.01" min="0"
                                           value="{{ $row['deduction_amount'] }}"
                                           placeholder="0.00" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ر.س</span>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:0;">
                                <button type="button" class="btn btn-sm remove-repeat"
                                        style="{{ $i === 0 ? 'display:none;' : '' }} border:none; color:#dc3545; padding:4px 6px;">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-2">
                    <button type="button" id="addRepeat" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-plus"></i> إضافة تكرار
                    </button>
                    <small class="text-muted mr-2">آخر صف يغطي هذا الرقم وما فوقه</small>
                </div>

                <div class="col-12 mt-4">
                    {{-- <a href="{{ route('admin.violations.index') }}" class="btn btn-secondary">إلغاء</a> --}}
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var tbody = document.getElementById('repeatsContainer');

    function getRows() { return tbody.querySelectorAll('.repeat-row'); }

    function reindex() {
        var rows = getRows();
        rows.forEach(function (row, i) {
            var num    = i + 1;
            var isLast = i === rows.length - 1;
            row.querySelector('.rpt-label').textContent      = 'المرة ' + num + (isLast ? '+' : '');
            row.querySelector('.rpt-num-input').name         = 'repeats[' + i + '][repeat_number]';
            row.querySelector('.rpt-num-input').value        = num;
            row.querySelector('.rpt-amount').name            = 'repeats[' + i + '][deduction_amount]';
            row.querySelector('.remove-repeat').style.display = i === 0 ? 'none' : '';
        });
    }

    document.getElementById('addRepeat').addEventListener('click', function () {
        var i   = getRows().length;
        var num = i + 1;
        var tr  = document.createElement('tr');
        tr.className = 'repeat-row';
        tr.innerHTML =
            '<td style="padding:0 12px 0 0; white-space:nowrap;">' +
                '<span class="badge badge-primary rpt-label" style="min-width:72px;font-size:0.8rem;padding:5px 10px;text-align:center;">المرة ' + num + '+</span>' +
                '<input type="hidden" name="repeats[' + i + '][repeat_number]" class="rpt-num-input" value="' + num + '">' +
            '</td>' +
            '<td style="padding:0 8px 0 0;">' +
                '<div class="input-group input-group-sm" style="width:160px;">' +
                    '<input type="number" name="repeats[' + i + '][deduction_amount]" class="form-control rpt-amount" step="0.01" min="0" placeholder="0.00" required>' +
                    '<div class="input-group-append"><span class="input-group-text">ر.س</span></div>' +
                '</div>' +
            '</td>' +
            '<td style="padding:0;">' +
                '<button type="button" class="btn btn-sm remove-repeat" style="border:none;color:#dc3545;padding:4px 6px;"><i class="fa fa-trash-o"></i></button>' +
            '</td>';
        tbody.appendChild(tr);
        reindex();
    });

    tbody.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-repeat');
        if (btn && getRows().length > 1) {
            btn.closest('.repeat-row').remove();
            reindex();
        }
    });
})();
</script>
@endpush
