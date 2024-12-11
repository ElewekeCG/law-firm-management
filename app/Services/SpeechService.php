<?php

namespace App\Services;

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Core\Exception\GoogleException;

class SpeechService
{
    private $speechClient;

    public function __construct()
    {
        try {
            $this->speechClient = new SpeechClient([
                'credentials' => config('services.google.speech_credentials')
            ]);
        } catch (GoogleException $e) {
            logger()->error('Speech to text client initialization error' . $e->getMessage());
            throw $e;
        }
    }

    public function transcribeAudio($audioFilePath, $languageCode = 'en-NG') {
        try {
            $audioContent = file_get_contents($audioFilePath);

            $config = new RecognitionConfig([
                'encoding' => RecognitionConfig\AudioEncoding::LINEAR16,
                'sample_rate_hertz' => 16000,
                'language_code' => $languageCode,
            ]);

            $audio = new RecognitionAudio([
                'content' => $audioContent
            ]);

            $response = $this->speechClient->recognize($config, $audio);

            // extract transcription results
            $transcription = '';
            foreach ($response->getResults() as $result) {
                $transcription.=$result->getAlternatives()[0]->getTranscript();
            }
            return $transcription;
        } catch (GoogleException $e) {
            logger()->error('Transcription error:'. $e->getMessage());
            throw $e;
        }
    }

    public function processTranscript($transcript)
    {
        $cleanedTranscript = trim($transcript);
        $cleanedTranscript = preg_replace('/\s+/', '', $cleanedTranscript);

        return $cleanedTranscript;
    }

    // clean up speech client when done
    public function __destruct()
    {
        if($this->speechClient) {
            $this->speechClient->close();
        }
    }
}
