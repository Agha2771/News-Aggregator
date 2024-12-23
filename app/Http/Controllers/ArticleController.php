<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use News\Repositories\Article\ArticleRepositoryInterface;
use News\Resources\ArticleResource;
use News\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use News\Enums\ResponseMessage;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    use ApiResponseTrait;

    protected ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Display a listing of articles.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        // Retrieve filters from the request
        $filters = $request->only(['keyword', 'date', 'category', 'source']);
        // Generate a unique cache key based on filters
        $cacheKey = 'articles_' . md5(serialize($filters));
        // Try to get cached data
        $paginatedArticles = Cache::remember($cacheKey, 60 * 30, function () use ($filters) {
            // Fetch paginated articles from the repository if not cached
            return $this->articleRepository->getAllArticles($filters);
        });
        // Transform articles into resource collection
        $articles = ArticleResource::collection($paginatedArticles);
        // Prepare the response data
        $responseData = [
            'records' => $articles,
            'meta' => [
                'current_page' => $paginatedArticles->currentPage(),
                'total_pages' => $paginatedArticles->lastPage(),
                'total_records' => $paginatedArticles->total(),
            ],
        ];
    
        return $this->successResponse($responseData, ResponseMessage::OK, Response::HTTP_OK);
    }
    

    /**
     * Display the specified article.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        // Get the article by ID and transform it into a resource
        $article = new ArticleResource($this->articleRepository->getArticleById($id));
        
        return $this->successResponse($article, ResponseMessage::OK, Response::HTTP_OK);
    }
}
