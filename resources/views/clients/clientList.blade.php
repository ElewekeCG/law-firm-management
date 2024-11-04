@extends('layout.layout')
@section('content')
    {{-- <h1 class="h3 mb-2 text-gray-800">Clients</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
        href="https://datatables.net">official DataTables documentation</a>.
    </p> --}}

    <!-- DataTales Example -->
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
            <h3 class="m-0 font-weight-bold text-primary">Clients</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="dataTable_length">
                                <label>Show
                                    <select id="dataTableLength" name="dataTable_length" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10" {{ request('dataTable_length') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('dataTable_length') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('dataTable_length') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('dataTable_length') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    entries
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="dataTable_filter" class="dataTables_filter">
                                <form action="{{ route('clients.clientList') }}" method="GET" class="mb-4">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="form-control mr-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Keeps query parameters in pagination links -->
                    {{ $clientList->appends(request()->input())->links() }}

                    @if(!$clientList->isEmpty())
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>Property Managed</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>Property Managed</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($clientList as $index => $client)
                                    <tr>
                                        <td>{{ $client->full_name }}</td>
                                        <td>{{ $client->address }}</td>
                                        <td>{{ $client->phoneNumber }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->propertyManaged ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a class="me-3" href="{{ url('clients/edit/' . $client->id) }}">
                                                <img src="{{ url('assets/img/edit.svg') }}" alt="Edit">
                                            </a>
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
                                Showing {{ $clientList->firstItem() }} to {{ $clientList->lastItem() }} of {{ $clientList->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $clientList->links() }} <!-- Generates pagination links automatically -->
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
    document.getElementById('dataTableLength').addEventListener('change', function () {
        this.form.submit();
    });

    document.getElementById('dataTableSearch').addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            this.form.submit(); // Submit on pressing Enter
        }
    });
</script>
@endsection
