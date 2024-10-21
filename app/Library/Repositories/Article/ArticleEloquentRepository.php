<?php 

namespace News\Repositories\Article;

use Carbon\Carbon;
use News\Abstracts\EloquentRepository;
use News\Models\Article;

class ArticleEloquentRepository extends EloquentRepository implements ArticleRepositoryInterface
{
    public function __construct(Article $article)
    {
        $this->model = $article;
    }

    public function getAllArticles(array $filters, int $perPage = 10)
    {
        $query = $this->model->query();

        // Filter by keyword in the title (case insensitive)
        if (isset($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($keyword) . '%']);
        }

        // Filter by published date
        if (isset($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        // Filter by category
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filter by source
        if (isset($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        return $query->paginate($perPage);
    }

    public function getArticleById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getPersonalizedArticles($preferences)
    {
        $query = $this->model->query();

        if ($preferences) {
            if ($preferences->preferred_source) {
                $query->where('source', $preferences->preferred_source);
            }
            if ($preferences->preferred_category) {
                $query->where('category', $preferences->preferred_category);
            }
            if ($preferences->preferred_author) {
                $query->where('author', $preferences->preferred_author);
            }
        }

        return $query->paginate(10);
    }
}
