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
                <h5 class="py-2">Edit Appointment</h5>
            </div>
            <div class="card-body">
                @if ($appt)
                    <form id="merchant-customer-form" action="{{ url('appointments/update/' . $appt->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- hidden input to capture selected dateTime --}}
                        <input type="hidden" id="selected-date"
                            value="{{ $appt->availableSlot->startTime
                                ? \Carbon\Carbon::parse($appt->availableSlot->startTime)->format('Y-m-d')
                                : '' }}">
                                {{-- <input type="hidden" name="lawyerId" id="selected-lawyer"> --}}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Lawyer</label>
                                    <select id="lawyer_select lawyerId" class="form-control" name="lawyerId" >
                                        @foreach ($lawyers as $lawyer)
                                            <option value="{{ $lawyer->id }}"
                                                {{ $lawyer->id == $appt->lawyerId ? 'selected' : '' }}>
                                                {{ $lawyer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $appt->title) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Availability</label>
                                    <select name="availableSlotId" id="time_slots" class="form-control" required>
                                        <option value="{{ $appt->availableSlot->id }}"
                                            {{ $appt->availableSlot->id == old('availableSlotId', $appt->availableSlot->id) ? 'selected' : '' }}>
                                            {{ $appt->availableSlot->startTime ?? '' }} -
                                            {{ $appt->availableSlot->endTime ?? '' }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="calendar"></div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Appointment Type</label>
                                    <select name="type" class="form-control" id="appt-type" required>
                                        <option value="consultation" {{ $appt->type == 'consultation' ? 'selected' : '' }}>
                                            Consultation</option>
                                        <option value="case_meeting" {{ $appt->type == 'case_meeting' ? 'selected' : '' }}>
                                            Case Meeting</option>
                                        <option value="court_appearance"
                                            {{ $appt->type == 'court_appearance' ? 'selected' : '' }}>Court Appearance
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" class="form-control"
                                        value="{{ old('location', $appt->location) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="client-container" style="display:none;">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select name="clientId" id="client_select" class="form-control">
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ $client->id == $appt->client_id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="case-container" style="display:none;">
                                <div class="form-group">
                                    <label>Select Case</label>
                                    <select name="caseId" id="case_select" class="form-control">
                                        @foreach ($cases as $case)
                                            <option value="{{ $case->id }}"
                                                {{ $case->id == $appt->case_id ? 'selected' : '' }}>
                                                {{ $case->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description (Optional)</label>
                                    <textarea name="description" class="form-control">{{ old('description', $appt->description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Notes (Optional)</label>
                                    <textarea name="notes" class="form-control">{{ old('notes', $appt->notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary col-md-3" id="add-customer-btn">
                                    Update
                                </button>
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
    <script>
        document.getElementById('merchant-customer-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const id = {{ json_encode($appt->id) }};

                const updateAppt = async (formData) => {
                    try {
                        const response = await fetch(`/appointments/update/${id}`, {
                            method: 'PUT',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Show success message
                            alert(data.message);
                            console.log(data);

                        } else {
                            // Handle validation errors
                            alert('Error:' + data.errors);

                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An unexpected error occurred');
                    }
                };
                await updateAppt(formData);
            } catch (error) {
                console.error('Submission error:', error);
                alert('Could not submit the form');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const lawyerSelect = document.getElementById('lawyer_select');
            const existingDateInput = document.getElementById('existing-date');
            const dateInput = document.getElementById('selected-date');
            const timeSlotsSelect = document.getElementById('time_slots');
            const selectedSlotId = {{ json_encode($appt->availableSlot?->id) }};
            const selectedLawyer = {{ json_encode($appt->lawyer_id) }};

            let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable: true,
                selectMirror: true,
                allDaySlot: false,
                slotMinTime: '08:00:00',
                slotMaxTime: '18:00:00',
                select: function(info) {
                    // set hidden inputs
                    dateInput.value = info.startStr;
                    // selectedLawyer.value = lawyerSelect.value;

                    fetchAvailableSlots(lawyerSelect.value, dateInput.value);
                }
            });

            calendar.render();

            const formatDate = (dateInput) => {
                return new Date(dateInput).toISOString().split('T')[0];
            }

            const fetchAvailableSlots = async (lawyerId, dateInput) => {
                const date = formatDate(dateInput);

                try {
                    const response = await fetch(`/appointments/available-slots/${lawyerId}/${date}`);
                    const contentType = response.headers.get('content-type');

                    if (!response.ok) {
                        const errorData = await response.text();
                        alert(errorData.message || 'Unable to fetch available slots');
                        return;
                    }

                    const slots = await response.json();

                    timeSlotsSelect.innerHTML = '';
                    slots.forEach(slot => {
                        const newOption = document.createElement('option');
                        newOption.value = slot.id;
                        const startTime = new Date(slot.startTime);
                        const endTime = new Date(slot.endTime);
                        newOption.textContent =
                            `${startTime.toLocaleString()} - ${endTime.toLocaleString()}`;
                        if (slot.id === selectedSlotId) {
                            newOption.selected = true;
                        }
                        timeSlotsSelect.appendChild(newOption);
                    });

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

            // if (lawyerSelect.value && existingDateInput.value) {
            //     fetchAvailableSlots(lawyerSelect.value, existingDateInput.value);
            // }

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
