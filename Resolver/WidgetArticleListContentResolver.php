<?php

namespace Victoire\Widget\ArticleListBundle\Resolver;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\HttpFoundation\RequestStack;
use Victoire\Bundle\WidgetBundle\Builder\WidgetFormBuilder;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Widget\FilterBundle\Filter\Chain\FilterChain;
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
    }

    /**
     * Get the static content of the widget
     * @param Widget $widget
     *
     * @return string The static content
     *
     * @SuppressWarnings checkUnusedFunctionParameters
     */
    public function getWidgetStaticContent(Widget $widget)
    {
        //create the form
        $filterForm = $this->widgetFormBuilder->buildWidgetForm($widget, $widget->getView(), null, null, Widget::MODE_STATIC);

        // initialize a query builder
        $filterBuilder = $this->em->getRepository('VictoireBlogBundle:Article')
            ->createQueryBuilder('article')
            ->where('article.status = :status')
            ->setParameter('status', 'published');

        //If a maxResults param is passed, we add a "limit" clause
        if ($widget->getMaxResults()) {
            $filterBuilder->setMaxResults($widget->getMaxResults());
        }

        //web order by the publicationDate
        $filterBuilder->orderBy('article.publishedAt', 'DESC');

        // build the query from the given form object
        $this->queryBuilderUpdater->addFilterConditions($filterForm, $filterBuilder);
        $articles = $filterBuilder->getQuery()->execute();

        $parameters = parent::getWidgetStaticContent($widget);

        return array_merge($parameters, array('items' => $articles));
    }
}
