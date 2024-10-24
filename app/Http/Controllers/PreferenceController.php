<?php

namespace App\Http\Controllers;

use App\Http\Middleware\XSSProtection;
use News\Repositories\Preference\PreferenceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use News\Enums\ResponseMessage;
use News\Repositories\Article\ArticleRepositoryInterface;
use News\ValidationRequests\StoreUserPreferencesRequest;
use News\Resources\UserPreferenceResource;
use News\Traits\ApiResponseTrait;

class PreferenceController extends Controller
{
    use ApiResponseTrait; 

    protected PreferenceRepositoryInterface $userPreferenceRepository;
    protected ArticleRepositoryInterface $articleRepository;

    public function __construct(
        PreferenceRepositoryInterface $userPreferenceRepository,
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->middleware(XSSProtection::class)->only(['store']);
        $this->userPreferenceRepository = $userPreferenceRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * Get user preferences.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $preferences = $this->userPreferenceRepository->getPreferencesByUserId(Auth::id());
        return $this->successResponse(UserPreferenceResource::collection($preferences) , ResponseMessage::OK , Response::HTTP_OK);
    }

    /**
     * Store user preferences.
     *
     * @param StoreUserPreferencesRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserPreferencesRequest $request): JsonResponse
    {
        $data = $request->prepareRequest();
        $preferences = $this->userPreferenceRepository->createOrUpdatePreferences(Auth::id(), $data);
        return $this->successResponse(new UserPreferenceResource($preferences) , ResponseMessage::CREATED , Response::HTTP_OK);
    }

    /**
     * Fetch personalized news feed based on user preferences.
     *
     * @return JsonResponse
     */
    public function fetchPersonalizedFeed(): JsonResponse
    {

        $preferences = $this->userPreferenceRepository->getPreferencesByUserId(Auth::id());
        $data = [
            'preferred_source' =>  $preferences->pluck('preferred_source'),
            'preferred_category' =>  $preferences->pluck('preferred_category'),
            'preferred_author' =>  $preferences->pluck('preferred_author'),
        ];
        $articles = $this->articleRepository->getPersonalizedArticles($data);
        return $this->successResponse($articles, ResponseMessage::OK , Response::HTTP_OK);
    }
}
