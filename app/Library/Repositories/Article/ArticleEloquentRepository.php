<?php 

namespace News\Repositories\Article;
use Carbon\Carbon;
use News\Abstracts\EloquentRepository;
use News\Models\Article;

class ArticleEloquentRepository extends EloquentRepository implements  ArticleRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Article();
    }

    public function getAllArticles(array $filters, int $perPage = 10)
    {
        $query = $this->model->query();

        if (isset($filters['keyword'])) {
            $query->where('title', 'like', '%' . $filters['keyword'] . '%');
        }

        if (isset($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        return $query->paginate($perPage);
    }

    public function getArticleById($id)
    {
        return $this->model->findOrFail($id);
    }
}
