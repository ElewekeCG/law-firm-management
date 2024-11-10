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
                <h5 class="py-2">Edit Tenant</h5>
            </div>
            <div class="card-body">
                @if ($tenant)
                    <form id="merchant-customer-form" action="{{ url('tenants/update/' . $tenant->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name<sup class="text-danger">*</sup></label>
                                    <input name="firstName" type="text" class="form-control"
                                        placeholder="" id="customer_first_name" required=""
                                        value="{{ old('firstName', $tenant->firstName) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name<sup class="text-danger">*</sup></label>
                                    <input name="lastName" type="text" class="form-control" placeholder=""
                                        id="customer_last_name" required="" value="{{ old('lastName', $tenant->lastName) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email<sup class="text-danger">*</sup></label>
                                    <input name="email" type="email" class="form-control" placeholder=""
                                        id="customer_email" required="" value="{{ old('email', $tenant->email) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Type</label>
                                    <select name="paymentType" class="form-control select" id="clientSelect">
                                        <option value="-1">Select Payment Type</option>
                                        <option value="yearly">Yearly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accomType">Accommodation Type<sup class="text-danger">*</sup></label>
                                    <input name="accomType" type="text" class="form-control" placeholder=""
                                        id="customer_email" value="{{ old('accomType', $tenant->accomType) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rentAmt">Rent Amount<sup class="text-danger">*</sup></label>
                                    <input name="rentAmt" type="number" class="form-control" placeholder=""
                                        id="customer_email" value="{{ old('rentAmt', $tenant->rentAmt) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Property</label>
                                    <select name="propertyId" class="form-control select2" id="clientSelect">
                                        <option value="-1">Select Property</option>
                                        @foreach ($props as $prop)
                                            <option value="{{ $prop->id }}" {{ $prop->propertyId == $prop->id ? 'selected' : '' }}>
                                                {{ $prop->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="add-customer-btn" class="btn btn-primary col-md-3">Save</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="row">
                        <div class="col-lg-12 text-danger">
                            No result found
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#clientSelect').select2({
                placeholder: 'Select Client',
                allowClear: true,
                minimumInputLength: 1 // Start searching after 1 character
            });
        });
    </script>
@endsection
