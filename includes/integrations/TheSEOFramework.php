<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use DOMDocument;
use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Integration;

use function status_header;

class TheSEOFramework extends Integration
{

    public static $plugins = [
        'autodescription/autodescription.php'
    ];

    public static $functions = [
        '\the_seo_framework'
    ];

    public function init(): void
    {
        add_filter(
            Option::getOptionFilterName('request_source_type_map', Bootstrap::SLUG),
            static function ($map) {
                $map['/sitemap.xml/is']       = 'sitemap_xml';
                $map['/sitemap-index.xml/is'] = 'sitemap_xml';

                return $map;
            }
        );

        add_action(
            'init',
            static function () {
                if ($_SERVER['REQUEST_URI'] === '/sitemap-index.xml') {
                    if (! headers_sent()) {
                        status_header(200);
                        header('Content-type: text/xml; charset=utf-8', true);
                    }

                    $dom           = new DOMDocument();
                    $dom->version  = '1.0';
                    $dom->encoding = 'utf-8';

                    $sitemap_index = $dom->createElement('sitemapindex');
                    $sitemap_index->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

                    $sitemap_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sitemap.xml";

                    $languages = Module::instance()->request->getActiveLanguages();

                    if (empty($languages) || Module::instance()->request->isGlobalDomain()) {
                        $languages = Module::instance()->request->getAcceptLanguages();
                    }


                    $urls = Module::instance()
                        ->translation
                        ->setLanguages($languages)
                        ->url
                        ->translate([$sitemap_url])[$sitemap_url];

                    /**
                     * Unset Main /sitemap.xml file from sitemap-index.xml
                     * */
                    unset($urls[array_search($sitemap_url, $urls, true)]);

                    foreach ($urls as $lang => $url) {
                        $sitemap            = $dom->createElement('sitemap');
                        $loc                = $dom->createElement('loc');
                        $loc->nodeValue     = $url;
                        $lastmod            = $dom->createElement('lastmod');
                        $lastmod->nodeValue = date('c');
                        $sitemap->appendChild($loc);
                        $sitemap->appendChild($lastmod);
                        $sitemap_index->appendChild($sitemap);
                    }

                    $dom->appendChild($sitemap_index);

                    echo $dom->saveXML();

                    die;
                }
            },
            11
        );

        add_action(
            'init',
            static function () {
                if (! function_exists('\the_seo_framework')) {
                    return;
                }

                if (preg_match('/^\/sitemap.xml/', $_SERVER['REQUEST_URI'])) {
                    if (! headers_sent()) {
                        status_header(200);
                        header('Content-type: text/xml; charset=utf-8', true);
                    }
                    echo \the_seo_framework()->get_view('sitemap/xml-sitemap');
                    echo "\n";
                    die;
                }
            },
            11
        );

        add_filter(
            'robots_txt',
            static function ($output, $public) {
                $current_domain = $_SERVER['HTTP_HOST'];
                $default_domain = parse_url(site_url(), PHP_URL_HOST);
                if (! empty($default_domain)) {
                    $output = str_replace($default_domain, $current_domain, $output);
                }

                return str_replace('sitemap.xml', 'sitemap-index.xml', $output);
            },
            30,
            2
        );
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}
