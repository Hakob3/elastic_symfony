<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Repository\ArticleRepository;
use App\Service\Elasticsearch\ElasticsearchFacade;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ArticleRepository $articleRepository,
        private readonly ElasticsearchFacade $elasticsearchFacade,

    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            AssociationField::new('articleCategory', 'Category')
                ->formatValue(function ($value, $entity) {
                    return $entity->getArticleCategory() ? $entity->getArticleCategory()->getName() : '';
                })
                ->setFormTypeOption('class', ArticleCategory::class)
                ->setFormTypeOption('choice_label', 'name')
            ,
            TextareaField::new('content')->setRequired(false)
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    /**
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $query = $this->requestStack->getCurrentRequest()->query->get('query');

        if (empty($query)) {
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        }

        $data = $this->elasticsearchFacade->searchDocument($query, Article::class);
        $articleIds = array_column($data, 'id');

        $qb = $this->articleRepository->createQueryBuilder('a');
        $qb->where($qb->expr()->in('a.id', ':ids'))
            ->setParameter('ids', $articleIds);

        return $qb;
    }

}
