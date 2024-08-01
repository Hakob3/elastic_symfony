<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\Elasticsearch\IndexingService;
use Doctrine\DBAL\Schema\Index;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class TestController extends AbstractController
{
    /**
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    #[Route('/test', name: 'app_test')]
    public function index(
        IndexingService $indexingService,
        ArticleRepository $articleRepository
    ): Response
    {
        $indexingService->createIndexByEntity(Article::class);
        foreach ($articleRepository->findAll() as $article){
            dd($indexingService->indexingEntity($article));
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
