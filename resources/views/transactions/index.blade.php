@extends('layout.layout')
@section('content')

    <div class="container-fluid py-4">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="m-0 font-weight-bold text-primary">
                Recent Transactions
            </h3>
            <a href="{{ route('transactions.add') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New
            </a>
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
                                <form action="{{ route('transactions.view') }}" method="GET" class=" mb-4">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="enter name, date or address" class="form-control mr-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Keeps query parameters in pagination links -->
                    {{ $transList->appends(request()->input())->links() }}


                    @if (!$transList->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Sub Type</th>
                                            <th>Paid By</th>
                                            <th>Property</th>
                                            <th>Narration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Sub Type</th>
                                            <th>Paid By</th>
                                            <th>Property</th>
                                            <th>Narration</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @forelse ($transList as $trans)
                                            <tr>
                                                <td>NGN{{ number_format($trans->amount, 0) }}</td>
                                                <td>{{ $trans->paymentDate }}</td>
                                                <td>{{ $trans->type }}</td>
                                                <td>{{ $trans->subType ?? '-' }}</td>
                                                <td>
                                                    @if ($trans->subType === 'rent')
                                                        {{ $trans->tenant->full_name ?? '-' }}
                                                    @elseif ($trans->subType === 'legalFee')
                                                        {{ $trans->client->name ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($trans->type === 'debit' || $trans->subType === 'rent')
                                                        {{ $trans->property->address ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $trans->narration }}</td>
                                                <td>
                                                    <a class="me-3" href="{{ url('transactions/edit/' . $trans->id) }}">
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
                                Showing {{ $transList->firstItem() }} to {{ $transList->lastItem() }} of
                                {{ $transList->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                {{ $transList->links() }} <!-- Generates pagination links automatically -->
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
