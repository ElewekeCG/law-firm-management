@extends('layout.layout')

@section('content')
    <div class="bg-white shadow-md rounded-lg">
        <div class="card-header py-3">
            <h2 class="text-2xl font-semibold">
                {{ $user->isLawyer() ? 'My Appointments' : 'Appointments' }}
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="dataTable_length">
                                {{-- <label>Show
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
                                </label> --}}
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            {{-- <div id="dataTable_filter" class="dataTables_filter">
                                <form action="{{ route('appointments.upcoming') }}" method="GET" class=" mb-4">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search..." class="form-control mr-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Keeps query parameters in pagination links -->
                    {{ $appointments->appends(request()->input())->links() }}


                    @if (!$appointments->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Lawyer</th>
                                            <th>Client</th>
                                            <th>Case</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Lawyer</th>
                                            <th>Client</th>
                                            <th>Case</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($appointments as $appt)
                                            <tr>
                                                <td>{{ $appt->startTime->format('d M Y H:i') }}</td>
                                                <td>{{ $appt->lawyer->name }}</td>
                                                <td>{{ $appt->client->name }}</td>
                                                <td>{{ $appt->case->title ?? '-' }}</td>
                                                <td>{{ $appt->title }}</td>
                                                <td>{{ ucfirst($appt->type) }}</td>
                                                {{-- <td>{{ $appt->description ?? '-' }} </td> --}}
                                                <td>{{ $appt->location }}</td>
                                                {{-- <td>{{ ucfirst($appt->status) }}</td> --}}
                                                {{-- <td>{{ $appt->notes ?? '-' }}</td> --}}
                                                <td>
                                                    <a class="me-3" href="{{ url('appointments/show/' . $appt->id) }}" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a class="me-3" href="{{ url('appointments/edit/' . $appt->id) }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ url('appointments/cancel/' . $appt->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-link me-3 p-0" title="Cancel" style="border: none; background: none;">
                                                            <i class="fas fa-times text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of
                                {{ $appointments->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $appointments->links() }} <!-- Generates pagination links automatically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
