<?php


namespace NovemBit\wp\plugins\i18n\integrations\Algolia;


use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\system\Integration;

class AlgoliaWoocommerceFork extends Integration
{

    public static $plugins = [
        'algoliasearch-woocommerce-fork/algolia-woocommerce.php'
    ];


    public function init(): void
    {
        add_action('init', [$this, 'fixPermalinkIssues'], 11);
    }

    public function fixPermalinkIssues()
    {
        if (Module::instance()->request->isReady()) {

            add_action('wp_footer', function () {
                $js = <<<js
(function () {

    if (window.hasOwnProperty('novembit')
        && window.novembit.hasOwnProperty('i18n')
        && window.novembit.i18n.hasOwnProperty('default_language')
    ) {

        let default_language = window.novembit.i18n.default_language;

        let removeDefaultLanguageFromPermalink = function (item) {
            let pattern = new RegExp('^\/' + default_language + '\/');
            item.permalink = item.permalink.replace(pattern, '/');
            return item;
        };

        if (window.hasOwnProperty('algolia') && window.algolia.hasOwnProperty('addFilter')) {
            window.algolia.addFilter('algoliaWC/infiniteHitsWidgetItemData', removeDefaultLanguageFromPermalink, 1000000000);
            window.algolia.addFilter('algolia/autocompleteItemData', removeDefaultLanguageFromPermalink, 1000000000);
        }
    }

})();
js;
                echo sprintf('<script type="application/javascript">%s</script>',
                    $js
                );
            });
        }
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}