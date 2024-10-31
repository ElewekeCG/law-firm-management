@extends('layout.layout')
@section('content')
    {{-- <h1 class="h3 mb-2 text-gray-800">Cases</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.
    </p> --}}

    <div class="card shadow mb-4">
        @if (Session::has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-primary">Cases</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
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
                                <label>
                                    Search:
                                    <input type="search" id="dataTableSearch" name="search"
                                        class="form-control form-control-sm" value="{{ request('search') }}"
                                        placeholder="Search..." aria-controls="dataTable">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Keeps query parameters in pagination links -->
                    {{ $caseList->appends(request()->input())->links() }}


                    @if (!$caseList->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Client</th>
                                            <th>Suit Number</th>
                                            <th>StartDate</th>
                                            <th>Adjourned Date</th>
                                            <th>Court</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Client</th>
                                            <th>Suit Number</th>
                                            <th>StartDate</th>
                                            <th>Adjourned Date</th>
                                            <th>Court</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($caseList as $case)
                                            <tr>
                                                <td>{{ $case->title }}</td>
                                                <td>{{ $case->type }}</td>
                                                <td>{{ $case->status }}</td>
                                                <td>{{ $case->client->full_name }}</td>
                                                <td>{{ $case->suitNumber }}</td>
                                                <td>{{ $case->startDate }}</td>
                                                <td>{{ $case->nextAdjournedDate }}</td>
                                                <td>{{ $case->assignedCourt }}</td>
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
                                Showing {{ $caseList->firstItem() }} to {{ $caseList->lastItem() }} of
                                {{ $caseList->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $caseList->links() }} <!-- Generates pagination links automatically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('dataTableLength').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('dataTableSearch').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                this.form.submit(); // Submit on pressing Enter
            }
        });
    </script>
@endsection
