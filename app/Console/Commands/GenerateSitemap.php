<?php

namespace App\Console\Commands;

use App\Models\Vitrin;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add static pages
        $sitemap->add(Url::create('/')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Add vitrin pages
        Vitrin::all()->each(function (Vitrin $vitrin) use ($sitemap) {
            $sitemap->add(Url::create("/vitrin/{$vitrin->slug}")
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        // Save the sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
