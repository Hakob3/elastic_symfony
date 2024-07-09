<?php

namespace App\DataFixtures\Article;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
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
        foreach ($fixtureArticles as $article) {
            $entity = new Article();
            $entity->setTitle($article['title']);
            $entity->setContent($article['content']);

            $manager->persist($entity);
        }
        $manager->flush();
    }
}