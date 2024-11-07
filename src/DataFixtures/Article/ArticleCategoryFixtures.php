<?php

namespace App\DataFixtures\Article;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ArticleCategoryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $articleCategoriesFileDir = __DIR__ . '/articleCategories.json';
        $fixtureArticleCategories = json_decode(
            file_get_contents($articleCategoriesFileDir),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        foreach ($fixtureArticleCategories as $articleCategory) {
            $entity = new ArticleCategory();
            $entity->setName($articleCategory['name']);

            $manager->persist($entity);
        }
        $manager->flush();
    }
}