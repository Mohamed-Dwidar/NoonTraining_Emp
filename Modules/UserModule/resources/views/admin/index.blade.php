@extends('layoutmodule::layouts.main')

@section('title')
    المستخدمون
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-users"></i>
                    &nbsp;
                    المستخدمون
                </h3>
                {{-- <a href="course.html">الدورات /</a> --}}
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                {{-- <div class="col-5">
                                <h2> الدورات</h2>
                            </div> --}}
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver().'.user.create') }}" role="button">أضف مستخدم جديدة</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th> الاسم</th>
                                            <th> الفرع</th>
                                            <th> البريد الإلكتروني</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($users)
                                            @foreach($users as $user)
                                                <tr>
                                                    <td class="strong">{{$user->name}}</td>
                                                    <td>{{$user->email}}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver().'.user.edit', $user->id) }}"
                                                            role="button">تعديل</a>

                                                        <a class="btn btn-danger"
                                                            href="{{ route(Auth::getDefaultDriver().'.user.delete', $user->id) }}"
                                                            onclick="return confirm('هل انت متأكد انك تريد حذف هذا المستخدم ؟')"
                                                            role="button">حذف</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
