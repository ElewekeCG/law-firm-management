<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <i class="fa-solid fa-scale-balanced"></i>
            {{-- <div class=" sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="{{ url('assets/img/lfms.png') }}" alt="...">
            </div> --}}
        </div>
        <div class="sidebar-brand-text mx-3">LFMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Firm Management
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fa-solid fa-users"></i>
            {{-- <i class="fas fa-fw fa-cog"></i> --}}
            <span>Clients</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Clients</h6>
                <a class="collapse-item" href="{{ url('clients/view') }}">View Clients</a>
                <a class="collapse-item" href="{{ url('clients/add') }}">Add new Client</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fa-solid fa-file"></i>
            {{-- <i class="fas fa-fw fa-wrench"></i> --}}
            <span>Cases</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Cases</h6>
                <a class="collapse-item" href="{{ url('cases/view') }}">View Cases</a>
                <a class="collapse-item" href="{{ url('cases/add') }}">Add new Brief</a>
                <a class="collapse-item" href="utilities-animation.html">Record of Proceedings</a>
                {{-- <a class="collapse-item" href="utilities-other.html">Add Record of Proceedings</a> --}}
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Property Management
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fa-solid fa-building"></i>
            {{-- <i class="fas fa-fw fa-folder"></i> --}}
            <span>Properties</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Login Screens:</h6> --}}
                <a class="collapse-item" href="{{ url('properties/view') }}">View Properties</a>
                <a class="collapse-item" href="{{ url('properties/add') }}">Add a new Property</a>
                <a class="collapse-item" href="login.html">View Tenants</a>
                <a class="collapse-item" href="register.html">Add a new Tenant</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fa-solid fa-coins"></i>
            {{-- <i class="fas fa-fw fa-folder"></i> --}}
            <span>Transactions</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Transactions</h6>
                <a class="collapse-item" href="404.html">Incoming Transactions</a>
                <a class="collapse-item" href="blank.html">Expenses</a>
                {{-- <a class="collapse-item" href="">Expenses</a> --}}
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Reports</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
