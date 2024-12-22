@extends('layout.layout')

@section('content')
    <div class="bg-white shadow-md rounded-lg"
        style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <!-- Header Section -->
                <div class="card-header py-3 text-center">
                    <h2 class="m-0 font-weight-bold text-primary">
                        Appointment Details
                    </h2>
                </div>.
                <!-- Appointment Details Section -->
                <div class="card-body">
                    @if ($singleAppointment)
                        <!-- Action Buttons -->
                        <div class="d-flex mb-4">
                            <a href="{{ url('appointments/edit/' . $singleAppointment->id) }}"
                                class="btn btn-primary btn-sm me-2">
                                Edit
                            </a>
                            <form action="{{ url('appointments/cancel/' . $singleAppointment->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Cancel
                                </button>
                            </form>
                        </div>

                        <!-- Appointment Info -->
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <strong>Date:</strong>
                                    <span>{{ $singleAppointment->startTime->format('d M Y H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Lawyer:</strong>
                                    <span>{{ $singleAppointment->lawyer->name }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Title:</strong>
                                    <span>{{ $singleAppointment->title }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Type:</strong>
                                    <span>{{ ucfirst($singleAppointment->type) }}</span>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <strong>Client:</strong>
                                    <span>{{ $singleAppointment->client->name }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Case:</strong>
                                    <span>{{ $singleAppointment->case->title ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Location:</strong>
                                    <span>{{ $singleAppointment->location }}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Description:</strong>
                                    <span>{{ $singleAppointment->description ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <strong>Status:</strong>
                                    <span
                                        class="badge font-weight-bold">
                                        {{ ucfirst($singleAppointment->status) }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <strong>Notes:</strong>
                                    <span>{{ $singleAppointment->notes ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Appointment Found -->
                        <div class="text-center text-muted">
                            No appointment details available.
                        </div>
                    @endif
                </div>
            </div>
        </div>



    </div>
@endsection

@section('style')
