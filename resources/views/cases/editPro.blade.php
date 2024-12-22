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
                <h5 class="py-2">Edit Record</h5>
            </div>
            <div class="card-body">
                @if ($pro)
                    <form id="merchant-customer-form" action="{{ url('cases/updateRecord/' . $pro->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Case</label>
                                    <select name="caseId" class="form-control select2" id="clientSelect">
                                        @foreach ($cases as $case)
                                            <option value="{{ $case->id }}"
                                                {{ $case->id == $pro->caseId ? 'selected' : '' }}>
                                                {{ $case->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dueDate">Date Due</label>
                                    <input name="dueDate" type="date" class="form-control" id="customer_email"
                                        value="{{ old('dueDate', $pro->dueDate) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Document Status</label>
                                    <select name="docStatus" class="form-control select" id="docSelect">
                                        <option value="pending">Pending</option>
                                        <option value="done">Done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="transcriptionResult">
                                    <label for="description">Court Proceeding</label>
                                    <textarea name="description" id="transcriptionText" class="text-muted form-control" style="text-align: left;">
                                        {{ old('description', $pro->description) }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="docStatus">Required Documents<sup class="text-danger">*</sup></label>
                        <input name="requiredDoc" type="text" class="form-control"
                            placeholder="E.g Written submission, Affidavit" id="customer_last_name"
                            value="{{ old('requiredDoc', $pro->requiredDoc) }}">
                    </div>
                </div>
                {{-- <div class="col-md-6">
                            <div class="form-group">
                                <a id="startRecording" class="btn btn-primary">
                                    Start Recording
                                </a>
                                <a id="stopRecording" class="btn btn-danger" disabled>
                                    Stop Recording
                                </a>
                            </div>
                        </div> --}}
                <div class="col-md-6">
                    <button id="add-customer-btn" class="btn btn-primary col-md-3">Save</button>
                </div>
            </div>
            </form>
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
        // document.addEventListener('DOMContentLoaded', () => {
        //     let mediaRecorder;
        //     let audioChunks = [];
        //     let intervalId;
        //     const startButton = document.getElementById('startRecording');
        //     const stopButton = document.getElementById('stopRecording');
        //     const transcriptionText = document.getElementById('transcriptionText');

        //     startButton.addEventListener('click', async () => {
        //         try {
        //             // Request microphone access
        //             const stream = await navigator.mediaDevices.getUserMedia({
        //                 audio: true
        //             });

        //             // Create media recorder
        //             mediaRecorder = new MediaRecorder(stream);

        //             // Reset audio chunks
        //             audioChunks = [];

        //             // Collect audio data
        //             mediaRecorder.addEventListener('dataavailable', (event) => {
        //                 audioChunks.push(event.data);
        //             });

        //             // Handle stop recording
        //             mediaRecorder.addEventListener('stop', () => {
        //                 clearInterval(intervalId);

        //                 // combine all recorded chunks into a single blob
        //                 const audioBlob = new Blob(audioChunks, {
        //                     type: 'audio/wav'
        //                 });

        //                 // Send final audio to server
        //                 sendAudioToServer(audioBlob);
        //             });

        //             intervalId = setInterval(() => {
        //                 if (audioChunks.length > 0) {
        //                     const partialBlob = new Blob(audioChunks, {
        //                         type: 'audio/wav'
        //                     });
        //                     sendPartialAudioToServer(partialBlob);
        //                     // clear chunks after sending
        //                     audioChunks = [];
        //                 }
        //             }, 1000);

        //             // Start recording
        //             mediaRecorder.start();

        //             // Update button states
        //             startButton.disabled = true;
        //             stopButton.disabled = false;
        //             transcriptionText.textContent = 'Recording...';
        //         } catch (error) {
        //             console.error('Error accessing microphone:', error);
        //             transcriptionText.textContent = 'Microphone access denied.';
        //         }
        //     });

        //     stopButton.addEventListener('click', () => {
        //         // Stop recording
        //         if (mediaRecorder) mediaRecorder.stop();

        //         // Update button states
        //         startButton.disabled = false;
        //         stopButton.disabled = true;
        //         transcriptionText.textContent = 'Processing...';
        //     });

        //     function sendPartialAudioToServer(audioBlob) {
        //         const formData = new FormData();
        //         formData.append('audio', audioBlob, 'partial_recording.wav');

        //         fetch('/speech/transcribe', {
        //                 method: 'POST',
        //                 body: formData
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.transcript) {
        //                     transcriptionText.textContent = data.transcript;
        //                     console.log('Partial transcription:', data.transcript);
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error during partial transcription:', error);
        //             });
        //     }

        //     function sendAudioToServer(audioBlob) {
        //         // Create form data
        //         const formData = new FormData();
        //         formData.append('audio_file', audioBlob, 'recording.wav');

        //         // Send to Laravel backend
        //         $.ajax({
        //             url: "{{ route('speech.transcribe') }}",
        //             type: 'POST',
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(response) {
        //                 $('#transcriptionText').text(response.transcript || 'No transcription');
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('Upload error:', xhr.responseText);
        //                 $('#transcriptionText').text('An error occurred during transcription:' + xhr
        //                     .responseText);
        //             }
        //         });
        //     }
        // });
    </script>
@endsection
