@extends('layout.layout');
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                Welcome back, {{ $user->name }}
            </h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div> --}}
        <div
            class="welcome-container d-flex flex-wrap align-items-center justify-content-between p-3 shadow rounded bg-light">
            <h1 class="welcome-title h3 mb-0 text-primary">
                Welcome back, {{ $user->name }}
            </h1>
            {{-- <a href="#" class="btn btn-primary btn-sm d-flex align-items-center shadow">
                <i class="fas fa-download fa-sm text-white me-2"></i> Generate Report
            </a> --}}
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-m font-weight-bold text-primary text-uppercase mb-1">
                                    {{ $newAppointments }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <a href="{{ route('dashboard.upcoming') }}">
                                        Upcoming Appointments
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-m font-weight-bold text-success text-uppercase mb-1">
                                    {{ $upcomingCases }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <a href="{{ route('dashboard.upcomingCases') }}">
                                        Upcoming Cases
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-m font-weight-bold text-info text-uppercase mb-1">{{ $pendingDocs }}
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            <a href="{{ route('dashboard.pendingDocs') }}">
                                                Documents Due
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="bg-white shadow-md rounded-lg"
                    style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                            </div>
                            <div class="card-body">
                                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    @if ($notifications->count() > 0)
                                        @foreach ($notifications as $notif)
                                            <div
                                                class="notification-item d-flex align-items-center justify-content-between mb-3 p-3 border rounded shadow-sm">
                                                <!-- Left Section: Icon and Message -->
                                                <div class="d-flex align-items-center">
                                                    <!-- Icon Section -->
                                                    <div class="icon-circle bg-primary text-center text-white d-flex justify-content-center align-items-center"
                                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                                        <i class="{{ $notif->data['icon'] ?? 'fas fa-bell' }}"></i>
                                                    </div>
                                                    <!-- Message Section -->
                                                    <div class="ml-3">
                                                        <div class="small text-gray-500 mb-1">
                                                            {{ $notif->created_at->diffForHumans() }}
                                                        </div>
                                                        <span class="font-weight-bold">
                                                            {{ $notif->data['message'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- Right Section: Mark as Read Button -->
                                                {{-- <div>
                                                    @if (is_null($notif->read_at))
                                                        <form action="{{ route('notifications.mark-as-read', $notif->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-sm btn-primary">Mark as
                                                                Read</button>
                                                        </form>
                                                    @else
                                                        <span class="text-success small">Read</span>
                                                    @endif
                                                </div> --}}
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted">
                                            No notifications available.
                                        </div>
                                    @endif
                                    <div class="row">
                                        {{-- <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info" id="dataTable_info" role="status"
                                                aria-live="polite">
                                                Showing {{ $notifications->firstItem() }} to
                                                {{ $notifications->lastItem() }} of
                                                {{ $notifications->total() }} entries
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                                {{ $notifications->links() }}
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Pie Chart -->

        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Customer Database <span class="float-right">60%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Color System -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-primary text-white shadow">
                            <div class="card-body">
                                Primary
                                <div class="text-white-50 small">#4e73df</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                Success
                                <div class="text-white-50 small">#1cc88a</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-info text-white shadow">
                            <div class="card-body">
                                Info
                                <div class="text-white-50 small">#36b9cc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-warning text-white shadow">
                            <div class="card-body">
                                Warning
                                <div class="text-white-50 small">#f6c23e</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-danger text-white shadow">
                            <div class="card-body">
                                Danger
                                <div class="text-white-50 small">#e74a3b</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-secondary text-white shadow">
                            <div class="card-body">
                                Secondary
                                <div class="text-white-50 small">#858796</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-light text-black shadow">
                            <div class="card-body">
                                Light
                                <div class="text-black-50 small">#f8f9fc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-dark text-white shadow">
                            <div class="card-body">
                                Dark
                                <div class="text-white-50 small">#5a5c69</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 mb-4">

                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="assets/img/undraw_posting_photo.svg" alt="...">
                        </div>
                        <p>Add some quality, svg illustrations to your project courtesy of <a target="_blank"
                                rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                            constantly updated collection of beautiful svg images that you can use
                            completely free and without attribution!</p>
                        <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                            unDraw &rarr;</a>
                    </div>
                </div>

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                    </div>
                    <div class="card-body">
                        <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                            CSS bloat and poor page performance. Custom CSS classes are used to create
                            custom components and custom utility classes.</p>
                        <p class="mb-0">Before working with this theme, you should become familiar with the
                            Bootstrap framework, especially the utility classes.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    {{-- </div> --}}
    <!-- End of Main Content -->
    {{-- </div> --}}
    <!-- End of Content Wrapper -->
@endsection

@push('styles')
    <style>
        .welcome-container {
            background-color: #f8f9fa;
            /* Light gray background */
            border-radius: 8px;
            /* Smooth rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            padding: 20px;
            /* Add padding for spacing */
            margin-bottom: 16px;
            /* Margin for spacing below */
        }

        .welcome-title {
            color: #4e73df;
            /* Primary color */
            font-weight: 600;
        }

        .welcome-container a.btn {
            background-color: #4e73df;
            /* Primary button color */
            color: white;
            padding: 8px 16px;
            /* Button padding */
            font-size: 0.875rem;
            /* Slightly smaller text size */
            border-radius: 4px;
            /* Rounded button edges */
            transition: all 0.3s ease;
        }

        .welcome-container a.btn:hover {
            background-color: #2e59d9;
            /* Darker blue on hover */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            /* More prominent shadow */
        }

        .welcome-container i {
            margin-right: 8px;
            /* Space between icon and text */
        }
    </style>
@endpush
