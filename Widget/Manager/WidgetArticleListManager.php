<?php

namespace Victoire\ArticleListBundle\Widget\Manager;


use Victoire\ArticleListBundle\Form\WidgetArticleListType;
use Victoire\ArticleListBundle\Entity\WidgetArticleList;

class WidgetArticleListManager
{
protected $container;

    /**
     * constructor
     *
     * @param ServiceContainer $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * create a new WidgetArticleList
     * @param Page   $page
     * @param string $slot
     *
     * @return $widget
     */
    public function newWidget($page, $slot)
    {
        $widget = new WidgetArticleList();
        $widget->setPage($page);
        $widget->setslot($slot);

        return $widget;
    }
    /**
     * render the WidgetArticleList
     * @param Widget $widget
     *
     * @return widget show
     */
    public function render($widget)
    {
        $filterForm = $this->container->get('form.factory')->create(new WidgetArticleListType('Article', '\Victoire\ArticleListBundle\Entity'), $widget);

        // initialize a query builder
        $filterBuilder = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('VictoireBlogBundle:Article')
            ->createQueryBuilder('article')
            ->setMaxResults($widget->getMaxResults());

        // build the query from the given form object
        $this->container->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $filterBuilder);
        // // now look at the DQL =)
        // var_dump($filterBuilder->getDql());

        $articles = $filterBuilder->getQuery()->execute();

        return $this->container->get('victoire_templating')->render(
            "VictoireArticleListBundle::show.html.twig",
            array(
                "articles" => $articles,
                "widget"   => $widget
            )
        );
    }

    /**
     * render WidgetArticleList form
     * @param Form           $form
     * @param WidgetArticleList $widget
     * @param BusinessEntity $entity
     * @return form
     */
    public function renderForm($form, $widget, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireArticleListBundle::edit.html.twig",
            array(
                "widget" => $widget,
                'form'   => $form->createView(),
                'id'     => $widget->getId(),
                'entity' => $entity
            )
        );
    }

    /**
     * create a form with given widget
     * @param WidgetArticleList $widget
     * @param string         $entityName
     * @param string         $namespace
     * @return $form
     */
    public function buildForm($widget, $entityName = null, $namespace = null)
    {
        $form = $this->container->get('form.factory')->create(new WidgetArticleListType($entityName, $namespace), $widget);

        return $form;
    }

    /**
     * create form new for WidgetArticleList
     * @param Form           $form
     * @param WidgetArticleList $widget
     * @param string         $slot
     * @param Page           $page
     * @param string         $entity
     *
     * @return new form
     */
    public function renderNewForm($form, $widget, $slot, $page, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireArticleListBundle::new.html.twig",
            array(
                "widget"          => $widget,
                'form'            => $form->createView(),
                "slot"            => $slot,
                "entity"          => $entity,
                "renderContainer" => true,
                "page"            => $page
            )
        );
    }
}
