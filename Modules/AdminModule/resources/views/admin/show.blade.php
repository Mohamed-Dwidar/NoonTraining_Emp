@extends('layoutmodule::layouts.main')

@section('title')
اضافه موظف جديدة
@endsection

@section('content')


<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                {{$admin->name}}
            </h3>
        </div>
    </div>

    @include('layoutmodule::layouts.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            {{-- <div class="col-5">
                                <h2> الدورات</h2>
                            </div> --}}
                            <div class="col-lg-10"></div>
                            <div class="col-lg-2">
                                {{-- <a class="btn btn-success" href="{{route(Auth::getDefaultDriver().'.admins.add_date',$admin->id)}}"
                                role="button">أضافه موعد جديد</a> --}}
                                <a class="btn btn-warning" href="{{route(Auth::getDefaultDriver().'.admins.edit',$admin->id)}}"
                                    role="button">تعديل</a>
                                <a href="{{ route(Auth::getDefaultDriver().'.admins.delete',[$admin->id])}}"
                                    onclick="return confirm('هل انت متأكد انك تريد حذف هذه الموظف ؟')"
                                    class="btn btn-danger" role="button">حذف</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-block">
                            <dl class="row">
                                <dt class="col-sm-12" style="text-align: center;margin-bottom: 20px">
                                    @if($admin->image != null)
                                    <img src="{{url('uploads/admins/' . $admin->image)}}" width="250px" />
                                    @else
                                    <img src="{{url('uploads/admins/default.png')}}" width="100px" height="100px" />
                                    @endif
                                </dt>
                                <dt class="col-sm-3">اسم الموظف</dt>
                                <dd class="col-sm-9">{{$admin->name}}</dd>



                                <dt class="col-sm-3">تفاصيل الموظف</dt>
                                <dd class="col-sm-9">{!!$admin->content!!}</dd>
                            </dl>
                        </div>


                    </div>










                </div>
            </div>
        </div>
    </div>
</div>


@endsection
