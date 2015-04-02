<?php

namespace Victoire\Widget\ArticleListBundle\Resolver;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;
use Victoire\Bundle\BlogBundle\Entity\Article;
use Victoire\Bundle\WidgetBundle\Builder\WidgetFormBuilder;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\FilterBundle\Filter\Chain\FilterChain;
use Victoire\Widget\ListingBundle\Resolver\WidgetListingContentResolver;

/**
 * CRUD operations on WidgetRedactor Widget
 *
 * The widget view has two parameters: widget and content
 *
 * widget: The widget to display, use the widget as you wish to render the view
 * content: This variable is computed in this WidgetManager, you can set whatever you want in it and display it in the show view
 *
 * The content variable depends of the mode: static/businessEntity/entity/query
 *
 * The content is given depending of the mode by the methods:
 *  getWidgetStaticContent
 *  getWidgetBusinessEntityContent
 *  getWidgetEntityContent
 *  getWidgetQueryContent
 *
 * So, you can use the widget or the content in the show.html.twig view.
 * If you want to do some computation, use the content and do it the 4 previous methods.
 *
 * If you just want to use the widget and not the content, remove the method that throws the exceptions.
 *
 * By default, the methods throws Exception to notice the developer that he should implements it owns logic for the widget
 *
 */
class WidgetArticleListContentResolver extends WidgetListingContentResolver
{
    private $em;
    private $queryBuilderUpdater;
    private $widgetFormBuilder;

    public function __construct(RequestStack $requestStack, FilterChain $filterChain = null, EntityManager $em, FilterBuilderUpdater $queryBuilderUpdater, WidgetFormBuilder $widgetFormBuilder)
    {
        $this->em = $em;
        $this->queryBuilderUpdater = $queryBuilderUpdater;
        $this->widgetFormBuilder = $widgetFormBuilder;
        parent::__construct($requestStack, $filterChain);
    }

    /**
     * Get the static content of the widget
     * @param Widget $widget
     *
     * @return string The static content
     *
     * @SuppressWarnings checkUnusedFunctionParameters
     */
    public function getWidgetQueryContent(Widget $widget)
    {
        $filterBuilder = $this->getWidgetQueryBuilder($widget);

        $filterBuilder
            ->leftJoin('main_item.blog', 'blog')
            ->addOrderBy('main_item.publishedAt', 'DESC')
            ->addOrderBy('main_item.createdAt', 'DESC')
            ->andWhere('main_item.status = :status')
            ->orWhere('main_item.status = :scheduled_status AND main_item.publishedAt > :publicationDate')
            ->setParameter('status', Article::PUBLISHED)
            ->setParameter('scheduled_status', Article::SCHEDULED)
            ->setParameter('publicationDate', new \DateTime());

        $adapter = new DoctrineORMAdapter($filterBuilder->getQuery());

        $pager = new Pagerfanta($adapter);
        if ($widget->getMaxResults() && is_integer($widget->getMaxResults())) {
            $pager->setMaxPerPage($widget->getMaxResults());
        }

        $pager->setCurrentPage($this->request->get('page') ?: 1);

        $articles = $pager->getCurrentPageResults();

        $parameters = parent::getWidgetStaticContent($widget);

        return array_merge($parameters, array('items' => $articles, 'pager' => $pager));
    }
}
