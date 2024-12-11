<?php

namespace App\Providers;
use Google\Cloud\Speech\V1\SpeechClient;

use Illuminate\Support\ServiceProvider;

class GoogleSpeechServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SpeechClient::class, function () {
            $credentialsPath = storage_path(('credentials/service-account-credentials.json'));

            if(!file_exists($credentialsPath)) {
                throw new \Exception("Google Speech credentials file not found at:".$credentialsPath);
            }

            return new SpeechClient([
                'credentials' => json_decode(file_get_contents($credentialsPath), true),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
