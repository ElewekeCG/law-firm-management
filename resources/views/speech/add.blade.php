@extends('layout.layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Speech to Text Transcription
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button id="startRecording" class="btn btn-primary">
                                Start Recording
                            </button>
                            <button id="stopRecording" class="btn btn-danger" disabled>
                                Stop Recording
                            </button>
                        </div>

                        <div id="transcriptionResult" class="mt-3">
                            <h4>Transcription:</h4>
                            <p id="transcriptionText" class="text-muted">
                                Transcription will appear here...
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let mediaRecorder;
            let audioChunks = [];
            let intervalId;
            const startButton = document.getElementById('startRecording');
            const stopButton = document.getElementById('stopRecording');
            const transcriptionText = document.getElementById('transcriptionText');

            startButton.addEventListener('click', async () => {
                try {
                    // Request microphone access
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });

                    // Create media recorder
                    mediaRecorder = new MediaRecorder(stream);

                    // Reset audio chunks
                    audioChunks = [];

                    // Collect audio data
                    mediaRecorder.addEventListener('dataavailable', (event) => {
                        audioChunks.push(event.data);
                    });

                    // Handle stop recording
                    mediaRecorder.addEventListener('stop', () => {
                        clearInterval(intervalId);

                        // combine all recorded chunks into a single blob
                        const audioBlob = new Blob(audioChunks, {
                            type: 'audio/wav'
                        });

                        // Send final audio to server
                        sendAudioToServer(audioBlob);
                    });

                    intervalId = setInterval(() => {
                        if (audioChunks.length > 0) {
                            const partialBlob = new Blob(audioChunks, {
                                type: 'audio/wav'
                            });
                            sendPartialAudioToServer(partialBlob);
                            // clear chunks after sending
                            audioChunks = [];
                        }
                    }, 1000);

                    // Start recording
                    mediaRecorder.start();

                    // Update button states
                    startButton.disabled = true;
                    stopButton.disabled = false;
                    transcriptionText.textContent = 'Recording...';
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                    transcriptionText.textContent = 'Microphone access denied.';
                }
            });

            stopButton.addEventListener('click', () => {
                // Stop recording
                if (mediaRecorder) mediaRecorder.stop();

                // Update button states
                startButton.disabled = false;
                stopButton.disabled = true;
                transcriptionText.textContent = 'Processing...';
            });

            function sendPartialAudioToServer(audioBlob) {
                const formData = new FormData();
                formData.append('audio', audioBlob, 'partial_recording.wav');

                fetch('/speech/transcribe', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.transcript) {
                            transcriptionText.textContent = data.transcript;
                            console.log('Partial transcription:', data.transcript);
                        }
                    })
                    .catch(error => {
                        console.error('Error during partial transcription:', error);
                    });
            }

            function sendAudioToServer(audioBlob) {
                // Create form data
                const formData = new FormData();
                formData.append('audio_file', audioBlob, 'recording.wav');

                // Send to Laravel backend
                $.ajax({
                    url: "{{ route('speech.transcribe') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#transcriptionText').text(response.transcript || 'No transcription');
                    },
                    error: function(xhr, status, error) {
                        console.error('Upload error:', xhr.responseText);
                        $('#transcriptionText').text('An error occurred during transcription:' + xhr
                            .responseText);
                    }
                });
            }
        });
    </script>
@endsection
