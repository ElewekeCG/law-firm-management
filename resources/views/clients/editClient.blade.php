@extends('layout.layout')
@section('content')
    <div class="container py-4">
        @if (Session::has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                <h5 class="py-2">Update Client</h5>
            </div>
            <div class="card-body">
                @if ($client)
                    <form id="merchant-customer-form" action="{{ url('clients/update/' .$client->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name<sup class="text-danger">*</sup></label>
                                    <input name="firstName" type="text" class="form-control" placeholder="E.g John"
                                        id="customer_first_name" value="{{ old('firstName', $client->firstName) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name<sup class="text-danger">*</sup></label>
                                    <input name="lastName" type="text" class="form-control" placeholder="E.g Doe" id="customer_last_name"
                                        required="" value="{{ old('lastName', $client->lastName) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address<sup class="text-danger">*</sup></label>
                                    <input name="address" type="text" class="form-control" placeholder="" id="customer_email"
                                        required="" value="{{ old('address', $client->address) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number<sup class="text-danger">*</sup></label>
                                    <input name="phoneNumber" type="text" class="form-control" placeholder="" id="customer_phone"
                                        value="{{ old('phoneNumber', $client->phoneNumber) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address<sup class="text-danger">*</sup></label>
                                    <input name="email" type="email" class="form-control" placeholder="example@domain.com"
                                        id="customer_email" value="{{ old('email', $client->email) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="propertyManaged">Property Managed?</label>
                                    <select name="propertyManaged" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0" selected>No</option>
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
