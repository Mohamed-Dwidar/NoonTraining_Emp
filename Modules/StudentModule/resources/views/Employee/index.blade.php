@extends('layoutmodule::layouts.layout_main')

@section('title')
    الطلاب
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        <div class="content-header mb-2">
            <div class="d-flex align-items-center justify-content-between w-100">
                <h3><i class="fa fa-graduation-cap"></i> &nbsp; الطلاب</h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')


        {{-- Students table --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <form method="GET" action="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                        class="d-flex flex-wrap gap-2 align-items-center">
                        <div class="col-lg-2">
                            <input type="text" name="search" id="searchInput" class="form-control"
                                placeholder="بحث عن طالب..." value="{{ request()->query('search') }}" style="width: 220px">
                        </div>
                        <div class="col-lg-2">
                            <input type="month" name="month" class="form-control"
                                value="{{ request()->query('month', now()->format('Y-m')) }}" style="width: 200px">
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-outline-secondary" type="submit">بحث</button>
                            <button class="btn btn-outline-danger" type="button" id="clearBtn">مسح</button>
                        </div>
                        <div class="col-lg-5" style="text-align: left">
                            <button class="btn btn-primary round btn-min-width mr-1 mb-1" data-toggle="modal"
                                data-target="#importModal" type="button">استيراد طلاب جدد</button>

                            {{-- <a id="exportBtn" class="btn btn-success round btn-min-width mr-1 mb-1" href="#">
                            <i class="fa fa-file-excel-o"></i>
                            تصدير ملف اكسل
                        </a> --}}
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($students->isEmpty())
                    <p class="text-center py-4 mt-2 text-muted">لا يوجد طلاب</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>اسم الطالب</th>
                                    <th>الجوال</th>
                                    <th>الكورس</th>
                                    <th>الإجمالي</th>
                                    <th>المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>طريقة الدفع</th>
                                    <th>تاريخ الدفع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $i => $student)
                                    @php $emp = $student->employee; @endphp
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->mobile }}</td>
                                        <td>{{ $student->course_name }}</td>
                                        <td>{{ number_format($student->total_amount) }}</td>
                                        <td>
                                            <span
                                                class="badge badge-success">{{ number_format($student->paid_amount) }}</span>
                                        </td>
                                        <td>
                                            @php $remaining = $student->remainingAmount(); @endphp
                                            <span class="badge badge-{{ $remaining > 0 ? 'danger' : 'secondary' }}">
                                                {{ number_format($remaining) }}
                                            </span>
                                        </td>
                                        <td>{{ $student->payment_method }}</td>
                                        <td>{{ $student->payment_date }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('employee.students.destroy', $student->id) }}"
                                                class="d-inline" onsubmit="return confirm('هل تريد حذف هذا الطالب؟')">
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
                    <div class="mt-3">
                        {{ $students->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Import Students Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">استيراد الطلاب من ملف Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="importForm" action="{{ route(Auth::getDefaultDriver() . '.students.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div id="formContent">
                            {{-- Employee cascade --}}
                            {{-- <div class="form-group">
                                <label for="modalBranch">الفرع <span class="text-danger">*</span></label>
                                <select id="modalBranch" class="form-control">
                                    <option value="">-- اختر الفرع --</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modalDept">القسم <span class="text-danger">*</span></label>
                                <select id="modalDept" class="form-control" disabled>
                                    <option value="">-- اختر الفرع أولاً --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modalEmployee">الموظف المسؤول <span class="text-danger">*</span></label>
                                <select id="modalEmployee" name="employee_id" class="form-control" disabled required>
                                    <option value="">-- اختر القسم أولاً --</option>
                                </select>
                            </div>
                            <hr> --}}
                            <div class="form-group">
                                <label for="fileInput">اختر ملف Excel لاستيراد الطلاب</label>
                                <input type="file" name="file" id="fileInput" class="form-control"
                                    accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">الصيغ المدعومة: (.xlsx, .xls)</small>
                                <a href="{{ asset('imports/students_template.xlsx') }}"
                                    class="btn btn-sm btn-info mt-2 badge info" style="color: #ffffff !important;" download>
                                    تحميل نموذج الملف</a>
                            </div>
                        </div>
                        <div id="uploadingMessage" style="display: none;" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">جاري التحميل...</span>
                            </div>
                            <p class="mt-3">جاري رفع الملف واستيراد الطلاب...</p>
                        </div>
                    </div>
                    <div class="modal-footer" id="modalFooter">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Modal employee cascade
        (function() {
            var deptsByBranch = @json($departments->groupBy('branch_id')->map(fn($g) => $g->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()));
            var empsByDept = @json($employees->groupBy('department_id')->map(fn($g) => $g->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()));

            var modalBranch = document.getElementById('modalBranch');
            var modalDept = document.getElementById('modalDept');
            var modalEmployee = document.getElementById('modalEmployee');

            modalBranch.addEventListener('change', function() {
                var list = deptsByBranch[this.value] || [];
                if (!this.value || list.length === 0) {
                    modalDept.innerHTML = '<option value="">-- اختر الفرع أولاً --</option>';
                    modalDept.disabled = true;
                    modalEmployee.innerHTML = '<option value="">-- اختر القسم أولاً --</option>';
                    modalEmployee.disabled = true;
                    return;
                }
                var opts = '<option value="">-- اختر القسم --</option>';
                list.forEach(function(d) {
                    opts += '<option value="' + d.id + '">' + d.name + '</option>';
                });
                modalDept.innerHTML = opts;
                modalDept.disabled = false;
                modalEmployee.innerHTML = '<option value="">-- اختر القسم أولاً --</option>';
                modalEmployee.disabled = true;
            });

            modalDept.addEventListener('change', function() {
                var list = empsByDept[this.value] || [];
                if (!this.value || list.length === 0) {
                    modalEmployee.innerHTML = '<option value="">لا يوجد موظفون</option>';
                    modalEmployee.disabled = true;
                    return;
                }
                var opts = '<option value="">-- اختر الموظف --</option>';
                list.forEach(function(e) {
                    opts += '<option value="' + e.id + '">' + e.name + '</option>';
                });
                modalEmployee.innerHTML = opts;
                modalEmployee.disabled = false;
            });

            // Reset cascade when modal closes
            document.getElementById('importModal').addEventListener('hidden.bs.modal', function() {
                modalBranch.value = '';
                modalDept.innerHTML = '<option value="">-- اختر الفرع أولاً --</option>';
                modalDept.disabled = true;
                modalEmployee.innerHTML = '<option value="">-- اختر القسم أولاً --</option>';
                modalEmployee.disabled = true;
            });
        })();
    </script>

    <script>
        $(document).ready(function() {
            // Import form handling
            $('#importForm').on('submit', function(e) {
                // Hide the form content and footer
                $('#formContent').hide();
                $('#modalFooter').hide();

                // Show the uploading message
                $('#uploadingMessage').show();
            });

            // Reset modal when it's closed
            $('#importModal').on('hidden.bs.modal', function() {
                $('#formContent').show();
                $('#modalFooter').show();
                $('#uploadingMessage').hide();
                $('#importForm')[0].reset();
            });

            // Clear filters
            $('#clearBtn').on('click', function() {
                window.location.href = window.location.pathname;
            });

            // Export button
            $('#exportBtn').on('click', function(e) {
                e.preventDefault();
                var params = new URLSearchParams(window.location.search);
                params.set('export', 'yes');
                window.location.href = window.location.pathname + '?' + params.toString();
            });
        });
    </script>
@endpush
