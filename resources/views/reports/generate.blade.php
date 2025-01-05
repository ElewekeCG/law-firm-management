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
                <h5 class="py-2">Generate Report</h5>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="" method="GET">@csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Report Type</label>
                                <select name="type" class="form-control select" id="type">
                                    <option value="">Select Report Type</option>
                                    <option value="property">Property</option>
                                    <option value="firm">Firm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="startDate">From<sup class="text-danger">*</sup></label>
                                <input name="startDate" type="date" class="form-control" id="startDate">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="endDate">To<sup class="text-danger">*</sup></label>
                                <input name="endDate" type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                        <div class="col-md-6" id="prop-container" style="display: none;">
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
                            <button id="add-customer-btn" class="btn btn-primary col-md-3">Generate</button>
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
                placeholder: 'Select Property',
                allowClear: true,
                minimumInputLength: 1 // Start searching after 1 character
            });

            const reportType = document.getElementById('type');
            const toggleProp = () => {
                const propSelect = document.getElementById('prop-container');
                {
                    if (reportType.value === 'property') {
                        propSelect.style.display = 'block';
                    } else {
                        propSelect.style.display = 'none';
                    }
                }
            }
            toggleProp();
            reportType.addEventListener('change', toggleProp);

            const formData = document.getElementById('merchant-customer-form');

            const toggleField = () => {
                if (reportType.value === 'property') {
                    formData.action = "{{ url('reports/p-generate') }}";
                    console.log(reportType.value);
                    console.log(formData.action);
                } else if(reportType.value === 'firm') {
                    console.log(reportType.value);
                    console.log(formData.action);
                    formData.action = "{{ url('reports/f-generate') }}";
                } else {
                    formData.action = "";
                }
            }
            toggleField();

            formData.addEventListener('submit', toggleField);
        });
    </script>
    {{-- <script>

        // document.getElementById('merchant-customer-form').addEventListener('submit', async function(e) {
        //     e.preventDefault();

        //     try {
        //         const formData = new FormData(this);
        //         const type = 'property';
        //         const start = document.getElementById('startDate').value;
        //         const end = document.getElementById('endDate').value;
        //         const propertyId = document.getElementById('clientSelect').value;
        //         console.log(propertyId);

        //         const response = await fetch(`/reports/p-generate/${propertyId}/${type}/${start}/${end}`, {
        //             method: 'GET',
        //             headers: {
        //                 'X-Requested-With': 'XMLHttpRequest',
        //                 'Accept': 'application/json'
        //             }
        //         });

        //         const data = await response.json();

        //         if (data.success) {
        //             // Show success message
        //             alert(data.message);

        //         } else {
        //             // Handle validation errors
        //             if (data.error) {
        //                 console.log(data.error);
        //                 // Display errors to user
        //                 alert(data.error);
        //             } else {
        //                 alert(data.message || 'An error occurred');
        //             }
        //         }
        //     } catch (error) {
        //         console.error(error);
        //         alert('An unexpected error occurred');
        //     }
        // });
    </script> --}}
@endsection
