<?php

namespace App\Http\Controllers;

use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\SpeechClient;
use Illuminate\Http\Request;

class SpeechController extends Controller
{
    public function index()
    {
        return view('speech.add');
    }
    public function transcribe(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:wav,mp3,ogg,webm|max:10240' // 10MB max
        ]);

        try {
            $audioFile = $request->file('audio_file');
            if(!$audioFile->isValid()) {
                return response()->json([
                    'error' => 'File upload failed',
                    'file_errors' => $audioFile->getError()
                ], 400);
            }

            $audioContent = file_get_contents($audioFile->getRealPath());

            \Log::info('Audio File Details', [
                'original_name' => $audioFile->getClientOriginalName(),
                'extension' => $audioFile->getClientOriginalExtension(),
                'mime_type' => $audioFile->getClientMimeType(),
                'size' => $audioFile->getSize()
            ]);

            $speechClient = app(SpeechClient::class);

            $config = new RecognitionConfig();

            $config->setEncoding(RecognitionConfig\AudioEncoding::WEBM_OPUS);
            $config->setSampleRateHertz(48000);

            $config->setLanguageCode('en-NG');
            $config->setEnableAutomaticPunctuation(true);

            // Additional configuration for better recognition
            $config->setModel('default');
            $config->setUseEnhanced(true);

            $audio = new RecognitionAudio();
            $audio->setContent($audioContent);

            $response = $speechClient->recognize($config, $audio);

            foreach ($response->getResults() as $result) {
                $alternative = $result->getAlternatives()[0];
                $transcript = $alternative->getTranscript();

                return response()->json([
                    'success' => true,
                    'transcript' => $transcript
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'No transcription results'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

