@extends('layoutmodule::layouts.main')

@section('title')
    لوحة تحكم الأختبارات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">

                <!-- Total Exams -->
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

            <!-- You can add more exam-related widgets here -->

        </div>
    </div>
@endsection
