<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col nav-links">
        <nav id="primary-menu">
            <ul class="top-menu menu-eff text-right">

                <li class="nav-item">
                    <a href="{{ route('employee.students.index') }}" class="nav-monthly-ops">
                        <i class="fa fa-graduation-cap"></i>
                        <span class="menu-title">تسجيل الطلاب </span>
                    </a>
                </li>
                @if (auth()->guard('employee')->user()?->userable?->can_view_work_regulations)
                    <li class="nav-item">
                        <a href="{{ route('employee.show_work_regulations') }}" class="nav-monthly-ops">
                            <i class="fa fa-file-text-o"></i>
                            <span class="menu-title">لأئحة العمل </span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('employee.my_info') }}" class="nav-monthly-ops">
                        <i class="fa fa-id-card-o"></i>
                        <span class="menu-title">بياناتي الوظيفية </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.payrolls.index') }}" class="nav-monthly-ops">
                        <i class="fa fa-money"></i>
                        <span class="menu-title">مرتباتي </span>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</div>
