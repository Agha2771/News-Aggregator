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
            // Check if 'preferred_source' is not empty and does not contain null
            if (count($preferences['preferred_source']) > 0 && !in_array(null, $preferences['preferred_source']->toArray())) {
                $query->whereIn('source', $preferences['preferred_source']);
            }
    
            // Check if 'preferred_category' is not empty and does not contain null
            if (count($preferences['preferred_category']) > 0 && !in_array(null, $preferences['preferred_category']->toArray())) {
                $query->whereIn('category', $preferences['preferred_category']);
            }
    
            // Check if 'preferred_author' is not empty and does not contain null
            if (count($preferences['preferred_author']) > 0 && !in_array(null, $preferences['preferred_author']->toArray())) {
                $query->whereIn('author', $preferences['preferred_author']);
            }
        }
    
        return $query->get();
    }
      
}
