<?php

namespace News\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class NytApiService
{
    /**
     * Fetch articles from the New York Times API.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        try {
            // Get the API URL and key from the config/services.php file
            $url = config('services.nytapi.url');
            $apiKey = config('services.nytapi.key');

            $response = Http::get($url, [
                'api-key' => $apiKey,
            ]);

            // Check if the response was successful
            if ($response->successful()) {
                $articles = $response->json()['response']['docs'] ?? [];

                return array_map(function ($article) {
                    return $this->formatArticle($article);
                }, $articles);
            }

            // Log a warning if the response was not successful
            Log::warning('Failed to fetch articles from New York Times API', ['response' => $response->body()]);

        } catch (RequestException $e) {
            // Log any errors that occur during the HTTP request
            Log::error('Error fetching articles from New York Times API', ['error' => $e->getMessage()]);
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
            'title' => $article['headline']['main'] ?? '',
            'content' => $article['lead_paragraph'] ?? '',
            'url' => $article['web_url'] ?? '',
            'author' => $article['byline']['original'] ?? 'Unknown',
            'category' => $article['section_name'] ?? 'General',
            'source' => 'NYT',
            'publishedAt' => $article['pub_date'] ?? now(),
        ];
    }
}
