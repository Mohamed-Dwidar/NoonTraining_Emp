@extends('layoutmodule::layouts.main')

@section('title')
الموظفين
@endsection


@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                <i class="fa fa-users"></i>
                &nbsp;
            الموظفين
            </h3>
            {{-- <a href="admin">الموظفن /</a> --}}
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
                                <h2> الموظفن</h2>
                            </div> --}}
                            <div class="col-lg-10"></div>
                            <div class="col-lg-2">
                                <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                    href="{{route(Auth::getDefaultDriver().'.admins.add')}}" role="button">أضف موظف جديد</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="head">
                                        <th>الموظف</th>
                                        <th>البريد اللألكتروني</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($admins))
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td class="strong">{{$admin->name}}</td>
                                        <td class="strong">{{$admin->email}}</td>
                                        <td class="action">
                                            <a class="btn btn-warning"
                                                href="{{route(Auth::getDefaultDriver().'.admins.edit',$admin->id)}}"
                                                role="button">تعديل</a>

                                            <a class="btn btn-danger"
                                                href="{{route(Auth::getDefaultDriver().'.admins.delete',$admin->id)}}"
                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الموظف؟')"
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
