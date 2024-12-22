@extends('layout.layout')

@section('content')
    <div class="bg-white shadow-md rounded-lg"
        style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notifications</h6>
                </div>
                <div class="card-body">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        @if ($notifications->count() > 0)
                            @foreach ($notifications as $notif)
                                <div class="notification-item d-flex align-items-center justify-content-between mb-3 p-3 border rounded shadow-sm">
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
                                    <div>
                                        @if (is_null($notif->read_at))
                                            <form action="{{ route('notifications.mark-as-read', $notif->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                                            </form>
                                        @else
                                            <span class="text-success small">Read</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                No notifications available.
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                    Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of
                                    {{ $notifications->total() }} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
