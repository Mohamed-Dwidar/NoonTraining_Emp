<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col nav-links">
        <nav id="primary-menu">
            <ul class="top-menu menu-eff text-right">

                {{-- 1. الإعدادات --}}
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
                            <a href="#">
                                <i class="fa fa-exclamation-triangle"></i> أنواع المخالفات
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-file-text-o"></i> اللائحة
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 2. الموظفون - single link --}}
                <li class="nav-item">
                    <a href="{{ route('admin.employees.index') }}" class="@if (Request::segment(2) == 'employee') active @endif">
                        <i class="fa fa-users"></i>
                        <span class="menu-title">الموظفون</span>
                    </a>
                </li>

                {{-- 3. العمليات الشهرية --}}
                <li class="nav-item">
                    <a href="#" class="nav-monthly-ops">
                        <i class="fa fa-calendar-check-o"></i>
                        <span class="menu-title">العمليات الشهرية</span>
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="{{ route('admin.attendances.index') }}" class="@if (Request::segment(2) == 'attendance') active @endif">
                                <i class="fa fa-clock-o"></i> الحضور والإجازات
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-ban"></i> المخالفات
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-minus-circle"></i> الخصومات
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-gift"></i> المكافآت
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-money"></i> الرواتب
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 4. الأداء والمتابعة --}}
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-line-chart"></i>
                        <span class="menu-title">الأداء والمتابعة</span>
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="#">
                                <i class="fa fa-tasks"></i> المهام
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-star-o"></i> تقييم الأداء
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-graduation-cap"></i> الطلاب <small class="text-muted">(للمعهد)</small>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 5. التقارير --}}
                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-bar-chart"></i>
                        <span class="menu-title">التقارير</span>
                    </a>
                    <ul class="dropdown">
                        <li>
                            <a href="#">
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

            </ul>
        </nav>
    </div>
</div>
