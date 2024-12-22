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
                <h5 class="py-2">New Transaction</h5>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="{{ url('transactions/create') }}" method="POST">@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount<sup class="text-danger">*</sup></label>
                                <input name="amount" type="number" class="form-control" id="customer_first_name"
                                    required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paymentDate">Payment Date<sup class="text-danger">*</sup></label>
                                <input name="paymentDate" type="date" class="form-control" placeholder=""
                                    id="customer_last_name" required="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select name="type" class="form-control select" id="type">
                                    <option value="-1">Select Type</option>
                                    <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                    <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="subtype-field" style="display:none;">
                            <div class="form-group">
                                <label>Sub Type</label>
                                <select name="subType" class="form-control select" id="subType">
                                    <option value="-1">Select SubType</option>
                                    <option value="legalFee">Legal Fee</option>
                                    <option value="rent">Rent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="display: none;" id="tenantId-field">
                            <div class="form-group">
                                <label>Tenant</label>
                                <select name="tenantId" id="tenantId" class="form-control select2">
                                    <option value="-1">Select Tenant</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none;" id="clientId-field">
                            <div class="form-group">
                                <label>Client</label>
                                <select name="clientId" class="form-control select2" id="clientId">
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="display: none;" id="propertyId-field">
                            <div class="form-group">
                                <label>Property</label>
                                <select name="propertyId" class="form-control select2" id="propertyId">
                                    <option value="-1">Select Property</option>
                                    @foreach ($properties as $prop)
                                        <option value="{{ $prop->id }}">{{ $prop->address }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Narration<sup class="text-danger">*</sup></label>
                                <input type="text" name="narration" id="customer_email" class="form-control">
                                {{-- <textarea name="narration" id="customer_email" cols="10" rows="5"></textarea> --}}
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
        document.addEventListener('DOMContentLoaded', function() {
            const typeField = document.getElementById('type');
            const subtypeField = document.getElementById('subType');
            const subtypeContainer = document.getElementById('subtype-field');
            const propertyIdContainer = document.getElementById('propertyId-field');
            const tenantIdContainer = document.getElementById('tenantId-field');
            const clientIdContainer = document.getElementById('clientId-field');

            function toggleFields() {
                const typeValue = typeField.value;
                const subtypeValue = subtypeField.value;

                if (typeValue === 'credit') {
                    // Show subtype if credit is selected
                    subtypeContainer.style.display = 'block';
                } else {
                    subtypeContainer.style.display = 'none';
                    subtypeField.value = '';
                }

                if (typeValue === 'debit') {
                    propertyIdContainer.style.display = 'block';
                    tenantIdContainer.style.display = 'none';
                    clientIdContainer.style.display = 'none';
                } else if (typeValue === 'credit' && subtypeValue === 'rent') {
                    clientIdContainer.style.display = 'none';
                    propertyIdContainer.style.display = 'block';
                    tenantIdContainer.style.display = 'block';
                } else if (typeValue === 'credit' && subtypeValue === 'legalFee') {
                    clientIdContainer.style.display = 'block';
                    propertyIdContainer.style.display = 'none';
                    tenantIdContainer.style.display = 'none';
                } else {
                    propertyIdContainer.style.display = 'none';
                    tenantIdContainer.style.display = 'none';
                    clientIdContainer.style.display = 'none';
                }
            }

            // Initial call
            toggleFields();

            // Event Listeners
            typeField.addEventListener('change', toggleFields);
            subtypeField.addEventListener('change', toggleFields);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#clientId').select2({
                placeholder: 'Select Client',
                allowClear: true,
                minimumInputLength: 1 // Start searching after 1 character
            });
        });
    </script>
@endsection
