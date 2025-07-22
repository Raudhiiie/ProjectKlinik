<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Pretty's Clinic </title>
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('dist/img/logobulat.png') }}">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css')}}">
        <!-- IonIcons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('dist/css/adminlte.min.css')}}">

        @yield('css')

    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <!-- Sidebar toggle -->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Notifikasi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" title="Notifikasi">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-header">3 Notifikasi</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-plus mr-2"></i> Pasien baru mendaftar
                                <span class="float-right text-muted text-sm">2 mnt</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-calendar-alt mr-2"></i> Jadwal kontrol hari ini
                                <span class="float-right text-muted text-sm">1 jam</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">Lihat Semua</a>
                        </div>
                    </li>
                    <!-- Akun -->
                    <!-- Akun -->
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" title="Akun"
                            style="gap: 8px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/6997/6997662.png" alt="User Avatar"
                                class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                            <span class="d-none d-sm-inline text-dark font-weight-bold">{{ Auth::user()->nama }}</span>
                            <i class="fas fa-caret-down ml-1 text-dark"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                    </li>
                </ul>
            </nav>

            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-light-pink elevation-4" style="background-color: #fdd9e5;">
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Brand Area: Logo + Clinic + Terapis dijadikan satu -->
                    <div class="brand-link d-flex align-items-center justify-content-center"
                        style="gap: 10px; padding: 1.2rem 1rem 0.5rem;">
                        <!-- Logo -->
                        <img src="{{ asset('dist/img/Prettys_clinic_logo.png') }}" alt="Logo" class="img-circle"
                            style="width: 60px; height: 60px; object-fit: contain;">

                        <!-- Nama Klinik -->
                        <span class="brand-text font-weight-bold text-dark" style="font-size: 1.2rem;">
                            Pretty’s Clinic
                        </span>
                    </div>
                    <!-- Nama Pengguna -->
                    <!-- Nama dan Role -->
                    <div class="d-flex align-items-center justify-content-center text-dark mb-3" style="gap: 8px;">
                        <i class="fas fa-user-circle" style="font-size: 2rem;"></i>
                        <span style="font-size: 1rem; font-weight: 600;">
                            {{ ucfirst(Auth::user()->role ?? 'User') }}
                        </span>
                    </div>





                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        {{-- <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

                            {{-- Umum: Dashboard 

                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="nav-icon fas fa-th"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                        </ul> --}}

                        {{-- User Terapis --}}
                        @if (Auth::user()->role === 'terapis')
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.dashboard.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.dashboard.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.pasien.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.pasien.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>Pasien</p>
                                    </a>
                                </li>

                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.terapis.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.terapis.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-solid fa-user-nurse"></i>
                                        <p>
                                            Terapis
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.produk.index', 'gudang') }}"
                                        class="nav-link {{ request()->segment(3) == 'gudang' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-box"></i>
                                        <p>
                                            Produk Gudang
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.produk.index', 'cabin') }}"
                                        class="nav-link {{ request()->segment(3) == 'cabin' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-box-open"></i>
                                        <p>
                                            Produk Cabin
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.produk.index', 'cream') }}"
                                        class="nav-link {{ request()->segment(3) == 'cream' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-archive"></i>
                                        <p>
                                            Produk Cream
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.layanan.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.layanan.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-syringe"></i>
                                        <p>
                                            Layanan
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.transaksi.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.transaksi.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-dollar-sign"></i>
                                        <p>
                                            Transaksi
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('terapis.laporanKeuangan.index') }}"
                                        class="nav-link {{ request()->routeIs('terapis.laporanKeuangan.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-receipt"></i>
                                        <p>
                                            Laporan Keuangan
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        {{-- Jika Dokter --}}
                        @if (Auth::user()->role === 'dokter')
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('dokter.dashboard.index') }}"
                                        class="nav-link {{ request()->routeIs('dokter.dashboard.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-th"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>

                            </ul>    
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                                data-accordion="false">
                                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                                <li class="nav-item">
                                    <a href="{{ route('dokter.rekamMedis.index') }}"
                                        class="nav-link {{ request()->routeIs('dokter.rekamMedis.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-notes-medical"></i>
                                        <p>Rekam Medis</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>


            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    @yield('content')
                    <!-- /.content -->
                </section>
                <!-- /.content-wrapper -->

                <!-- Control Sidebar -->
                <aside class="control-sidebar control-sidebar-dark">
                    <!-- Control sidebar content goes here -->
                </aside>
                <!-- /.control-sidebar -->
            </div>
            <!-- ./wrapper -->

            <!-- REQUIRED SCRIPTS -->

            <!-- jQuery -->
            <script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
            <!-- Bootstrap -->
            <script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
            <!-- AdminLTE -->
            <script src="{{url('dist/js/adminlte.js')}}"></script>

            <!-- OPTIONAL SCRIPTS -->
            <script src="{{url('plugins/chart.js/Chart.min.js')}}"></script>
            <!-- AdminLTE for demo purposes -->
            <script src="{{url('dist/js/demo.js')}}"></script>
            <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
            <script src="{{url('dist/js/pages/dashboard3.js')}}"></script>
            <script>
                $(".custom-file-input").on("change", function () {
                    var fileName = $(this).val().split("\\").pop();
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
            </script>
            @yield('js')

    </body>

</html>