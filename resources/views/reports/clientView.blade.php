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
        {{-- @if (!$report->isEmpty()) --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 text-primary">
                    Report from {{ $report->startDate }} to {{ $report->endDate }}
                </h3>
                <a href="{{ route('reports.download')}}" class="btn btn-primary">
                    Download PDF
                </a>

            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="dataTable_length">
                        <p>Total credit: &#8358;{{ number_format($report->report_data['credits'], 0) }}</p>
                        <p>Total Expenses: &#8358;{{ number_format($report->report_data['expenses'], 0 ) }}</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="dataTable_filter" class="dataTables_filter">
                        <p>Professional Fee: &#8358;{{ number_format($report->report_data['professionalFee'], 0) }}</p>
                        <p>Net Income: &#8358;{{ number_format($report->report_data['netIncome'], 0 ) }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            @if (!empty($report->report_data['transactions']))
                                <div class="col-sm-12">
                                    <h2>Transactions</h2>
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                {{-- <th>Narration</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($report->report_data['transactions'] as $trans)
                                                <tr>
                                                    <td>{{ ucfirst($trans['type']) }}</td>
                                                    <td>&#8358;{{ number_format($trans['amount'], 0) }}</td>
                                                    <td>{{ $trans['paymentDate'] }}</td>
                                                    {{-- <td>{{ $trans['narration'] }}</td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h3>Sorry, report is not yet available</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

    </div>
@endsection


