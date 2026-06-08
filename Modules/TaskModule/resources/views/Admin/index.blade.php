@extends('layoutmodule::layouts.layout_main')

@section('title')
    التكليمات و المهام
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between w-100">
                <h3><i class="fa fa-tasks"></i> &nbsp; التكليمات و المهام</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        {{-- Filter --}}
        <div class="card mb-2">
            <div class="card-body py-2 px-2">
                <form method="GET" action="{{ route('admin.tasks.index') }}">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الفرع</strong></label>
                            <select name="branch_id" id="branchSelect" class="form-control">
                                <option value="">-- كل الفروع --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
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
                        <div class="col-md-3">
                            <label class="d-block mb-1"><strong>الحالة</strong></label>
                            <select name="status" class="form-control">
                                <option value="">-- كل الحالات --</option>
                                @foreach ($statuses as $val => $label)
                                    <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="d-block mb-1">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tasks table --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12" style="text-align: left;">
                        <a class="btn btn-success round btn-min-width mr-1 mb-1" href="{{ route('admin.tasks.create') }}">
                            <i class="fa fa-plus"></i> إضافة مهمة
                        </a>

                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($tasks->isEmpty())
                    <p class="text-center py-4 mt-2 text-muted">لا توجد مهام</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>الموظف</th>
                                    <th>المهمة</th>
                                    <th>من</th>
                                    <th>إلى</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $i => $task)
                                    @php $emp = $task->employee; @endphp
                                    <tr>
                                        <td>
                                            <div class="strong">{{ $emp->name ?? '—' }}</div>
                                            <small class="text-muted">
                                                {{ $emp->branch->name ?? '' }}
                                                @if ($emp->department)
                                                    &nbsp;/&nbsp; {{ $emp->department->name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $task->title }}</strong>
                                            @if ($task->details)
                                                <br><small class="text-muted">{{ Str::limit($task->details, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $task->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $task->end_date->format('Y-m-d') }}</td>
                                        <td style="width: 150px;">
                                            {{-- Quick status change --}}
                                            <form method="POST"
                                                action="{{ route('admin.tasks.update-status', $task->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status"
                                                    class="form-control form-control-sm status-select status-{{ $task->status }}"
                                                    onchange="this.form.submit()" style="min-width:130px;">
                                                    @foreach ($statuses as $val => $label)
                                                        <option value="{{ $val }}"
                                                            {{ $task->status === $val ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}"
                                                class="d-inline" onsubmit="return confirm('هل تريد حذف هذه المهمة؟')">
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

@push('styles')
    <style>
        .status-select {
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
        }

        .status-new {
            background-color: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }

        .status-in_progress {
            background-color: #ffc107;
            color: #212529;
            border-color: #ffc107;
        }

        .status-completed {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .status-late {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .status-select option {
            background: #fff;
            color: #212529;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($g) => $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));

            var branchSelect = document.getElementById('branchSelect');
            var deptSelect = document.getElementById('deptSelect');
            var selectedDept = {{ $deptId ? (int) $deptId : 'null' }};

            function populateDepts(branchId) {
                var opts = '<option value="">-- كل الأقسام --</option>';
                var list = deptsByBranch[branchId] || [];
                list.forEach(function(d) {
                    var sel = (selectedDept && d.id === selectedDept) ? ' selected' : '';
                    opts += '<option value="' + d.id + '"' + sel + '>' + d.name + '</option>';
                });
                deptSelect.innerHTML = opts;
            }

            branchSelect.addEventListener('change', function() {
                selectedDept = null;
                populateDepts(this.value);
            });

            if (branchSelect.value) populateDepts(branchSelect.value);
        })();
    </script>
@endpush
