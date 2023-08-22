<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="{{ route('user.profile') }}"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="" />
        {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
        <a href="javascript:void(0)"> <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6></a>
        <p class="mb-0 font-roboto">{{ Auth::user()->user_role }}</p>
        <p class="mb-0 font-roboto">{{ Session::get('user_business_unit_name')}}</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Settings</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav" href="{{ route('dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>

                    @if(Auth::user()->user_role == "Admin")
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('business-unit') }}" href="javascript:void(0)"><i data-feather="server"></i><span>Business Units</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/business-unit') }};">
                            <li><a href="{{ url('business-unit') }}" class="{{routeActive('business-unit')}}">Business Unit</a></li>
                            <li><a href="{{ url('branch') }}" class="{{routeActive('branch')}}">Branch</a></li>
                            <li><a href="{{ url('project') }}" class="{{routeActive('project')}}">Project</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ routeActive('/user') }}" href="javascript:void(0)"><i data-feather="users"></i><span>Users</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/user') }};">
                            <li><a href="{{ url('user') }}" class="{{routeActive('user')}}">User</a></li>
                            <li><a href="{{ url('role') }}" class="{{routeActive('role')}}">Role</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/item') }}" href="javascript:void(0)"><i data-feather="file-text"></i><span>Items</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/item') }};">
                            <li><a href="{{ url('itemcategory') }}" class="{{routeActive('itemcategory')}}">Item Category</a></li>
                            <li><a href="{{ url('item') }}" class="{{routeActive('item')}}">Item</a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/invoice') }}" href="javascript:void(0)"><i data-feather="file"></i><span>Invoices</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/invoice') }};">
                            <li><a href="{{ route('expense-invoice.index') }}" class="{{routeActive('expense-invoice.index')}}">Expense Invoice</a></li>
                            <li><a href="{{ route('income-invoice.index') }}" class="{{routeActive('income-invoice.index')}}">Income Invoice</a></li>
                            <li><a href="{{ route('return-invoice.index') }}" class="{{routeActive('return-invoice.index')}}">Claim Return Invoice</a></li>
                            @if(Auth::user()->user_role == "Admin")
                            <li><a href="{{ route('invoicetype.index') }}" class="{{routeActive('invoicetype')}}">Invoice Type</a></li>
                            @endif
                        </ul>
                    </li>
                    @if(Auth::user()->user_role == "Admin")
                    <li>
                        <a class="nav-link menu-title link-nav " href="{{ url('report') }}"><i data-feather="pie-chart"></i><span>Report</span></a>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav " href="{{ url('budget') }}"><i data-feather="dollar-sign"></i><span>Budget</span></a>
                    </li>
                    @endif
                    {{-- <li class="sidebar-main-title">
                        <div>
                            <h6>Quick Action</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav" href="{{ route('landing-page') }}" class="{{routeActive('landing-page')}}"><i data-feather="navigation-2"></i><span>Landing page</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{routeActive('create-user')}}" href="{{ route('create-user') }}"><i data-feather="user-plus"></i><span>Create user</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{routeActive('internationalization')}}" href="{{ route('internationalization') }}"><i data-feather="file-plus"></i><span>Create invoice</span></a>
                    </li> --}}
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
