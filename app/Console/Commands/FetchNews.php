<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use News\Services\NewsApiService;
use News\Services\NytApiService;
use News\Services\GuardianApiService;
use App\Jobs\StoreArticleJob;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from NewsAPI, NYT, and The Guardian and store them in the database';

    /**
     * The service for fetching NewsAPI articles.
     *
     * @var \App\Services\NewsApiService
     */
    protected $newsApiService;

    /**
     * The service for fetching New York Times articles.
     *
     * @var \App\Services\NytApiService
     */
    protected $nytApiService;

    /**
     * The service for fetching Guardian articles.
     *
     * @var \App\Services\GuardianApiService
     */
    protected $guardianApiService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\NewsApiService $newsApiService
     * @param \App\Services\NytApiService $nytApiService
     * @param \App\Services\GuardianApiService $guardianApiService
     * @return void
     */
    public function __construct(
        NewsApiService $newsApiService,
        NytApiService $nytApiService,
        GuardianApiService $guardianApiService
    ) {
        parent::__construct();

        $this->newsApiService = $newsApiService;
        $this->nytApiService = $nytApiService;
        $this->guardianApiService = $guardianApiService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->fetchAndDispatchArticles($this->newsApiService, 'NewsAPI');
        $this->fetchAndDispatchArticles($this->nytApiService, 'NYT');
        $this->fetchAndDispatchArticles($this->guardianApiService, 'The Guardian');

        $this->info('Articles fetched and dispatched for storage successfully.');

        return 0;
    }

    /**
     * Fetch articles from the given service and dispatch jobs to store them.
     *
     * @param object $service
     * @param string $source
     * @return void
     */
    private function fetchAndDispatchArticles($service, $source)
    {
        $articles = $service->fetchArticles();

        if (!empty($articles)) {
            foreach ($articles as $article) {
                StoreArticleJob::dispatch($article, $source);
            }
        } else {
            $this->warn("No articles found for source: $source");
        }
    }
}
