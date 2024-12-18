@extends('layout.layout')

@section('content')
    <div class="bg-white shadow-md rounded-lg">
        <div class="card-header py-3">
            <h2 class="text-2xl font-semibold">
                Appointment Details
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    {{-- <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="dataTable_length">
                                <label>Show
                                    <select id="dataTableLength" name="dataTable_length"
                                        class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10" {{ request('dataTable_length') == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('dataTable_length') == 25 ? 'selected' : '' }}>25
                                        </option>
                                        <option value="50" {{ request('dataTable_length') == 50 ? 'selected' : '' }}>50
                                        </option>
                                        <option value="100" {{ request('dataTable_length') == 100 ? 'selected' : '' }}>
                                            100</option>
                                    </select>
                                    entries
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="dataTable_filter" class="dataTables_filter">
                                <form action="{{ route('appointments.view') }}" method="GET" class=" mb-4">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search..." class="form-control mr-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Keeps query parameters in pagination links -->
                    {{ $appointments->appends(request()->input())->links() }} --}}


                    @if ($singleAppointment)
                    <div class="row">
                        <div class="col-md-12">
                                <a class="me-3" href="{{ url('appointments/edit/' . $singleAppointment->id) }}" title="Edit">
                                    Edit
                                </a>
                                <form action="{{ url('appointments/cancel/' . $singleAppointment->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('PUT') <!-- Change this if your route uses POST -->
                                    <button type="submit" class="btn btn-link me-3 p-0" title="Cancel"
                                        style="border: none; background: none;">
                                        Cancel
                                    </button>
                                </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>Date: {{ $singleAppointment->startTime->format('d M Y H:i') }} </p>
                                <p>Lawyer: {{ $singleAppointment->lawyer->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>Client: {{ $singleAppointment->client->name }}</p>
                                <p>Case: {{ $singleAppointment->case->title ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Title:{{ $singleAppointment->title }}</p>
                                <p>Type:{{ ucfirst($singleAppointment->type) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>Description{{ $singleAppointment->description ?? '-' }} </p>
                                <p>Location: {{ $singleAppointment->location }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Status: {{ ucfirst($singleAppointment->status) }}</p>
                                <p>Notes: {{ $singleAppointment->notes ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
