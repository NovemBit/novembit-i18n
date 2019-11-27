<?php

use NovemBit\i18n\component\request\interfaces\Request;
use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;


return
    [
        'runtime_dir'=>Bootstrap::RUNTIME_DIR,
        'restore_non_translated_urls' => true,
        'allow_editor' => current_user_can('administrator'),

        'source_type_map' => [
            '/sitemap.xml/is' => 'sitemap_xml',
            '/sitemap-index.xml/is' => 'sitemap_xml',
        ],

        'exclusions' => [
            /**
             * @param Request $request
             * @return bool
             */
            function ($request) {

                /** @var Request $request */

                if(preg_match('/data-feed|wiki/',$_SERVER['REQUEST_URI'])){
                    return true;
                }

                if (is_404() || Bootstrap::isWPRest()) {
                    return true;
                }

                /**
                 * If admin and not doing ajax
                 * And current page not wp-login.php then ignore page
                 * */
                if (
                    (
                        is_admin()
                        && !wp_doing_ajax()
                    )
                    && (
                        !isset($GLOBALS['pagenow']) ||
                        (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] != 'wp-login.php')
                    )
                ) {
                    return true;
                }

                return false;
            }
        ],
        'on_page_not_found' => function () {

            //self::discordNotify(self::PAGE_NOT_FOUND);

            header('Location: ' . site_url() . Module::instance()->request->getDestination());

            exit;

        }
    ];