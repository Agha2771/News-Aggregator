<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use News\Repositories\Article\ArticleRepositoryInterface;
use News\Resources\ArticleResource;
use News\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use News\Enums\ResponseMessage;



class ArticleController extends Controller
{
    use ApiResponseTrait;
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'date', 'category', 'source']);
        $articles = ArticleResource::collection($this->articleRepository->getAllArticles($filters));
        return $this->successResponse($articles , ResponseMessage::OK , Response::HTTP_OK);
    }

    public function show($id)
    {
        $article = new ArticleResource($this->articleRepository->getArticleById($id));
        return $this->successResponse($article , ResponseMessage::OK , Response::HTTP_OK);

    }
}
