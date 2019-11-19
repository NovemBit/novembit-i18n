<?php

namespace NovemBit\wp\plugins\i18n;

use Exception;

use NovemBit\i18n\Module;
use NovemBit\i18n\system\helpers\Arrays;

class Bootstrap
{
    public static function init()
    {

        add_action('init', function () {

            Module::instance(
                [
                    'translation' => include('config/translation.php'),
                    'languages' => include('config/languages.php'),
                    'request' => include('config/request.php'),
                    'rest' => include('config/rest.php'),
                    'db' => include('config/db.php')
                ]
            );
        }, 10);

        add_action('init', function () {

            Module::instance()->start();

            if (Module::instance()->request->isReady()) {

                add_filter('wp_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

                add_filter('wp_safe_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

                add_filter('redirect_canonical', function () {
                    return false;
                }, PHP_INT_MAX, 2);

                add_action('admin_init', function () {
                    remove_action('admin_head', 'wp_admin_canonical_url');
                }, PHP_INT_MAX);
            }

        }, 11);

//        add_filter('woocommerce_get_country_locale',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_default',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_base', [self::class, 'woocommerceFrontendI18nArray'], PHP_INT_MAX);

        /**
         * Seo framework
         * */
        add_action('init', function () {

            if (!function_exists('\the_seo_framework')) {
                return;
            }

            if ($_SERVER['REQUEST_URI'] == "/sitemap-index.xml") {
                if (!headers_sent()) {
                    \status_header(200);
                    header('Content-type: text/xml; charset=utf-8', true);
                }

                $dom = new \DOMDocument();
                $dom->version  = "1.0";
                $dom->encoding = "utf-8";

                $sitemapindex = $dom->createElement('sitemapindex');
                $sitemapindex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

                $sitemap_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/sitemap.xml";

                $urls = Module::instance()
                    ->translation
                    ->setLanguages(
                        Module::instance()->languages->getAcceptLanguages()
                    )
                    ->url
                    ->translate([$sitemap_url])[$sitemap_url];

                foreach ($urls as $lang => $url) {
                    $sitemap = $dom->createElement('sitemap');
                    $loc = $dom->createElement('loc');
                    $loc->nodeValue = $url;
                    $lastmod = $dom->createElement('lastmod');
                    $lastmod->nodeValue = date('c');
                    $sitemap->appendChild($loc);
                    $sitemap->appendChild($lastmod);
                    $sitemapindex->appendChild($sitemap);
                }

                $dom->appendChild($sitemapindex);

                echo $dom->saveXML();

                die;
            }
        }, 10);
        add_action('init', function () {

            if (!function_exists('\the_seo_framework')) {
                return;
            }

            if (preg_match('/^\/sitemap.xml/',$_SERVER['REQUEST_URI'])) {

                if (!headers_sent()) {
                    \status_header(200);
                    header('Content-type: text/xml; charset=utf-8', true);
                }
                echo \the_seo_framework()->get_view('sitemap/xml-sitemap');
                echo "\n";
                die;
            }

        }, 11);
        add_filter('robots_txt', function ($output, $public) {
            $current_domain = $_SERVER['HTTP_HOST'];
            $default_domain = parse_url(site_url(), PHP_URL_HOST);
            if (!empty($default_domain)) {
                $output = str_replace($default_domain, $current_domain, $output);
            }
            return str_replace('sitemap.xml', 'sitemap-index.xml', $output);
        }, 30, 2);

    }

    public static function woocommerceFrontendI18nArray($array)
    {
        $to_translate = [];
        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use ($to_translate) {
            if (is_string($val)) {
                if ($key == 'label') {
                    $to_translate[] = $val;
                }
            }
        });

        $translates = Module::instance()->request->getTranslation()->text->translate($to_translate);

        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use ($translates) {
            if (is_string($val)) {
                if ($key == 'label') {
                    $val = $translates[$val][Module::instance()->request->getLanguage()] ?? $val;
                }
            }
        });
        return $array;
    }

    public static function logMissingUrl($source_url)
    {
        $dir = WP_CONTENT_DIR . '/novembit-i18n';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $prefix = $_SERVER['HTTP_HOST'] ?? parse_url(site_url(), PHP_URL_HOST) ?? "undefined";

        $file = $dir . '/' . $prefix . '-wrong-urls.log';

        file_put_contents($file, PHP_EOL . $source_url, FILE_APPEND);
    }

    public static function isWPRest()
    {
        $rest_path = get_rest_url(null, '/', 'relative');

        $rest_path = trim($rest_path, '/');

        $url = trim($_SERVER['REQUEST_URI'] ?? '', '/');

        if (substr($url, 0, strlen($rest_path)) === $rest_path) {
            return true;
        }

        return false;
    }

    /**
     * @param $url
     * @return string
     * @throws Exception
     */
    public static function i18n_redirect_fix($url)
    {


        $parsed = parse_url($url);

        if (
            isset($parsed['host']) &&
            !in_array($parsed['host'], [$_SERVER['HTTP_HOST'], parse_url(site_url(), PHP_URL_HOST)])
        ) {
            return $url;
        }

        $language = Module::instance()->request->getLanguage();
        if ($language !== null) {

            $url =  Module::instance()->request->getTranslation()->url->translate([$url])[$url][$language] ?? $url;
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                return $url;
            }
            $url = '/' . ltrim($url, '/');
        }
        return $url;
    }

    const PAGE_NOT_FOUND = 1;
    const GOOGLE_LIMIT_ERROR = 2;

    private static $_notify_messages = [
        self::PAGE_NOT_FOUND => '404 page not found',
        self::GOOGLE_LIMIT_ERROR => 'Google translate limit expired',
    ];

    public static function discordNotify(int $type = self::PAGE_NOT_FOUND, array $data = []): void
    {

        $url = i18n::getOption('discord_webhook', null);

        if ($url == null) {
            return;
        }

        $fields = [
            [
                "name" => "HOST",
                "value" => $_SERVER['HTTP_HOST'] ?? site_url() ?? 'Undefined',
                "inline" => true
            ],
            [
                "name" => "URI",
                "value" => $_SERVER['ORIG_REQUEST_URI'] ?? $_SERVER['REQUEST_URI'] ?? "Undefined",
                "inline" => true
            ]
        ];

        foreach ($data as $key => $value) {
            $fields[] = [
                "name" => $key,
                "value" => $value ?? "Undefined",
                "inline" => false
            ];
        }

        wp_remote_post(
            $url,
            [
                'method' => 'POST',
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode(
                    [
                        'content' => self::$_notify_messages[$type],
                        'embeds' => [
                            [
                                'fields' => $fields
                            ]
                        ]
                    ]
                )
            ]
        );
    }
}