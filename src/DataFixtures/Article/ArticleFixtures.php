<?php

namespace App\DataFixtures\Article;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            ArticleCategoryFixtures::class
        ];
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $articleFileDir = __DIR__ . '/articles.json';
        $fixtureArticles = json_decode(
            file_get_contents($articleFileDir),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $articleCategoryRepository = $manager->getRepository(ArticleCategory::class);

        foreach ($fixtureArticles as $article) {
            $entity = new Article();
            $entity->setTitle($article['title']);
            $entity->setContent($article['content']);
            $entity->setArticleCategory(
                $articleCategoryRepository->findOneBy(
                    [
                        'name' => $article['categoryName']
                    ]
                )
            );

            $manager->persist($entity);
        }
        $manager->flush();
    }
}