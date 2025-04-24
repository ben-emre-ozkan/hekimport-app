<?php

namespace App\Http\Middleware;

use App\Models\SeoSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->status() === 200 && $request->is('vitrin/*')) {
            $slug = $request->segment(2);
            $seoSettings = SeoSettings::whereHas('vitrin', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->first();

            if ($seoSettings) {
                $content = $response->getContent();
                $content = $this->injectMetaTags($content, $seoSettings);
                $response->setContent($content);
            }
        }

        return $response;
    }

    private function injectMetaTags(string $content, SeoSettings $seoSettings): string
    {
        $metaTags = [];

        // Basic meta tags
        if ($seoSettings->meta_title) {
            $metaTags[] = "<title>{$seoSettings->meta_title}</title>";
            $metaTags[] = "<meta name=\"title\" content=\"{$seoSettings->meta_title}\">";
        }
        if ($seoSettings->meta_description) {
            $metaTags[] = "<meta name=\"description\" content=\"{$seoSettings->meta_description}\">";
        }
        if ($seoSettings->meta_keywords) {
            $metaTags[] = "<meta name=\"keywords\" content=\"{$seoSettings->meta_keywords}\">";
        }

        // Open Graph tags
        if ($seoSettings->og_title) {
            $metaTags[] = "<meta property=\"og:title\" content=\"{$seoSettings->og_title}\">";
        }
        if ($seoSettings->og_description) {
            $metaTags[] = "<meta property=\"og:description\" content=\"{$seoSettings->og_description}\">";
        }
        if ($seoSettings->og_image) {
            $metaTags[] = "<meta property=\"og:image\" content=\"{$seoSettings->og_image}\">";
        }

        // Twitter Card tags
        if ($seoSettings->twitter_title) {
            $metaTags[] = "<meta name=\"twitter:title\" content=\"{$seoSettings->twitter_title}\">";
        }
        if ($seoSettings->twitter_description) {
            $metaTags[] = "<meta name=\"twitter:description\" content=\"{$seoSettings->twitter_description}\">";
        }
        if ($seoSettings->twitter_image) {
            $metaTags[] = "<meta name=\"twitter:image\" content=\"{$seoSettings->twitter_image}\">";
        }

        // Robots meta tag
        $robots = $seoSettings->index_follow ? 'index, follow' : 'noindex, nofollow';
        $metaTags[] = "<meta name=\"robots\" content=\"{$robots}\">";

        // Schema.org markup
        if ($seoSettings->schema_markup) {
            $metaTags[] = "<script type=\"application/ld+json\">" . json_encode($seoSettings->schema_markup) . "</script>";
        }

        // Inject meta tags into the head section
        $metaTagsString = implode("\n    ", $metaTags);
        $content = preg_replace('/<head>/', "<head>\n    " . $metaTagsString, $content, 1);

        return $content;
    }
}
