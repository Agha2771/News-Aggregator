<?php

namespace News\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class GuardianApiService
{
    /**
     * Fetch articles from The Guardian API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        try {
            // Get the API URL and key from the config/services.php file
            $url = config('services.guardian.url');
            $apiKey = config('services.guardian.key');

            $response = Http::get($url, [
                'api-key' => $apiKey,
                'section' => 'world',
                'order-by' => 'newest',
                'page-size' => 10,
            ]);

            // Check if the response was successful
            if ($response->successful()) {
                $articles = $response->json()['response']['results'] ?? [];

                return array_map(function ($article) {
                    return $this->formatArticle($article);
                }, $articles);
            }

            // Log a warning if the response was not successful
            Log::warning('Failed to fetch articles from The Guardian API', ['response' => $response->body()]);

        } catch (RequestException $e) {
            // Log any errors that occur during the HTTP request
            Log::error('Error fetching articles from The Guardian API', ['error' => $e->getMessage()]);
        }

        // Return an empty array if something went wrong
        return [];
    }

    /**
     * Format the article data.
     *
     * @param array $article
     * @return array
     */
    private function formatArticle(array $article): array
    {
        return [
            'title' => $article['webTitle'] ?? '',
            'content' => '',  // Content not provided by Guardian API
            'url' => $article['webUrl'] ?? '',
            'author' => $article['pillarName'] ?? 'Unknown',
            'category' => $article['sectionName'] ?? 'General',
            'source' => 'The Guardian',
            'publishedAt' => $article['webPublicationDate'] ?? now(),
        ];
    }
}
