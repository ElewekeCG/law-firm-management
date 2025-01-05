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
            <h3 class="m-0 text-primary">
                Report For {{ $property->address }} from {{ $startDate }} to {{ $endDate }}
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="dataTable_length">
                    <p>Total credit: NGN{{ number_format($credits, 0) }}</p>
                    <p>Total Expenses: NGN{{ number_format($expenses, 0 ) }}</p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div id="dataTable_filter" class="dataTables_filter">
                    <p>Professional Fee: NGN{{ number_format($professionalFee, 0) }}</p>
                    <p>Net Income: NGN{{ number_format($netIncome, 0 ) }}</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    @if (!$transactions->isEmpty())
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tenant Name</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Narration</th>
                                            {{-- <th>Professional Fee</th>
                                            <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $trans)
                                            <tr>
                                                <td>{{ $trans->tenant->full_name ?? '-' }}</td>
                                                <td>NGN{{ number_format($trans->amount, 0) }}</td>
                                                <td>{{ $trans->paymentDate }}</td>
                                                <td>{{ $trans->narration }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


