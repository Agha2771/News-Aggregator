<?php

namespace App\Jobs;

use News\Models\Article; // Assuming the Article model is under App\Models
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The article data to be stored.
     *
     * @var array
     */
    protected array $articleData;

    /**
     * The source of the article.
     *
     * @var string
     */
    protected string $source;

    /**
     * Create a new job instance.
     *
     * @param array $articleData
     * @param string $source
     * @return void
     */
    public function __construct(array $articleData, string $source)
    {
        $this->articleData = $articleData;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
            Log::error('Failed to store article: ' . $e->getMessage(), [
                'articleData' => $this->articleData,
                'source' => $this->source,
            ]);
        }
    }
}
