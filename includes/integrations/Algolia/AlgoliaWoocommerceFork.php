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
            add_action(
                'wp_footer',
                function () {
                    ?>
                    <script type="application/javascript">
                        (function () {

                            if (window.hasOwnProperty('novembit')
                                && window.novembit.hasOwnProperty('i18n')
                                && window.novembit.i18n.hasOwnProperty('default_language')
                                && window.hasOwnProperty('algolia')
                                && window.algolia.hasOwnProperty('addFilter')
                            ) {

                                let default_language = window.novembit.i18n.default_language;

                                let removeDefaultLanguageFromPermalink = function (item) {
                                    let pattern = new RegExp('^\/' + default_language + '\/');
                                    item.permalink = item.permalink.replace(pattern, '/');
                                    return item;
                                };

                                let addEditorSuffixToPermalink = function (item) {
                                    let query_key = window.novembit.i18n.prefix + '-' + window.novembit.i18n.editor.query_key;
                                    if (window.novembit.i18n.hasOwnProperty('editor')
                                        && window.novembit.i18n.editor.hasOwnProperty('addParameterToURL')) {
                                        item.permalink = window.novembit.i18n.editor.addParameterToURL(item.permalink, query_key, 1);
                                    }
                                    return item;
                                };

                                window.algolia.addFilter('algoliaWC/infiniteHitsWidgetItemData', removeDefaultLanguageFromPermalink, 1000000000);
                                window.algolia.addFilter('algolia/autocompleteItemData', removeDefaultLanguageFromPermalink, 1000000000);

                                if (window.novembit.i18n.hasOwnProperty('editor')
                                    && window.novembit.i18n.editor.hasOwnProperty('is_editor')
                                    && window.novembit.i18n.editor.is_editor
                                ) {
                                    window.algolia.addFilter('algoliaWC/infiniteHitsWidgetItemData', addEditorSuffixToPermalink, 1000000000);
                                    window.algolia.addFilter('algolia/autocompleteItemData', addEditorSuffixToPermalink, 1000000000);
                                }
                            }
                        })();
                    </script>
                    <?php
                }
            );
        }
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}
