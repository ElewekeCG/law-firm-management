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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lawyer</label>
                                <select name="lawyerId" id="lawyer_select" class="form-control" required>
                                    @foreach ($lawyers as $lawyer)
                                        <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="appointment_date" id="appointment_date"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class=>Available Time Slots</label>
                                <select name="time_slot" id="time_slots" class="form-control" required>
                                    <!-- Dynamically populated -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Appointment Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="consultation">Consultation</option>
                                    <option value="case_meeting">Case Meeting</option>
                                    <option value="court_appearance">Court Appearance</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description (Optional)</label>
                                {{-- <input type="text" name="description" class="form-control"> --}}
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 flex justify-end space-x-4">
                            <button type="button" onclick="closeAppointmentModal()"
                                class="btn btn-primary col-md-3" id="add-customer-btn">
                                Cancel
                            </button>
                        </div>
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
    <script>
        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const lawyerSelect = document.getElementById('lawyer_select');
            const dateInput = document.getElementById('appointment_date');
            const timeSlotsSelect = document.getElementById('time_slots');

            // Fetch available slots when lawyer or date changes
            [lawyerSelect, dateInput].forEach(el => {
                el.addEventListener('change', fetchAvailableSlots);
            });

            function fetchAvailableSlots() {
                const lawyerId = lawyerSelect.value;
                const date = dateInput.value;

                if (lawyerId && date) {
                    fetch(`/appointments/available-slots?lawyer_id=${lawyerId}&date=${date}`)
                        .then(response => response.json())
                        .then(slots => {
                            timeSlotsSelect.innerHTML = slots.map(slot =>
                                `<option value="${slot.start_datetime}" ${!slot.available ? 'disabled' : ''}>
                                    ${slot.start} - ${slot.end} ${!slot.available ? '(Booked)' : ''}
                                </option>`
                            ).join('');
                        })
                        .catch(error => {
                            console.error('Error fetching slots:', error);
                            alert('Unable to fetch available slots');
                        });
                }
            }
        });
    </script>
@endsection
