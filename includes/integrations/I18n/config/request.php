<?php

use NovemBit\i18n\component\request\interfaces\Request;
use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'restore_non_translated_urls' => new Option(
            'request_restore_non_translated_urls',
            true,
            ['type' => Option::TYPE_BOOL]
        ),
        'allow_editor' => current_user_can('administrator'),
        'default_http_host' => parse_url(site_url(), PHP_URL_HOST),
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

                if (preg_match('/(емисия-на-данни|data-feed|данни-подаване|zdroj-dat|projekt|datafeed|data-foder|Daten-Feed|daten-feed|Projekt|τροφοδοσία-δεδομένων|alimentación-de-datos|proyecto|andmevoog|projekti|flux-de-données|projet|feed-podataka|podaci-uvlačenja|feed-di-dati|progetto|データフィード|데이터-피드|gegevensfeed|data-toevoer|project|plik-danych|źródło-danych|feed-de-dados|projeto|flux-de-date|подача-данных|данные-подачи|проект|podajanje-podatkov|data-flöde)/i',
                    $_SERVER['REQUEST_URI'])) {
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
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;