<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">LAPF</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

     <!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard Link -->
        <li class="nav-item">
            <a href="{{ url('/home') }}" class="nav-link {{ Request::is('index.html') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <!-- Performance Link -->
        <li class="nav-item">
            <a href="#" class="nav-link {{ Request::is('periods') || Request::is('targets') || Request::is('objectives')|| Request::is('initiatives') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Performance
                    <i class="fas fa-angle-left right"></i>
     
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                    <a href="{{ url('/periods')}}" class="nav-link {{ Request::is('periods')  ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Periods</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/targets')}}" class="nav-link {{ Request::is('targets')  ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Targets</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/objectives')}}" class="nav-link {{ Request::is('objectives')  ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Objectives</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/initiatives')}}" class="nav-link {{ Request::is('initiatives')  ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Actions</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="pages/layout/top-nav.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>My Performance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Departmental</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages/layout/boxed.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Organisation</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-header">User Management</li>

        <li class="nav-item {{ Request::is('users') || Request::is('departments') || Request::is('sections') || Request::is('jobtitles') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('users') || Request::is('departments') || Request::is('sections') || Request::is('jobtitles') ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                    User Management
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="{{ Request::is('users') || Request::is('departments') || Request::is('sections') || Request::is('jobtitles') ? 'display: block;' : 'display: none;' }}">
                <li class="nav-item">
                    <a href="{{ url('/users') }}" class="nav-link {{ Request::is('users') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Departments/Section
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="{{ Request::is('departments') || Request::is('sections') ? 'display: block;' : 'display: none;' }}">
                        <li class="nav-item">
                            <a href="{{ url('/departments') }}" class="nav-link {{ Request::is('departments') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Departments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/sections') }}" class="nav-link {{ Request::is('sections') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sections</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/jobtitles') }}" class="nav-link {{ Request::is('jobtitles') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Job-Titles</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>