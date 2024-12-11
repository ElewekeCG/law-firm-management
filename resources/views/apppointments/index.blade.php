@extends('layout.layout')

@section('content')
<div class="bg-white shadow-md rounded-lg">
    <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-semibold">
            {{ $user->isLawyer() ? 'My Appointments' : 'Appointments' }}
        </h2>
        <button onclick="openAppointmentModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
            Schedule New Appointment
        </button>
    </div>

    <div class="p-6">
        <div class="mb-4 flex space-x-4">
            <select id="filterType" class="border rounded px-3 py-2">
                <option value="">All Types</option>
                <option value="consultation">Consultation</option>
                <option value="case_meeting">Case Meeting</option>
                <option value="court_appearance">Court Appearance</option>
            </select>

            <select id="filterStatus" class="border rounded px-3 py-2">
                <option value="">All Statuses</option>
                <option value="scheduled">Scheduled</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <input type="text" id="dateRange" placeholder="Select Date Range" class="border rounded px-3 py-2">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Lawyer</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $appointment->title }}</td>
                        <td class="px-4 py-3">{{ $appointment->lawyer->name }}</td>
                        <td class="px-4 py-3">{{ $appointment->client->name }}</td>
                        <td class="px-4 py-3">
                            {{ $appointment->startTime->format('M d, Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="capitalize">{{ $appointment->type }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="
                                px-2 py-1 rounded text-xs
                                {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                   'bg-blue-100 text-blue-800') }}
                            ">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('appointments.show', $appointment) }}"
                                   class="text-blue-500 hover:text-blue-700">
                                    View
                                </a>
                                @can('update', $appointment)
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="text-yellow-500 hover:text-yellow-700">
                                        Edit
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $appointments->links() }}
    </div>
</div>

@include('appointments.modal')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date range picker
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                filterAppointments();
            }
        }
    });

    // Filter event listeners
    document.getElementById('filterType').addEventListener('change', filterAppointments);
    document.getElementById('filterStatus').addEventListener('change', filterAppointments);
});

function filterAppointments() {
    const type = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    const dateRange = document.getElementById('dateRange').value;

    // Construct query parameters
    const params = new URLSearchParams();
    if (type) params.append('type', type);
    if (status) params.append('status', status);

    if (dateRange) {
        const [start, end] = dateRange.split(' to ');
        params.append('start', start);
        params.append('end', end);
    }

    // Reload page with filters
    window.location.href = `{{ route('appointments.index') }}?${params.toString()}`;
}

function openAppointmentModal() {
    document.getElementById('appointmentModal').classList.remove('hidden');
}
</script>
@endsection
