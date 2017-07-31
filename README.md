Victoire DCMS List Articles Bundle
============

## What is the purpose of this bundle

This bundle installs the *List Articles Widget*.
If you have set up a Victoire's blog, this widget helps you to render a list of your posts and pages with various parameters

## Set Up Victoire

If you haven't already, you can follow the steps to set up Victoire *[here](https://github.com/Victoire/victoire/blob/master/setup.md)*

## Install the Bundle :

    php composer.phar require friendsofvictoire/articlelist-widget

### Reminder

Do not forget to add the bundle in your AppKernel !

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Victoire\Widget\ArticleListBundle\VictoireWidgetArticleListBundle(),
                new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            );

            return $bundles;
        }
    }
