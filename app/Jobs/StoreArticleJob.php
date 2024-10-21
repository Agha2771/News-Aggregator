<?php

namespace App\Jobs;

use News\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $articleData;
    protected $source;

    // Number of times to attempt the job
    public $tries = 5;

    // Timeout in seconds for the job
    public $timeout = 60;

    public function __construct(array $articleData, string $source)
    {
        $this->articleData = $articleData;
        $this->source = $source;
    }

    public function handle()
    {
        try {
            Article::updateOrCreate(
                ['title' => $this->articleData['title']],
                [
                    'content' => $this->articleData['content'] ?? '',
                    'url' => $this->articleData['url'],
                    'author' => $this->articleData['author'] ?? 'Unknown',
                    'category' => $this->articleData['category'] ?? 'General',
                    'source' => $this->source,
                    'published_at' => $this->articleData['publishedAt'] ?? now(),
                ]
            );
        } catch (\Exception $e) {
            Log::error('Failed to store article', [
                'error' => $e->getMessage(),
                'article' => $this->articleData,
            ]);

            // Optionally rethrow the exception to indicate failure
            throw $e;
        }
    }
}
