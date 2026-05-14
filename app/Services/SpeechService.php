<?php

namespace App\Services;

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Core\Exception\GoogleException;
use Illuminate\Log\Logger;

class SpeechService
{
    private SpeechClient $speechClient;

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
        // validate file exists and is readable
        if (!file_exists($audioFilePath) || !is_readable($audioFilePath)) {
            throw new \InvalidArgumentException('Audio file not found or unreadable: {$audioFilePath}');
        }

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
                $alternatives = $result->getAlternatives();
                if (!empty($alternatives)) {
                    $transcription .= $alternatives[0]->getTranscript();
                }
            }
            return $this->processTranscript($transcription);
        } catch (GoogleException $e) {
            logger()->error('Transcription error:'. $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            logger()->error('Unexpected transcription error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function processTranscript($transcript)
    {
        // trim leading and trailing white spaces
        $cleaned = trim($transcript);
        // replace multiple spaces with a single space
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);

        return $cleaned;
    }

    // clean up speech client when done
    public function __destruct()
    {
        if(isset($this->speechClient)) {
            $this->speechClient->close();
        }
    }
}
