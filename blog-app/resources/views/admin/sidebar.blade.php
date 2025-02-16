<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(135deg, #1abc9c, #3498db);">
    <!-- Brand Logo -->
    <a href="#" class="brand-link d-flex align-items-center">
        <img src="{{ asset('/metalcluster.jpg') }}" alt="POST APP" class="brand-image" style="width: 30px; height: auto; margin-right: 10px;">
        <span class="brand-text font-weight-light text-white">POST APP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->
        <div class="form-inline my-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" 
                       style="background-color: white; color: black; border: 1px solid #ccc;">
                <button class="btn btn-sidebar" style="background-color: black; color: white;">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard (Ditampilkan untuk semua pengguna) -->
                <li class="nav-item {{ request()->is('dashboard*') ? 'menu-open' : '' }}">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @auth
                    <!-- Setting Admin -->
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item {{ request()->is('admin*') ? 'menu-open' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="nav-link {{ request()->is('admin*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Setting Admin</p>
                            </a>
                        </li>
                    @endif

                    <!-- Kelola Postingan -->
                    @if(auth()->user()->role !== 'admin')
                        <li class="nav-item {{ request()->is('posts*') ? 'menu-open' : '' }}">
                            <a href="{{ route('posts.index') }}" 
                            class="nav-link {{ request()->is('posts*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Kelola Postingan</p>
                            </a>
                        </li>
                    @endif

                @endauth
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Custom CSS for sidebar menu hover effect -->
<style>
    .nav-sidebar .nav-link {
        color: white !important;
    }
    .nav-sidebar .nav-link:hover, 
    .nav-sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2) !important; 
        color: #ffffff !important;
    }
</style>
