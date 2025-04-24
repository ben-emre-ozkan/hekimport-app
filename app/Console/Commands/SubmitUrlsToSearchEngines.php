<?php

namespace App\Console\Commands;

use App\Models\Vitrin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SubmitUrlsToSearchEngines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:submit-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit URLs to search engines';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Submitting URLs to search engines...');

        $urls = $this->getUrlsToSubmit();
        $this->submitToGoogle($urls);
        $this->submitToBing($urls);

        $this->info('URL submission completed!');
    }

    private function getUrlsToSubmit(): array
    {
        $urls = [url('/')];

        Vitrin::all()->each(function (Vitrin $vitrin) use (&$urls) {
            $urls[] = url("/vitrin/{$vitrin->slug}");
        });

        return $urls;
    }

    private function submitToGoogle(array $urls): void
    {
        if (!config('services.google.search_console_api_key')) {
            $this->warn('Google Search Console API key not configured. Skipping Google submission.');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.google.search_console_api_key'),
            ])->post('https://www.googleapis.com/webmasters/v3/sites/' . urlencode(config('app.url')) . '/urlNotifications', [
                'url' => $urls[0], // Submit the first URL as an example
            ]);

            if ($response->successful()) {
                $this->info('Successfully submitted URL to Google Search Console');
            } else {
                $this->error('Failed to submit URL to Google Search Console: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('Error submitting to Google Search Console: ' . $e->getMessage());
        }
    }

    private function submitToBing(array $urls): void
    {
        if (!config('services.bing.webmaster_api_key')) {
            $this->warn('Bing Webmaster API key not configured. Skipping Bing submission.');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.bing.webmaster_api_key'),
            ])->post('https://www.bing.com/webmasters/api/v1/SubmitUrl', [
                'siteUrl' => config('app.url'),
                'url' => $urls[0], // Submit the first URL as an example
            ]);

            if ($response->successful()) {
                $this->info('Successfully submitted URL to Bing Webmaster Tools');
            } else {
                $this->error('Failed to submit URL to Bing Webmaster Tools: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('Error submitting to Bing Webmaster Tools: ' . $e->getMessage());
        }
    }
}
