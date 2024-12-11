# speech_recognition_script.py
import speech_recognition as sr
import sys
import json

def transcribe_audio(audio_path):
    # Initialize recognizer
    recognizer = sr.Recognizer()
    try:
    # Load audio file
        with sr.AudioFile(audio_path) as source:
            # Read the entire audio file
            recognizer.adjust_for_ambient_noise(source, duration=1)
            # read the entire audio file
            audio_data = recognizer.record(source)

        try:
            # Use Google Speech Recognition
            text = recognizer.recognize_google(audio_data)
        except sr.UnknownValueError:

            try:
                text = recognizer.recognize_sphinx(audio_data)
            except sr.UnknownValueError:
                text = "unable to transcribe audio"

        print(json.dumps({
            "transcription": text,
            "success": True
        }))
        sys.exit(0)

    except Exception as e:
        print(json.dumps({
            "error": str(e),
            "success": False
        }))
        sys.exit(1)

# Get audio path from command line argument
if len(sys.argv) > 1:
    transcribe_audio(sys.argv[1])
else:
    print(json.dumps({
        "error": "No audio file path provided",
        "success": False
    }))
    sys.exit(1)
