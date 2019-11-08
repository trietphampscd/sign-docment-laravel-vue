<aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="{{ Request::path() == 'admin/dashboard' ? 'active' : '' }}">
          <a href="/admin/dashboard">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview {{ strpos(Request::path(), 'admin/profile') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Profile</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::path() == 'admin/profile/view' ? 'active' : '' }}">
              <a href="/admin/profile/view"><i class="fa fa-circle-o"></i> View profile</a>
            </li>
            <li class="{{ Request::path() == 'admin/profile/change-password' ? 'active' : '' }}">
              <a href="/admin/profile/change-password"><i class="fa fa-circle-o"></i> Change password</a>
            </li>
          </ul>
        </li>
        @if(Auth::user()->admin == 2)
        <li class="treeview {{ strpos(Request::path(), 'admin/admin-users/') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Admin users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::path() == 'admin/admin-users/new' ? 'active' : '' }}">
              <a href="/admin/admin-users/new"><i class="fa fa-circle-o"></i> Add</a>
            </li>
            <li class="{{ Request::path() == 'admin/admin-users/all' ? 'active' : '' }}">
              <a href="/admin/admin-users/all"><i class="fa fa-circle-o"></i> List All</a>
            </li>
          </ul>
        </li>
        @endif
        <li class="treeview {{ strpos(Request::path(), 'admin/users/') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::path() == 'admin/users/new' ? 'active' : '' }}">
              <a href="/admin/users/new"><i class="fa fa-circle-o"></i> Add</a>
            </li>
            <li class="{{ Request::path() == 'admin/user/all' ? 'active' : '' }}">
              <a href="/admin/users/all"><i class="fa fa-circle-o"></i> List All</a>
            </li>
          </ul>
        </li>

        <li class="{{ Request::path() == 'admin/contracts/all' ? 'active' : '' }}">
          <a href="/admin/contracts/all">
            <i class="fa fa-dashboard"></i> <span>Contracts</span>
          </a>
        </li>

        <li class="treeview {{ strpos(Request::path(), 'admin/package') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Packages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li {{ Request::path() == 'admin/package/new' ? 'active' : '' }}>
              <a href="/admin/companies/new"><i class="fa fa-circle-o"></i> Add</a>
            </li>
            <li {{ Request::path() == 'admin/package' ? 'active' : '' }}>
              <a href="/admin/companies"><i class="fa fa-circle-o"></i> List All</a>
            </li>
          </ul>
        </li>
        <li class="treeview {{ strpos(Request::path(), 'admin/static-pages') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Static pages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li {{ Request::path() == 'admin/static-pages/new' ? 'active' : '' }}>
              <a href="/admin/static-pages/new"><i class="fa fa-circle-o"></i> Add </a>
            </li>
            <li {{ Request::path() == 'admin/static-pages' ? 'active' : '' }}>
              <a href="/admin/static-pages/all"><i class="fa fa-circle-o"></i> List All </a>
            </li>
          </ul>
        </li>
        <li class="treeview {{ strpos(Request::path(), 'admin/settings') > -1  ? 'active menu-open' : '' }}">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li {{ Request::path() == 'admin/settings/notifications' ? 'active' : '' }}>
              <a href="/admin/settings/notifications"><i class="fa fa-circle-o"></i> Notifications </a>
            </li>
            <li {{ Request::path() == 'admin/settings/logut' ? 'active' : '' }}>
              <a href="/admin/logout"><i class="fa fa-circle-o"></i> Logout </a>
            </li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>