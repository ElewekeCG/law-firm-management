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
        <div id="appointmentModal" class="card card-body">
            <div class="card-body">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Schedule New Appointment</h3>
            </div>
            <div class="card-body">
                <form id="merchant-customer-form" action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    {{-- hidden input to capture selected dateTime --}}
                    <input type="hidden" name="date" id="selected-date">
                    <input type="hidden" name="lawyerId" id="selected-lawyer">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lawyer</label>
                                <select id="lawyer_select" class="form-control" required>
                                    <option value="-1">Select Lawyer</option>
                                    @foreach ($lawyers as $lawyer)
                                        <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Availability</label>
                                <select name="availableSlotId" id="time_slots" class="form-control" required>
                                    <option value="" disabled selected>select a slot</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Appointment Type</label>
                                <select name="type" class="form-control" id="appt-type" required>
                                    <option value="">Select Type</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="case_meeting">Case Meeting</option>
                                    <option value="court_appearance">Court Appearance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" id="client-container" style="display:none;">
                            <div class="form-group">
                                <label>Client</label>
                                <select name="clientId" id="client_select" class="form-control">
                                    <option value="-1">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="case-container" style="display:none;">
                            <div class="form-group">
                                <label>Select Case</label>
                                <select name="caseId" id="case_select" class="form-control">
                                    <option value="">Select Case</option>
                                    @foreach ($cases as $case)
                                        <option value="{{ $case->id }}">{{ $case->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description (Optional)</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Notes (Optional)</label>
                                <textarea name="notes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary col-md-3" id="add-customer-btn">
                                Schedule
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('scripts')
    {{-- <!-- FullCalendar Dependencies -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js'></script> --}}
    <script>
        document.getElementById('merchant-customer-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);

                const response = await fetch('/appointments/store', {
                    method: 'POST',
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

                } else {
                    // Handle validation errors
                    if (data.errors) {
                        // Display errors to user
                        alert(data.errors);
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An unexpected error occurred');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // calendar for taking date inputs and getting available slots
            if (typeof FullCalendar === 'undefined') {
                console.error('FullCalendar is not loaded');
                return;
            }
            const lawyerSelect = document.getElementById('lawyer_select');
            const dateInput = document.getElementById('selected-date');
            const timeSlotsSelect = document.getElementById('time_slots');
            const selectedLawyer = document.getElementById('selected-lawyer');

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
                    selectedLawyer.value = lawyerSelect.value;

                    fetchAvailableSlots(lawyerSelect.value, dateInput.value);
                }
            });

            calendar.render();

            const formatDate = (dateInput) => {
                return new Date(dateInput).toISOString().split('T')[0];
            }

            const fetchAvailableSlots = async (lawyerId, dateInput) => {
                const date = formatDate(dateInput);

                // console.log(`fetching slot for lawyer id: ${lawyerId}, ${date}`);
                try {
                    const response = await fetch(`/appointments/available-slots/${lawyerId}/${date}`);

                    if (!response.ok) {
                        const errorData = await response.json();
                        alert(errorData.message || 'Unable to fetch available slots');
                        return;
                    }

                    const slots = await response.json();

                    timeSlotsSelect.innerHTML = '<option value="" disabled selected>Select a slot</option>';

                    slots.forEach(slot => {
                        if (slot.status === 'available') {
                            const newOption = document.createElement('option');
                            newOption.value = slot.id;
                            newOption.textContent =
                                `${new Date(slot.startTime).toLocaleString()} - ${new Date(slot.endTime).toLocaleString()}`;
                            timeSlotsSelect.appendChild(newOption);
                        }
                    });

                } catch (error) {
                    console.error('Fetch error:', error);
                    alert(`An error occured while fetching available slots: ${error.message}`);

                    timeSlotsSelect.innerHTML =
                        '<option value="" disabled selected>Error loading slots</option>';
                }
            };

            // update slot when lawyer changes
            lawyerSelect.addEventListener('change', function() {
                if (dateInput.value) {
                    fetchAvailableSlots(lawyerSelect.value, dateInput.value);
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
