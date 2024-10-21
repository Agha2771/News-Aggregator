<?php

namespace News\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class NewsApiService
{
    /**
     * Fetch articles from the NewsAPI.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        try {
            $url = config('services.newsapi.url');
            $apiKey = config('services.newsapi.key');

            $response = Http::get($url . $apiKey);

            // Check if the response was successful
            if ($response->successful()) {
                $articles = $response->json()['articles'] ?? [];

                return array_map(function ($article) {
                    return $this->formatArticle($article);
                }, $articles);
            }

            // Log any unsuccessful response
            Log::warning('Failed to fetch articles from NewsAPI', ['response' => $response->body()]);

        } catch (RequestException $e) {
            // Log any errors that occur during the HTTP request
            Log::error('Error fetching articles from NewsAPI', ['error' => $e->getMessage()]);
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
            'title' => $article['title'] ?? '',
            'content' => $article['content'] ?? '',
            'url' => $article['url'] ?? '',
            'author' => $article['author'] ?? 'Unknown',
            'category' => $article['category'] ?? 'General',
            'source' => 'NewsAPI',
            'publishedAt' => $article['publishedAt'] ?? now(),
        ];
    }
}
