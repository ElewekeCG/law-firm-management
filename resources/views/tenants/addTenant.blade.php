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
                <h5 class="py-2">Add a new Tenant</h5>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="{{ url('tenants/addTenant') }}" method="POST">@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName">First Name<sup class="text-danger">*</sup></label>
                                <input name="firstName" type="text" class="form-control"
                                    placeholder="Eg John" id="customer_first_name" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName">Last Name<sup class="text-danger">*</sup></label>
                                <input name="lastName" type="text" class="form-control" placeholder="Eg Doe"
                                    id="customer_last_name" required="" >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email<sup class="text-danger">*</sup></label>
                                <input name="email" type="email" class="form-control" placeholder="johndoe@example.com"
                                    id="customer_email" required="">
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
                                    id="customer_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rentAmt">Rent Amount<sup class="text-danger">*</sup></label>
                                <input name="rentAmt" type="number" class="form-control" placeholder=""
                                    id="customer_email">
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
                            <button id="add-customer-btn" class="btn btn-primary col-md-3">Save</button>
                        </div>
                    </div>
                </form>
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
