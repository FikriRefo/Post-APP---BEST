<nav class="main-header navbar navbar-expand navbar-dark" style="background: linear-gradient(135deg, #1abc9c, #3498db);">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Fullscreen Button -->
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        @guest
            <!-- Jika belum login, tampilkan tombol Sign In -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link text-white">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </a>
            </li>
        @else
            <!-- Jika sudah login, tampilkan nama user, profile, dan logout -->
            <li class="nav-item dropdown">
                <a class="nav-link text-white" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    style="background: linear-gradient(135deg, #1abc9c, #3498db); border: none;">
                    <span class="dropdown-header text-white font-weight-bold">
                        {{ Auth::user()->name }}
                    </span>
                    <div class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.3);"></div>

                    <!-- Profile Link -->
                    <a href="{{ route('profile.show') }}" class="dropdown-item text-white custom-dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>

                    <div class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.3);"></div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-white custom-dropdown-item" type="submit">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>

<!-- Custom CSS for hover effect -->
<style>
    .custom-dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;  /* Slight grayish background */
        color: #d1d1d1 !important;  /* Light gray text on hover */
    }
</style>
