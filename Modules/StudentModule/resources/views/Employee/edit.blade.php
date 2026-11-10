@extends('layoutmodule::layouts.layout_main')

@section('title') تعديل طالب @endsection

@section('content')
<div class="content-wrapper container-fluid">

    <div class="content-header mb-2">
        <h3><i class="fa fa-edit"></i> &nbsp; تعديل بيانات الطالب</h3>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="card">
        <div class="card-content">
            <form class="card-form side-form" method="POST"
                  action="{{ route('employee.students.update', $student->id) }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $backUrl) }}">

                {{-- Student info --}}
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        <label for="name">اسم الطالب <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <label for="mobile">رقم الجوال <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="mobile" name="mobile"
                                   class="form-control @error('mobile') is-invalid @enderror"
                                   value="{{ old('mobile', $student->mobile) }}">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <label for="national_id">رقم الهوية <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="national_id" name="national_id"
                                   class="form-control @error('national_id') is-invalid @enderror"
                                   value="{{ old('national_id', $student->national_id) }}">
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="course_name">اسم الكورس <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="course_name" name="course_name"
                                   class="form-control @error('course_name') is-invalid @enderror"
                                   value="{{ old('course_name', $student->course_name) }}">
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="row">
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="total_amount">المبلغ الإجمالي <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="number" id="total_amount" name="total_amount" min="0"
                                   class="form-control @error('total_amount') is-invalid @enderror"
                                   value="{{ old('total_amount', $student->total_amount) }}">
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="paid_amount">المبلغ المدفوع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="number" id="paid_amount" name="paid_amount" min="0"
                                   class="form-control @error('paid_amount') is-invalid @enderror"
                                   value="{{ old('paid_amount', $student->paid_amount) }}">
                            @error('paid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label>المتبقي</label>
                        <div class="form-group">
                            <span id="remainingDisplay" class="form-control text-center bg-light">
                                {{ number_format($student->remainingAmount()) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-6">
                        <label for="payment_method">طريقة الدفع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" id="payment_method" name="payment_method"
                                   class="form-control @error('payment_method') is-invalid @enderror"
                                   value="{{ old('payment_method', $student->payment_method) }}">
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-6">
                        <label for="payment_date">تاريخ الدفع <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="date" id="payment_date" name="payment_date"
                                   class="form-control @error('payment_date') is-invalid @enderror"
                                   value="{{ old('payment_date', $student->payment_date) }}">
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Previous student --}}
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <label for="previous_student_of">طالب سابق لدى</label>
                        <div class="form-group">
                            <input type="text" id="previous_student_of" name="previous_student_of"
                                   class="form-control @error('previous_student_of') is-invalid @enderror"
                                   value="{{ old('previous_student_of', $student->previous_student_of) }}"
                                   placeholder="اختياري – اسم الجهة السابقة">
                            @error('previous_student_of')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-1">
                    <a href="{{ old('redirect_to', $backUrl) }}" class="btn btn-secondary">إلغاء</a>
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
    var totalInput       = document.getElementById('total_amount');
    var paidInput        = document.getElementById('paid_amount');
    var remainingDisplay = document.getElementById('remainingDisplay');

    function updateRemaining() {
        var total = parseInt(totalInput.value) || 0;
        var paid  = parseInt(paidInput.value)  || 0;
        remainingDisplay.textContent = (total - paid).toLocaleString('ar-SA');
    }

    totalInput.addEventListener('input', updateRemaining);
    paidInput.addEventListener('input',  updateRemaining);
    updateRemaining();
})();
</script>
@endpush
