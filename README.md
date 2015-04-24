Victoire Article List Bundle
============

Need to add an article list in a victoire website ?
Get this bundle and so on

First you need to have a valid Symfony2 Victoire edition.
Then you just have to run the following composer command :

    php composer.phar require friendsofvictoire/articlelist-widget

Do not forget to add the bundle in your AppKernel !

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Victoire\Widget\ArticleListBundle\VictoireWidgetArticleListBundle(),
            );
    
            return $bundles;
        }
    }
