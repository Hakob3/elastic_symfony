<?php

namespace App\Controller\Elasticsearch;

use App\Entity\Article;
use App\Service\Elasticsearch\ElasticsearchFacade;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleSearchController extends AbstractController
{
    /**
     * @param Request $request
     * @param ElasticsearchFacade $indexingService
     * @return Response
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    #[Route('/search_article', name: 'app_search_article', methods: ['GET'])]
    public function searchArticle(
        Request $request,
        ElasticsearchFacade $indexingService
    ): Response
    {
        $queryString = $request->query->get('q');
        if ($queryString) {
            $articles = $indexingService->searchDocument($queryString, Article::class);
        }

        return new JsonResponse($articles ?? []);
    }
}
