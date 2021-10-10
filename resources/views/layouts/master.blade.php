
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ĐỒ ÁN 2</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini" >
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>

    </ul>

    <!-- SEARCH FORM -->
      <div class="input-group input-group-sm">
        <input class="form-control form-control" @keyup="searchEmloyee" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchEmloyee">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="./img/logo.png" class="brand-image img-circle elevation-3" style="opacity: .9">
      <span class="brand-text font-weight-light">Quản lý nhân sự</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/auth.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
             Xin chào! {{ Auth::user()->name }}
          </a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
              <li class="nav-item">
                <router-link to="home" class="nav-link">
                  <i class="fab fa-phoenix-framework blue"></i>
                  <p>
                    Trang chủ
                    <span class="right badge badge-danger">Home</span>
                  </p>
                </router-link>
              </li> 

          @can('isAdmin')

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-tools indigo"></i>
              <p>
                Quản lý 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="emloyee" class="nav-link">
                  <i class="fas fa-users nav-icon cyan"></i>
                  <p>Nhân viên</p>
                </router-link>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan

          @can('isAdmin')
          <li class="nav-item">
            <router-link to="profile" class="nav-link">
              <i class="fas fa-user-cog blue green"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li> 
          @endcan

          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt red"></i>
              <p>
                {{ __('Đăng xuất') }}
              </p>
              
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li> 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->  
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Đồ án 2 - Thầy Trí
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; DH18TIN02 <a href="https://facebook.com/rhythmformylife">Phan Hữu Phúc</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->
@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth
<!-- REQUIRED SCRIPTS -->

<script src="/js/app.js"></script>
</body>
</html>
