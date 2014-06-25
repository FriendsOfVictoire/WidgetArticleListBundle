<?php

namespace Victoire\Widget\ArticleListBundle\Widget\Manager;


use Victoire\Widget\ArticleListBundle\Form\WidgetArticleListType;
use Victoire\Widget\ArticleListBundle\Entity\WidgetArticleList;


use Victoire\Bundle\CoreBundle\Widget\Managers\BaseWidgetManager;
use Victoire\Bundle\CoreBundle\Entity\Widget;
use Victoire\Bundle\CoreBundle\Widget\Managers\WidgetManagerInterface;

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
class WidgetArticleListManager extends BaseWidgetManager implements WidgetManagerInterface
{
    /**
     * The name of the widget
     *
     * @return string
     */
    public function getWidgetName()
    {
        return 'ArticleList';
    }

    /**
     * Get the static content of the widget
     *
     * @param Widget $widget
     * @return string The static content
     *
     * @SuppressWarnings checkUnusedFunctionParameters
     */
    protected function getWidgetStaticContent(Widget $widget)
    {
        $filterForm = $this->container->get('form.factory')->create(new WidgetArticleListType('Article', '\Victoire\Widget\ArticleListBundle\Entity'), $widget);

        // initialize a query builder
        $filterBuilder = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('VictoireBlogBundle:Article')
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
        $this->container->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);

        $articles = $filterBuilder->getQuery()->execute();

        return $articles;
    }
}
