<?php

namespace App\Http\Controllers;


use App\Services\SpeechService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SpeechController extends Controller
{
    public function __construct(private SpeechService $speechService)
    {
        //
    }
    public function index()
    {
        return view('speech.add');
    }
    public function transcribe(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:wav,mp3,ogg,webm|max:10240' // 10MB max
        ]);

        $audioFile = $request->file('audio_file');
        if(!$audioFile->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'File upload failed',
                'code' => $audioFile->getError()
            ], 400);
        }

            \Log::info('Audio transcription requested', [
                'user_id' => auth()->id(),
                'original_name' => $audioFile->getClientOriginalName(),
                'mime_type' => $audioFile->getClientMimeType(),
                'size' => $audioFile->getSize()
            ]);

            try {
                $transcript = $this->speechService->transcribeAudio(
                    $audioFile->getRealPath()
                );

                if (empty($transcript)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'No transcription results returned'
                    ], 400);
                }

                return response()->json([
                    'Success' => true,
                    'transcript' => $transcript
                ]);
            } catch (\InvalidArgumentException $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 422);
            } catch (\Exception $e) {
                \Log::error('Transcription failed', [
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Transcription failed, please try again.'
                ], 500);
            }
    }
}

