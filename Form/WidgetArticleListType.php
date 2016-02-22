<?php

namespace Victoire\Widget\ArticleListBundle\Form;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Victoire\Widget\ListingBundle\Form\WidgetListingType;

class WidgetArticleListType extends WidgetListingType
{
    /**
     * define form fields.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $noValidationClosure = function (QueryInterface $filterQuery, $field, $values) {
                return false;
        };

        parent::buildForm($builder, $options);

        $builder
            ->add('title', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_BOTH,
                'label'             => 'widget.articlelist.form.type.title.label', ])
            ->add('maxResults', IntegerType::class, [
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.maxResults.label',
                'required'     => false,
            ])
            ->add('globalLinkTitle', null, [
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkTitle.label',
            ])
            ->add('globalLinkUrl', null, [
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkUrl.label',
            ])
            ->add('globalLinkLabel', null, [
                'apply_filter' => $noValidationClosure,
                'label'        => 'widget.articlelist.form.type.linkLabel.label',
            ])
            ->remove('targetPattern');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'csrf_protection'    => false,
            'data_class'         => 'Victoire\Widget\ArticleListBundle\Entity\WidgetArticleList',
            'validation_groups'  => ['filtering'], // avoid NotBlank() constraint-related message
            'widget'             => 'articlelist',
            'translation_domain' => 'victoire',
        ]);
    }
}
