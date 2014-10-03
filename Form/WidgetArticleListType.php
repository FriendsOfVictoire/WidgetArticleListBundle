<?php

namespace Victoire\Widget\ArticleListBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Widget\ListingBundle\Form\WidgetListingType;

/**
 * WidgetArticleList form type
 */
class WidgetArticleListType extends WidgetListingType
{

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $noValidationClosure = function (QueryInterface $filterQuery, $field, $values) {
                return false;
        };

        $builder
            ->add('title', 'filter_text', array(
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'label'             => 'widget.articlelist.form.type.title.label'))
            ->add('maxResults', 'integer', array(
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.maxResults.label',
                'required'     => false
            ))
            ->add('globalLinkTitle', null, array(
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkTitle.label',
            ))
            ->add('globalLinkUrl', null, array(
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkUrl.label',
            ))
            ->add('globalLinkLabel', null, array(
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkLabel.label',
            ));

        parent::buildForm($builder, $options);
    }

    /**
     * bind form to WidgetArticleList entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'data_class'        => 'Victoire\Widget\ArticleListBundle\Entity\WidgetArticleList',
            'validation_groups' => array('filtering'), // avoid NotBlank() constraint-related message
            'widget'             => 'articlelist',
            'translation_domain' => 'victoire'
        ));
    }

    /**
     * get form name
     *
     * @return string The name of the form
     */
    public function getName()
    {
        return 'victoire_widget_form_articlelist';
    }
}
