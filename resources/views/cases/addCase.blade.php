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
                <h5 class="py-2">Add a new Case</h5>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="{{ url('cases/addCase') }}" method="POST">@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title<sup class="text-danger">*</sup></label>
                                <input name="title" type="text" class="form-control"
                                    placeholder="E.g John Doe vs Jane Doe" id="customer_first_name" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type<sup class="text-danger">*</sup></label>
                                <input name="type" type="text" class="form-control" placeholder="E.g Criminal"
                                    id="customer_last_name" required="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status<sup class="text-danger">*</sup></label>
                                <input name="status" type="text" class="form-control" placeholder="E.g for hearing"
                                    id="customer_email" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client Name</label>
                                <select name="clientId" class="form-control select2" id="clientSelect">
                                    <option value="-1">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suitNumber">Suit Number<sup class="text-danger">*</sup></label>
                                <input name="suitNumber" type="text" class="form-control" placeholder=""
                                    id="customer_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="startDate">Start Date<sup class="text-danger">*</sup></label>
                                <input name="startDate" type="date" class="form-control" placeholder=""
                                    id="customer_email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nextAdjournedDate">Next Adjourned Date<sup class="text-danger">*</sup></label>
                                <input name="nextAdjournedDate" type="datetime-local" class="form-control" placeholder=""
                                    id="customer_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assignedCourt">Assigned Court<sup class="text-danger">*</sup></label>
                                <input name="assignedCourt" type="text" class="form-control" placeholder=""
                                    id="customer_email">
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
