@extends('layoutmodule::layouts.layout_main')

@section('title')
    بيانات الموظف
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-user"></i>
                    &nbsp;
                    بيانات الموظف
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        @php
            $statusLabels = [
                'active' => ['label' => 'نشط', 'bg' => '#28a745', 'color' => '#fff'],
                'suspended' => ['label' => 'موقوف', 'bg' => '#ffba16', 'color' => '#000'],
                'on_leave' => ['label' => 'إجازة', 'bg' => '#17a2b8', 'color' => '#fff'],
                'resigned' => ['label' => 'مستقيل', 'bg' => '#f8d7da', 'color' => '#721c24'],
                'terminated' => ['label' => 'منهي الخدمة', 'bg' => '#dc3545', 'color' => '#fff'],
            ];
            $status = $statusLabels[$employee->status] ?? [
                'label' => $employee->status,
                'bg' => '#6c757d',
                'color' => '#fff',
            ];
        @endphp

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header">
                            <div class="row align-items-end">
                                <div class="col-lg-10">
                                    <h4 class="card-title mb-0">
                                        {{ $employee->name }}
                                        &nbsp;&nbsp;&nbsp;
                                        <span
                                            style="background-color:{{ $status['bg'] }}; color:{{ $status['color'] }}; padding:4px 14px; border-radius:50px; font-size:.85rem; font-weight:600;">
                                            {{ $status['label'] }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="p-2">
                                {{-- Section: Personal --}}

                                <div class="row mb-4">
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">اسم الموظف</div>
                                        <div class="font-weight-bold">{{ $employee->name }}</div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">الوظيفة</div>
                                        <div class="font-weight-bold">{{ $employee->job }}</div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">البريد الإلكتروني</div>
                                        <div class="font-weight-bold">{{ $employee->user->first()?->email ?? '—' }}</div>
                                    </div>
                                </div>

                                {{-- Section: Branch / Department --}}

                                <div class="row mb-4">
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">الفرع</div>
                                        <div class="font-weight-bold">{{ $employee->branch?->name ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">القسم</div>
                                        <div class="font-weight-bold">{{ $employee->department?->name ?? '—' }}</div>
                                    </div>
                                </div>

                                {{-- Section: Salary --}}

                                <div class="row mb-2">
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">الراتب الأساسي</div>
                                        <div class="font-weight-bold">{{ number_format($employee->basic_salary, 2) }} ر٫س
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">الراتب اليومي</div>
                                        <div class="font-weight-bold">{{ number_format($employee->daily_salary, 2) }} ر٫س
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">سعر الساعة</div>
                                        <div class="font-weight-bold">{{ number_format($employee->hourly_salary, 2) }} ر٫س
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">أيام العمل الشهرية</div>
                                        <div class="font-weight-bold">{{ $employee->monthly_working_days }} يوم</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">ساعات العمل اليومية</div>
                                        <div class="font-weight-bold">{{ $employee->daily_working_hours }} ساعة</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">إجمالي ساعات العمل الشهرية</div>
                                        <div class="font-weight-bold">{{ $employee->total_working_hours }} ساعة</div>
                                    </div>
                                    @if ($employee->branch && $employee->branch->type === 'training')
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="text-muted small mb-1">عمولة اضافة الطالب</div>
                                            <div class="font-weight-bold">{{ number_format($employee->stu_commission, 2) }}
                                                ر٫س
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Section: Contract Dates --}}

                                <div class="row mb-2">
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">تاريخ التعيين</div>
                                        <div class="font-weight-bold">{{ $employee->hired_at ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">تاريخ انتهاء العقد</div>
                                        <div class="font-weight-bold">{{ $employee->contract_ends_at ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="text-muted small mb-1">تاريخ إنهاء الخدمة</div>
                                        <div class="font-weight-bold">{{ $employee->terminated_at ?? '—' }}</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route(Auth::getDefaultDriver() . '.employees.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i> رجوع
                            </a>
                            <a href="{{ route(Auth::getDefaultDriver() . '.employees.edit', $employee->id) }}"
                                class="btn btn-warning">
                                <i class="fa fa-edit"></i> تعديل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
