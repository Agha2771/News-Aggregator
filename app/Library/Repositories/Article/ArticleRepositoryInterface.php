<?php 
namespace News\Repositories\Article;
use News\Abstracts\RepositoryInterface;

interface ArticleRepositoryInterface extends RepositoryInterface
{
    public function getAllArticles(array $filters, int $perPage = 10);
    public function getArticleById($id);
    public function getPersonalizedArticles($preferences);
}
