<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <?php //print_r(auth()->user()->privileges_keys());
    ?>
    <div class="navbar-wrapper">
        {{-- <div class="navbar-header"> --}}
        <div class="row">
            <div class="col-xl-3 col-lg-2 col-xs-0 header-title">
                @if (Auth::guard('employee')->check())
                    <div class="col-xl-12 col-lg-12 col-xs-12 header-branch">
                        <span>
                            موقع الموظفين
                            &nbsp; - &nbsp;
                            {{ Auth::guard('employee')->user()->userable->name }}
                        </span>
                    </div>
                @elseif(Auth::guard('admin')->check())
                    <div class="col-xl-12 col-lg-12 col-xs-12 header-branch">
                        <span>
                            موقع الموظفين
                            &nbsp; - &nbsp;
                            مدير النظام
                        </span>
                    </div>
                @endif
            </div>
            <div class="col-xl-6 col-lg-8 col-xs-6 h-logo">
                {{-- <a href="{{url('/')}}" class=" nav-link" target="_blank"> --}}
                <img src="{{ url('admin-assets/images/logo/logo.jpeg') }}" alt="logo" class="logo-default" style="height: 62px">
                {{-- </a> --}}
            </div>
            <div class="col-xl-3 col-lg-2 col-xs-6 h-info">
                <ul class="nav navbar-nav float-xs-right header-user-menu">
                    <li class="dropdown dropdown-user nav-item header-user">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="user-name">{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('employee')->user())?->userable?->name }}</span>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-menu arrow dropdown-menu-left">
                            <a href="{{ (Auth::guard('admin')->check() ? route('admin.changePassword') : route('employee.changePassword')) }}" class="dropdown-item">
                                <i class="icon-key4"></i>
                                تغيير كلمة المرور
                            </a>
                            <a href="{{ Auth::guard('admin')->check() ? route('admin.logout') : route('employee.logout') }}" class="dropdown-item">
                                <i class="icon-power3"></i>
                                خروج
                            </a>
                        </div>
                    </li>
                </ul>
                {{-- Mobile only: opens the main menu (custom.js toggles body.mobile-nav-open) --}}
                <button type="button" class="mobile-nav-toggle" aria-label="القائمة" aria-controls="primary-menu" aria-expanded="false">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
