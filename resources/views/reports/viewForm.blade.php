@extends('layout.layout')
@section('content')
    <div class="container py-4">
        @if (Session::has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card card-body">
            <div class="card-body">
                <h5 class="py-2">View Your Property Report</h5>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="{{ url('reports/view') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="startDate">From<sup class="text-danger">*</sup></label>
                                <input name="startDate" type="date" class="form-control" id="startDate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="endDate">To<sup class="text-danger">*</sup></label>
                                <input name="endDate" type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="prop-container">
                            <div class="form-group">
                                <label>Property</label>
                                <select name="propertyId" class="form-control select2" id="clientSelect">
                                    <option value="">Select Property</option>
                                    @foreach ($props as $prop)
                                        <option value="{{ $prop->id }}">
                                            {{ $prop->address }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="add-customer-btn" class="btn btn-primary col-md-3">View Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
