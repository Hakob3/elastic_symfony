<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\Elasticsearch\ElasticsearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    /**
     * @param ElasticsearchService $indexingService
     * @param ArticleRepository $articleRepository
     * @return Response
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \ReflectionException
     */
    #[Route('/test', name: 'app_test')]
    public function index(
        ElasticsearchService $indexingService,
        ArticleRepository $articleRepository
    ): Response
    {
//        $indexingService->deleteIndex(Article::class);
        $indexingService->createIndex(Article::class);
//        dd($indexingService->searchDocument('sleep', Article::class));
//        foreach ($articleRepository->findAll() as $article){
//            VarDumper::dump($indexingService->indexingEntity($article)->asArray());
//            VarDumper::dump($indexingService->getDocument($article));
//        }
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
