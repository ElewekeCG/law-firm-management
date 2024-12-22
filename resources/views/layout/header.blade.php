<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                @if ($unreadNotifications->count() > 0)
                    <span class="badge badge-danger badge-counter">
                        {{ $unreadNotifications->count() > 3 ? '3+' : $unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifications
                </h6>
                @forelse ($unreadNotifications as $notification)
                    <form action="{{ url('notifications/mark-as-read/' . $notification->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                                <span class="font-weight-bold">
                                    {{ $notification->data['message'] }}
                                </span>
                            </div>
                        </button>
                    </form>
                @empty
                    <a class="dropdown-item text-center" small text-gray-500>
                        No new notifications
                    </a>
                @endforelse
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.view') }}">
                    view All Notifications
                </a>
                <div class="topbar-divider d-none d-sm-block"></div>
            </div>
        </li>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ Auth::user()->name }}
                </span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
