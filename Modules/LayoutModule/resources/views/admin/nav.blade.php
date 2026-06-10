<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col nav-links">
        <nav id="primary-menu">
            <ul class="top-menu menu-eff text-right">
                {{--  الموظفون - single link --}}
                <li class="nav-item">
                    <a href="{{ route('admin.employees.index') }}" class="@if (Request::segment(2) == 'employee') active @endif">
                        <i class="fa fa-users"></i>
                        <span class="menu-title">الموظفون</span>
                    </a>
                </li>

                {{--  العمليات الشهرية --}}
                <li class="nav-item">
                    <a href="#" class="nav-monthly-ops">
                        <i class="fa fa-calendar-check-o"></i>
                        <span class="menu-title">العمليات و الإجراءات</span>
                    </a>
                    <ul class="dropdown">
                        {{-- <li>
                            <a href="{{ route('admin.attendances.index') }}" class="@if (Request::segment(2) == 'attendance') active @endif">
                                <i class="fa fa-clock-o"></i> الحضور
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('admin.leaves.index') }}" class="@if (Request::segment(2) == 'leave') active @endif">
                                <i class="fa fa-ban"></i> الإجازات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.bonuses.index') }}" class="@if (Request::segment(2) == 'bonus') active @endif">
                                <i class="fa fa-gift"></i> المكافآت
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.deductions.index') }}" class="@if (Request::segment(2) == 'discount') active @endif">
                                <i class="fa fa-minus-circle"></i> الجزاءات والخصومات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.tasks.index') }}" class="@if (Request::segment(2) == 'task') active @endif">
                                <i class="fa fa-tasks"></i> التكليفات و المهام
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.students.index') }}" class="@if (Request::segment(2) == 'student') active @endif">
                                <i class="fa fa-graduation-cap"></i> تسجيل الطلاب <small class="text-muted">(للمعهد)</small>
                            </a>
                        </li>
                    </ul>
                </li>


                {{--  التقارير --}}
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-bar-chart"></i>
                        <span class="menu-title">التقارير</span>
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="{{ route('admin.payrolls.index') }}">
                                <i class="fa fa-money"></i> تقارير الرواتب
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i> تقارير الموظفين
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-graduation-cap"></i> تقارير الطلاب
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- الإعدادات --}}
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-cog"></i>
                        <span class="menu-title">الإعدادات</span>
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="{{ route('admin.branches.index') }}" class="@if (Request::segment(2) == 'branch') active @endif">
                                <i class="fa fa-code-fork"></i> الفروع
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.departments.index') }}" class="@if (Request::segment(2) == 'department') active @endif">
                                <i class="fa fa-sitemap"></i> الأقسام
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.violations.index') }}" class="@if (Request::segment(2) == 'violation') active @endif">
                                <i class="fa fa-exclamation-triangle"></i> المخالفات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.branches.work-regulations.list') }}" class="@if (Request::segment(2) == 'branches' && Request::segment(3) == 'work-regulations') active @endif">
                                <i class="fa fa-file-text-o"></i> لائحة العمل الداخلية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}" class="@if (Request::segment(2) == 'branches' && Request::segment(3) == 'work-regulations') active @endif">
                                <i class="fa fa-cogs"></i> اعدادات النظام العامة
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</div>
