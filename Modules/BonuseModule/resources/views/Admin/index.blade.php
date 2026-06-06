@extends('layoutmodule::layouts.layout_main')

@section('title') المكافآت @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <div class="d-flex align-items-center justify-content-between">
            <h3><i class="fa fa-gift"></i> &nbsp; المكافآت</h3>
            <a href="{{ route('admin.bonuses.create', ['month' => $month]) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> &nbsp; إضافة مكافأة
            </a>
        </div>
    </div>

    @include('layoutmodule::layouts.flash')

    {{-- ─── Filter bar ─── --}}
    <div class="card mb-2">
        <div class="card-body py-2 px-2">
            <form method="GET" action="{{ route('admin.bonuses.index') }}" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="d-block mb-1"><strong>الشهر</strong></label>
                        <input type="month" name="month" class="form-control"
                               value="{{ $month }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block mb-1"><strong>الفرع</strong></label>
                        <select name="branch_id" id="branchSelect" class="form-control">
                            <option value="">-- كل الفروع --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ $branchId == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block mb-1"><strong>القسم</strong></label>
                        <select name="department_id" id="deptSelect" class="form-control">
                            <option value="">-- كل الأقسام --</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="d-block mb-1">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search"></i> عرض
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Bonuses table ─── --}}
    <div class="card">
        <div class="card-body p-0">
            @if($bonuses->isEmpty())
                <p class="text-center py-4 mt-2 text-muted">لا توجد مكافآت لهذا الشهر</p>
            @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr class="head">
                            <th>#</th>
                            <th>الموظف</th>
                            <th>الشهر</th>
                            <th>المبلغ</th>
                            <th>السبب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($bonuses as $i => $bonuse)
                        @php $emp = $bonuse->employee; @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="strong">{{ $emp->name ?? '—' }}</div>
                                <small class="text-muted">
                                    {{ $emp->branch->name ?? '' }}
                                    @if($emp->department) &nbsp;/&nbsp; {{ $emp->department->name }} @endif
                                </small>
                            </td>
                            <td>{{ $bonuse->month }}</td>
                            <td>
                                <span class="badge badge-success">
                                    {{ number_format($bonuse->amount, 2) }} ر.س
                                </span>
                            </td>
                            <td style="max-width:200px; white-space:normal;">
                                {{ $bonuse->reason ?? '—' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.bonuses.edit', $bonuse->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.bonuses.destroy', $bonuse->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('هل تريد حذف هذه المكافأة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var deptsByBranch = @json(
        $departments->groupBy('branch_id')->map(fn($g) =>
            $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()
        )
    );

    var branchSelect  = document.getElementById('branchSelect');
    var deptSelect    = document.getElementById('deptSelect');
    var selectedDept  = {{ $deptId ? (int)$deptId : 'null' }};

    function populateDepts(branchId) {
        var opts = '<option value="">-- كل الأقسام --</option>';
        var list = deptsByBranch[branchId] || [];
        list.forEach(function (d) {
            var sel = (selectedDept && d.id === selectedDept) ? ' selected' : '';
            opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
        });
        deptSelect.innerHTML = opts;
    }

    branchSelect.addEventListener('change', function () {
        selectedDept = null;
        populateDepts(this.value);
    });

    if (branchSelect.value) populateDepts(branchSelect.value);
})();
</script>
@endpush
