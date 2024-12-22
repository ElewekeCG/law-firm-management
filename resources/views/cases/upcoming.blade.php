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
                    {{ $upcomingCases->appends(request()->input())->links() }}


                    @if (!$upcomingCases->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Case Title</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Case Title</th>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($upcomingCases as $appt)
                                            <tr>
                                                <td>{{ $appt->startTime->format('d M Y H:i') }}</td>
                                                <td>{{ $appt->case->title }}</td>
                                                <td>{{ $appt->case->status }}</td>
                                                <td>{{ $appt->location }}</td>
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
                                Showing {{ $upcomingCases->firstItem() }} to {{ $upcomingCases->lastItem() }} of
                                {{ $upcomingCases->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $upcomingCases->links() }} <!-- Generates pagination links automatically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
