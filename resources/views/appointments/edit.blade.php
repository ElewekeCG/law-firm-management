@extends('layout.layout')
@section('content')
    <div class="container py-4">
        @if (Session::has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ Session::get('message') }} <button
                    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
            @endif{{-- validation errors --}} @if ($errors->any())
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
                    <h5 class="py-2">Edit Appointment</h5>
                </div>
                <div class="card-body">
                    @if ($appt)
                        <form id="merchant-customer-form" action="{{ url('appointments/update/' . $appt->id) }}"
                            method="POST">@csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selected-date">Choose a new Date</label>
                                        <input type="date" id="selected-date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lawyer</label>
                                        <select name="lawyerId" id="lawyer_select" class="form-control" required>
                                            @foreach ($lawyers as $lawyer)
                                                <option value="{{ $lawyer->id }}"
                                                    {{ $lawyer->id == $appt->lawyerId ? 'selected' : '' }}>
                                                    {{ $lawyer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="client-container" style="display:none;">
                                    <div class="form-group"><label>Client</label><select name="clientId" id="client_select"
                                            class="form-control">
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}"
                                                    {{ $client->id == $appt->client_id ? 'selected' : '' }}>
                                                    {{ $client->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Time</label>
                                        <select name="availableSlotId" id="time_slots" class="form-control" required>
                                            <option value="{{ $appt->id }}">
                                                {{ \Carbon\Carbon::parse($appt->startTime)->format('Y-m-d H:i:s') }} -
                                                {{ \Carbon\Carbon::parse($appt->endTime)->format('Y-m-d H:i:s') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" id="case-container" style="display:none;">
                                    <div class="form-group"><label>Select Case</label><select name="caseId"
                                            id="case_select" class="form-control">
                                            @foreach ($cases as $case)
                                                <option value="{{ $case->id }}"
                                                    {{ $case->id == $appt->case_id ? 'selected' : '' }}>
                                                    {{ $case->title }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><label>Title</label><input type="text" name="title"
                                            class="form-control" value="{{ old('title', $appt->title) }}"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"><label>Status</label><select name="status"
                                            class="form-control" id="status" required>
                                            <option value="scheduled"
                                                {{ $appt->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="confirmed"
                                                {{ $appt->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="completed"
                                                {{ $appt->status == 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="cancelled"
                                                {{ $appt->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                            </option>
                                        </select></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label>Description (Optional)</label>
                                        <textarea name="description" class="form-control">{{ old('description', $appt->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Appointment Type</label><select name="type"
                                            class="form-control" id="appt-type" required>
                                            <option value="consultation"
                                                {{ $appt->type == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                            <option value="case_meeting"
                                                {{ $appt->type == 'case_meeting' ? 'selected' : '' }}>Case Meeting</option>
                                            <option value="court_appearance"
                                                {{ $appt->type == 'court_appearance' ? 'selected' : '' }}>Court Appearance
                                            </option>
                                        </select></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label>Location</label><input type="text" name="location"
                                            class="form-control" value="{{ old('location', $appt->location) }}"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Notes (Optional)</label>
                                        <textarea name="notes" class="form-control">{{ old('notes', $appt->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- <input type="hidden" id="selected-lawyer" name="selected_lawyer_id"> --}}
                            <div class="row">
                                <div class="col-md-6"><button type="submit" class="btn btn-primary col-md-3"
                                        id="add-customer-btn">Update </button></div>
                            </div>
                        </form>
                    @else
                        <div class="row">
                            <div class="col-lg-12 text-danger">No result found </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    </div>@endsection @section('scripts')
    <script>
        $(document).ready(function() {
            $('#clientSelect').select2({
                placeholder: 'Select Client',
                allowClear: true,
                minimumInputLength: 1 // Start searching after 1 character
            });
        });
    </script>
    <script>
        document.getElementById('merchant-customer-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const formObject= {
                    _method: 'PUT',
                    lawyerId: document.getElementById('lawyer_select').value,
                    type: document.getElementById('appt-type').value,
                    title: document.querySelector('input[name="title"]').value,
                    status: document.getElementById('status').value,
                    location: document.querySelector('input[name="location"]').value,
                    availableSlotId: document.getElementById('time_slots').value,
                    description: document.querySelector('textarea[name="description"]').value,
                    notes: document.querySelector('textarea[name="notes"]').value,
                };

                // Add optional fields based on type
                if (['consultation', 'case_meeting'].includes(formObject.type)) {
                    formObject.clientId = document.getElementById('client_select')?.value;
                }
                if (['court_appearance', 'case_meeting'].includes(formObject.type)) {
                    formObject.caseId = document.getElementById('case_select')?.value;
                }

                const id = {{ json_encode($appt->id) }};


                try {
                    // Log form data for debugging
                    console.log('sending data:'
                        formObject);

                    const response = await fetch(`/appointments/update/${id}`, {
                        method: 'PUT',
                        body: JSON.stringify(formObject),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        if(data.redirect){
                            window.location.assign (data.redirect);
                            console.log("Redirecting to:", data.redirect);
                        }
                    } else {
                        // Handle validation errors
                        alert('Error:' + data.errors);

                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An unexpected error occurred');
                }

            } catch (error) {
                console.error('Submission error:', error);
                alert('Could not submit the form');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const lawyerSelect = document.getElementById('lawyer_select');
            // const existingDateInput = document.getElementById('existing-date');
            const dateInput = document.getElementById('selected-date');
            const timeSlotsSelect = document.getElementById('time_slots');
            const selectedSlotId = {{ json_encode($appt->availableSlot?->id) }};
            const selectedLawyer = {{ json_encode($appt->lawyerId) }};

            // document.getElementById('selected-lawyer').value = lawyerSelect.value;

            const formatDate = (dateInput) => {
                return new Date(dateInput).toISOString().split('T')[0];
            }

            const fetchAvailableSlots = async (selectedLawyer, dateInput) => {
                const date = formatDate(dateInput);

                try {
                    const response = await fetch(`/appointments/available-slots/${selectedLawyer}/${date}`);

                    if (!response.ok) {
                        const errorData = await response.json();
                        alert(errorData.message || 'Unable to fetch available slots');
                        return;
                    }

                    const slots = await response.json();

                    timeSlotsSelect.innerHTML = '';
                    const availableSlots = slots.filter(slot => slot.status === 'available');

                    if (availableSlots.length === 0) {
                        const noSlotsOption = document.createElement('option');
                        noSlotsOption.textContent = 'No slots available';
                        noSlotsOption.disabled = true;
                        timeSlotsSelect.appendChild(noSlotsOption);
                    } else {
                        availableSlots.forEach(slot => {
                            const newOption = document.createElement('option');
                            newOption.value = slot.id;
                            newOption.textContent =
                                `${new Date(slot.startTime).toLocaleString()} - ${new Date(slot.endTime).toLocaleString()}`;
                            // if (slot.id === selectedSlotId) {
                            //     newOption.selected = true;
                            // }
                            timeSlotsSelect.appendChild(newOption);
                        });
                    }

                } catch (error) {
                    console.error('Fetch error:', error);
                    alert(`An error occured while fetching available slots: ${error.message}`);
                }

            };

            // update slot when lawyer changes
            lawyerSelect.addEventListener('change', function() {
                if (dateInput.value) {
                    fetchAvailableSlots(lawyerSelect.value, dateInput.value);
                }
            });

            // lawyerSelect.addEventListener('change', function() {
            //     document.getElementById('selected-lawyer').value = lawyerSelect.value;
            // });


            dateInput.addEventListener('change', function() {
                if (lawyerSelect.value) {
                    fetchAvailableSlots(lawyerSelect.value, this.value);
                }
            });

            const caseBlock = document.getElementById('case-container');
            const typeField = document.getElementById('appt-type');
            const clientField = document.getElementById('client-container')
            const toggleCase = () => {
                const selection = typeField.value;
                if (selection === 'court_appearance') {
                    caseBlock.style.display = 'block';
                    clientField.style.display = 'none';
                } else if (selection === 'consultation') {
                    clientField.style.display = 'block';
                    caseBlock.style.display = 'none';
                } else if (selection === 'case_meeting') {
                    caseBlock.style.display = 'block';
                    clientField.style.display = 'block';
                } else {
                    caseBlock.style.display = 'none';
                    clientField.style.display = 'none';
                }
            }

            toggleCase();
            typeField.addEventListener('change', toggleCase);
        });
    </script>
@endsection
