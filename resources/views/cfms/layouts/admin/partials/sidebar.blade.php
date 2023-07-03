<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="{{ route('edit-user') }}"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="" />
        {{-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div> --}}
        <a href="javascript:void(0)"> <h6 class="mt-3 f-14 f-w-600">Nyan Lynn Htun</h6></a>
        <p class="mb-0 font-roboto">Admin / Manager</p>
        <p class="mb-0 font-roboto">Business Unit Name</p>
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
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/project') }}" href="javascript:void(0)"><i data-feather="server"></i><span>Business Unit </span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/project') }};">
                            <li><a href="{{ route('projects') }}" class="{{routeActive('projects')}}">Project List</a></li>
                            <li><a href="{{ route('projectcreate') }}" class="{{routeActive('projectcreate')}}">Create new project</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/project') }}" href="javascript:void(0)"><i data-feather="box"></i><span>Project </span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/project') }};">
                            <li><a href="{{ route('projects') }}" class="{{routeActive('projects')}}">Project List</a></li>
                            <li><a href="{{ route('projectcreate') }}" class="{{routeActive('projectcreate')}}">Create new project</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/user') }}" href="javascript:void(0)"><i data-feather="users"></i><span>Users</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/user') }};">
                            <li><a href="{{ route('list') }}" class="{{routeActive('list')}}">User List</a></li>
                            <li><a href="{{ route('create-user') }}" class="{{routeActive('create-user')}}">Create new User</a></li>
                            <li><a href="{{ route('edit-user') }}" class="{{routeActive('edit')}}">Edit User (Temp)</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/user') }}" href="javascript:void(0)"><i data-feather="file"></i><span>Invoices</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/user') }};">
                            <li><a href="{{ route('list') }}" class="{{routeActive('list')}}">User List</a></li>
                            <li><a href="{{ route('create-user') }}" class="{{routeActive('create-user')}}">Create new User</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/user') }}" href="javascript:void(0)"><i data-feather="pie-chart"></i><span>Report</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/user') }};">
                            <li><a href="{{ route('list') }}" class="{{routeActive('')}}">User List</a></li>
                            <li><a href="{{ route('create-user') }}" class="{{routeActive('')}}">Create new User</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav "><i data-feather="dollar-sign"></i><span>Budget</span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Quick Action</h6>
                        </div>
                    </li>
                    <li>
                        {{-- <a class="nav-link menu-title link-nav" href="{{ route('landing-page') }}" class="{{routeActive('landing-page')}}"><i data-feather="navigation-2"></i><span>Landing page</span></a> --}}
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{routeActive('create-user')}}" href="{{ route('create-user') }}"><i data-feather="user-plus"></i><span>Create user</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{routeActive('internationalization')}}" href="{{ route('internationalization') }}"><i data-feather="file-plus"></i><span>Create invoice</span></a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
