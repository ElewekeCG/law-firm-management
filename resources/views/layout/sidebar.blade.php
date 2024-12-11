<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - head -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <i class="fa-solid fa-scale-balanced"></i>
        </div>
        <div class="sidebar-brand-text mx-3">LFMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Firm Management
    </div>

    <!-- clients-->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
            aria-expanded="true" aria-controls="collapseClients">
            <i class="fa-solid fa-users"></i>
            <span>Clients</span>
        </a>
        <div id="collapseClients" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Clients</h6>
                <a class="collapse-item" href="{{ url('clients/view') }}">View Clients</a>
                <a class="collapse-item" href="{{ url('clients/add') }}">Add new Client</a>
            </div>
        </div>
    </li>

    {{-- appointments --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSch"
            aria-expanded="true" aria-controls="collapseSch">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Appointments</span>
        </a>
        <div id="collapseSch" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Appointments</h6>
                <a class="collapse-item" href="{{ url('appointments/view') }}">View Appointment</a>
                <a class="collapse-item" href="{{ url('appointments/create') }}">Add Appointment</a>
            </div>
        </div>
    </li>

    <!-- Cases -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCases"
            aria-expanded="true" aria-controls="collapseCases">
            <i class="fa-solid fa-file"></i>
            <span>Cases</span>
        </a>
        <div id="collapseCases" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Cases</h6>
                <a class="collapse-item" href="{{ url('cases/view') }}">View Cases</a>
                <a class="collapse-item" href="{{ url('cases/add') }}">Add new Brief</a>
                <a class="collapse-item" href="utilities-animation.html">Record of Proceedings</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Property Management
    </div>

    <!-- properties -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProps"
            aria-expanded="true" aria-controls="collapseProps">
            <i class="fa-solid fa-building"></i>
            <span>Properties</span>
        </a>
        <div id="collapseProps" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('properties/view') }}">View Properties</a>
                <a class="collapse-item" href="{{ url('properties/add') }}">Add a new Property</a>
            </div>
        </div>
    </li>

    {{-- tenants --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTenants"
            aria-expanded="true" aria-controls="collapseTenants">
            <i class="fa-solid fa-users"></i>
            <span>Tenants</span>
        </a>
        <div id="collapseTenants" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Tenants</h6>
                <a class="collapse-item" href="{{ url('tenants/view') }}">View Tenants</a>
                <a class="collapse-item" href="{{ url('tenants/add') }}">Add a new Tenant</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Extras
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTrans"
            aria-expanded="true" aria-controls="collapseTrans">
            <i class="fa-solid fa-coins"></i>
            <span>Transactions</span>
        </a>
        <div id="collapseTrans" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Transactions</h6>
                <a class="collapse-item" href="{{ url('transactions/add') }}">Add Transaction</a>
                <a class="collapse-item" href="{{ url('transactions/view') }}">Manage Transactions</a>
            </div>
        </div>
    </li>

    <!-- Reports -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Reports</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <a href="{{ url('logout') }}">logout</a>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
