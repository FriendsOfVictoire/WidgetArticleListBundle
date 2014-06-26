Victoire CMS Article List Bundle
============

Need to add an article list in a victoire cms website ?
Get this bundle and so on

First you need to have a valid Symfony2 Victoire edition.
Then you just have to run the following composer command :

    php composer.phar require victoire/articlelist-bundle

Do not forget to add the bundle in your AppKernel !

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                ...
                new Victoire\Widget\ArticleListBundle\VictoireArticleListBundle(),
            );
    
            return $bundles;
        }
    }

[![Licence Creative Commons](http://i.creativecommons.org/l/by-nc-sa/3.0/fr/88x31.png)](http://creativecommons.org/licenses/by-nc-sa/3.0/fr/)

This product is provided under [Licence Creative Commns Attributions - No commercial use - Same conditions sharing 3.0 France](http://creativecommons.org/licenses/by-nc-sa/3.0/fr/) terms.
