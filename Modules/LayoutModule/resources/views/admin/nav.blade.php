<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col nav-links">
        <nav id="primary-menu">
            <ul class="top-menu menu-eff text-right">

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.users.list') }}"
                        class="@if (Request::segment(2) == 'users') active @endif">
                        <i class="fa fa-users"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">المستخدمون</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.exam.index') }}"
                        class="@if (Request::segment(2) == 'exam') active @endif">
                        <i class="fa fa-book"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">الأختبارات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.categories.index') }}"
                        class="@if (Request::segment(2) == 'category') active @endif">
                        <i class="fa fa-list"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">تصنيفات الأسئلة</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.questions.index') }}"
                        class="@if (Request::segment(2) == 'question') active @endif">
                        <i class="fa fa-question-circle fa-fw"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">بنك الأسئلة</span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                        class="@if (Request::segment(2) == 'student') active @endif">
                        <i class="fa fa-graduation-cap"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">الطلاب</span>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}" class="@if (Request::segment(2) == 'students' && in_array(Request::segment(3), ['', 'add', 'edit', 'view'])) active @endif">
                        <i class="fa fa-graduation-cap"></i>
                        <span data-i18n="nav.dash.main" class="menu-title">الطلاب</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</div>
