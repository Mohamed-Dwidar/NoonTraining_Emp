@extends('layoutmodule::layouts.main')

@section('title')
{{ __('messages.admin') }}
@endsection

@section('content')

<div class="content-wrapper container-fluid">
    {{-- <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                <i class="fa fa-list"></i>
                &nbsp;
                طلبات التسجيل في الدورات
            </h3>
        </div>
    </div> --}}

    @include('layoutmodule::layouts.flash')

    <div class="content-body">
        <div class="row">
             <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-file-text-o teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $totalStudentExams }}</h3>
                                        <h5>إجمالي الأختبارات</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-clock-o teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $totalCompletedExams }}</h3>
                                        <h5>الأختبارات المنتهية</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-users teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $totalStudents }}</h3>
                                        <h5>إجمالي الطلاب</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-question-circle-o teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $totalQuestions }}</h3>
                                        <h5>إجمالي الأسئلة</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>




        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
