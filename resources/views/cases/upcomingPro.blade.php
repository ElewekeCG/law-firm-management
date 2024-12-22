@extends('layout.layout')

@section('content')
    <div class="bg-white shadow-md rounded-lg">
        <div class="card-header py-3">
            <h2 class="text-2xl font-semibold">
                Pending Documents
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    {{ $pendingDocs->appends(request()->input())->links() }}


                    @if (!$pendingDocs->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Case Title</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Case Title</th>
                                            <th>Document</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($pendingDocs as $docs)
                                            <tr>
                                                <td>{{ $docs->dueDate}}</td>
                                                <td>{{ $docs->case->title }}</td>
                                                <td>{{ $docs->requiredDoc }}</td>
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
                                Showing {{ $pendingDocs->firstItem() }} to {{ $pendingDocs->lastItem() }} of
                                {{ $pendingDocs->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $pendingDocs->links() }} <!-- Generates pagination links automatically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
