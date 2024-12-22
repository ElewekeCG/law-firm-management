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
                <h5 class="py-2">Edit Property</h5>
            </div>
            <div class="card-body">
                @if ($prop)
                    <form id="merchant-customer-form" action="{{ url('properties/update/' . $prop->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Landlord</label>
                                    <select name="clientId" class="form-control select2" id="clientSelect">
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ $prop->clientId == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address<sup class="text-danger">*</sup></label>
                                    <input name="address" type="text" class="form-control" placeholder="E.g Criminal"
                                        id="customer_last_name" required="" value="{{ old('address', $prop->address) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rate">Rate<sup class="text-danger">*</sup></label>
                                    <input name="rate" type="number" class="form-control" id="customer_email" required="" value="{{ old('rate', $prop->rate) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="percentage">Professional fee<sup class="text-danger">*</sup></label>
                                    <input name="percentage" type="number" class="form-control" id="customer_email" required="" value="{{ old('percentage', $prop->percentage) }}">
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
