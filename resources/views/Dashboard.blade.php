@extends('layout.layout');
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div
            class="welcome-container d-flex flex-wrap align-items-center justify-content-between p-3 shadow rounded bg-light">
            <h1 class="welcome-title h3 mb-0 text-primary">
                Welcome back, {{ $user->name }}
            </h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            @if (auth()->user()->role !== 'client')
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
            @else
            <div class="col-xl-6 col-md-6 mb-4">
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

            <div class="col-xl-6 col-md-6 mb-4">
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
            @endif

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
                            {{-- @if (auth()->user()->role !== 'clerk') --}}
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
                                                                @if(is_array($notif->data) && isset($notif->data['message']))
                                                                    {{ $notif->data['message'] }}
                                                                @else
                                                                    Notification received
                                                                @endif
                                                                {{-- {{ $notif->data['message'] }} --}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted">
                                                No notifications available.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            {{-- @endif --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
