<?php

namespace NovemBit\wp\plugins\i18n;

use Exception;
use NovemBit\i18n\component\languages\Languages;
use NovemBit\i18n\component\request\Request;
use NovemBit\i18n\component\rest\Rest;
use NovemBit\i18n\component\translation\Translation;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\i18n\component\translation\type\Text;
use NovemBit\i18n\component\translation\type\URL;
use NovemBit\i18n\component\translation\type\XML;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\component\DB;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\i18n\system\parsers\xml\Rule;

class Bootstrap
{
    public static function init()
    {
        add_action('init', function () {

            Module::instance(
                [
                    'translation' => [
                        'class' => Translation::class,
                        'method' => [
                            'class' => \NovemBit\i18n\component\translation\method\Rest::class,
                            'remote_host' => 'i18n.brandlight.org',
                            'ssl' => false,
                            'api_key' => 'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv',
                            'save_translations' => true,

                            /*'class' => Google::class,
                            'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
                            'validation' => true,
                            'save_translations' => true,*/

                            /*
                            'class' => Dummy::class,
                            'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                            'validation' => true,
                            'save_translations' => true*/

                            'exclusions' => [
                                "vitamin",
                                'Adidas',
                                'Terry Naturally',
                                'Twinlab',
                                'Shearer Candles',
                                'Stella Sport',
                                'Planetary Herbals',
                                'Reebok',
                                'Fairhaven Health',
                                'Garden of Life',
                                'Dr. Mercola',
                                'Ellyndale',
                                'Doctor\'s Best',
                                'Cosmesis Skin Care (by Life Extension)',
                                'Bounce',
                                'Now Foods',
                                'Jarrow Formulas',
                                'Pip & Nut',
                                'Liberation',
                                'PraNaturals',
                                'Life Extension',
                                'Regime London',
                                'Metabolife',
                                'Source Naturals',
                                'Milkies',
                                'Swanson',
                                'Natural Factors',
                                'Trèsutopia',
                                'Natures Aid',
                                'Brandlight',
                                'Activpet',
                            ],

                        ],
                        'text' => [
                            'class' => Text::class,
                            'save_translations' => true,
                            /*'exclusions' => [ "Hello"],*/
                        ],
                        'url' => [
                            'class' => URL::class,
                            'path_translation' => true,
                            'url_validation_rules' => [
                                'scheme' => [
                                    '^(https?)?$'
                                ],
                                'host' => [
                                    sprintf("^$|^%s$|^%s$",
                                        preg_quote($_SERVER['HTTP_HOST'] ?? ''),
                                        preg_quote(parse_url(site_url(), PHP_URL_HOST))
                                    ),
                                ],
                                'path' => [
                                    /**
                                     * @todo query string
                                     * */
                                    '^.*(?<!\.js|\.css|\.map|\.png|\.gif|\.webp|\.jpg|\.sass|\.less)$'
                                ]
                            ]
                        ],
                        'xml' => [
                            'class' => XML::class,
                        ],
                        'sitemap_xml' => [
                            'class' => XML::class,
                            'fields_to_translate' => [
                                [
                                    'rule' => ['tags' => ['loc']],
                                    'text' => 'url'
                                ],
                            ]
                        ],
                        'html' => [
                            'class' => HTML::class,
                            'title_tag_template' => function (
                                array $params
                            ) {

                                return sprintf(
                                    '%s | %s, %s',
                                    $params['translate'],
                                    mb_convert_case($params['country_native'] ?? ($params['region_native'] ?? ''),
                                        MB_CASE_TITLE, "UTF-8"),
                                    mb_convert_case(($params['language_native'] ?? $params['language_name'] ?? ''),
                                        MB_CASE_TITLE, "UTF-8")
                                );
                            },
                            /*
                             * Xpath for parser
                             * */
                            'parser_query' => './/*[not(ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wpadminbar" or @id="query-monitor-main"]) and (text() or @*)]',
                            'fields_to_translate' => [
                                /*
                                 * Json+ld translation
                                 * */
                                [
                                    'rule' => ['tags' => ['script'], 'attrs' => ['type' => ['application/ld+json']]],
                                    'text' => 'jsonld'
                                ],
                                /*
                                 * Standard SEO meta tags
                                 * */
                                [
                                    'rule' => ['tags' => ['meta'], 'attrs' => ['name' => ['description']]],
                                    'attrs' => ['content' => 'text']
                                ],
                                /*
                                 * Canonical url
                                 * */
                                [
                                    'rule' => ['tags' => ['link'], 'attrs' => ['rel' => ['canonical','next']]],
                                    'attrs' => ['href' => 'url']
                                ],
                                /*
                                 * Facebook open graph meta tags
                                 * */
                                [
                                    'rule' => [
                                        'tags' => ['meta'],
                                        'attrs' => ['property' => ['og:description', 'og:title', 'og:site_name']]
                                    ],
                                    'attrs' => ['content' => 'text']
                                ],
                                [
                                    'rule' => ['tags' => ['meta'], 'attrs' => ['property' => ['og:url']]],
                                    'attrs' => ['content' => 'url']
                                ],
                                /*
                                 * Twitter SEO meta tags
                                 * */
                                [
                                    'rule' => [
                                        'tags' => ['meta'],
                                        'attrs' => ['name' => ['twitter:description', 'twitter:title']]
                                    ],
                                    'attrs' => ['content' => 'text']
                                ],
                                /**
                                 * Urls with url text content
                                 * ```html
                                 *  <a href="http://test.com"> http://test.com </a>
                                 * ```
                                 * */
                                [
                                    'rule' => [
                                        'tags' => ['/a/'],
                                        'texts' => [
                                            sprintf(
                                                "/^https?:\\/\\/(%s|%s)\\/.*\$/",
                                                preg_quote($_SERVER['HTTP_HOST'] ?? ''),
                                                preg_quote(parse_url(site_url(), PHP_URL_HOST))
                                            )
                                        ],
                                        'mode' => Rule::REGEX
                                    ],
                                    'text' => 'url',
                                    'attrs' => [
                                        'title' => 'text',
                                        'alt' => 'text',
                                        'href' => 'url',
                                        'data-tooltip' => 'text',
                                        'data-tip' => 'text'
                                    ],
                                ],
                                ['rule' => ['tags' => ['title']], 'text' => 'text'],
                                [
                                    'rule' => ['tags' => ['button']],
                                    'attrs' => ['data-value' => 'text'],
                                    'text' => 'text'
                                ],
                                [
                                    'rule' => [
                                        'tags' => ['input'],
                                        'attrs' => [
                                            'type' => ['submit', 'button']
                                        ]
                                    ],
                                    'attrs' => ['value' => 'text']
                                ],
                                [
                                    'rule' => ['tags' => ['a']],
                                    'attrs' => [
                                        'title' => 'text',
                                        'alt' => 'text',
                                        'href' => 'url',
                                        'data-tooltip' => 'text',
                                        'data-tip' => 'text'
                                    ],
                                    'text' => 'text'
                                ],
                                [
                                    'rule' => ['tags' => ['input', 'textarea']],
                                    'attrs' => ['placeholder' => 'text']
                                ],
                                [
                                    'rule' => [
                                        'tags' => [
                                            'title',
                                            'div',
                                            'strong',
                                            'italic',
                                            'i',
                                            'b',
                                            'label',
                                            'span',
                                            'em',
                                            'h1',
                                            'h2',
                                            'h3',
                                            'h4',
                                            'h5',
                                            'h6',
                                            'li',
                                            'p',
                                            'time',
                                            'th',
                                            'td',
                                            'option',
                                            'nav',
                                            'img'
                                        ],
                                    ],
                                    'attrs' => [
                                        'title' => 'text',
                                        'alt' => 'text',
                                        'data-tooltip' => 'text',
                                        'data-tip' => 'text'
                                    ],
                                    'text' => 'text'
                                ],
                                ['rule' => ['tags' => ['form']], 'attrs' => ['action' => 'url'], 'text' => 'text'],
                            ],
                            'save_translations' => false,
                        ],
                        'json' => [
                            'class' => JSON::class,
                            'save_translations' => false,
                            'fields_to_translate' => [
                                '/^quantity_options>.*$/i' => 'text',
                                '/^price_html$/i' => 'html',
                                '/^availability_html$/i' => 'html',
                            ]
                        ],
                        'jsonld' => [
                            'class' => JSON::class,
                            'name' => 'jsonld',
                            'save_translations' => false,
                            'type_autodetect' => false,
                            'fields_to_translate' => [
                                '/^(name|description)$/i' => 'text',
                                '/^(@id|url)/i' => 'url',
                                '/^(?>@?\w+>)+(name|description$|reviewBody)$/i' => 'text',
                                '/^(?>@?\w+>)+(url|@id)$/i' => 'url',
                                '/^potentialAction>target$/' => function ($val, $language) {
                                    $main_domain = parse_url($val, PHP_URL_HOST);
                                    $current_domain = $_SERVER['HTTP_HOST'] ?? null;

                                    if ($main_domain != $current_domain) {
                                        $val = str_replace($main_domain, $current_domain, $val);
                                    }
                                    return $val;
                                },
                                /*                                '/^(?>@?\w+>)+category$/i' => 'html',*/
                            ]
                        ]
                    ],
                    'languages' => [
                        'class' => Languages::class,
                        'accept_languages' => i18n::getOption(
                            'accept_languages',
                            [
                                'cs',
                                'da',
                                'el',
                                'et',
                                'es',
                                'hr',
                                'ja',
                                'ko',
                                'nl',
                                'bg',
                                'pl',
                                'pt',
                                'ro',
                                'sl',
                                'sv',
                                'fr',
                                'it',
                                'de',
                                'ru',
                                'en'
                            ]
                        ),
                        'from_language' => 'en',
                        'localization_config' => [
                            'default' => ['language' => 'en', 'country' => 'UK', 'region' => 'Europe'],
                            '^.*\.uk$' => ['language' => 'en', 'country' => 'UK'],
                            '^.*\.ca$' => ['language' => 'en', 'country' => 'Canada'],
                            '^.*\.ro$' => ['language' => 'ro', 'country' => 'Romania'],
                            '^.*\.gr$' => ['language' => 'el', 'country' => 'Greece'],
                            '^.*\.sg$' => ['language' => 'en', 'country' => 'Singapore'],
                            '^.*\.fr$' => ['language' => 'fr', 'country' => 'France'],
                            '^.*\.it$' => ['language' => 'it', 'country' => 'Italy'],
                            '^.*\.nl$' => ['language' => 'nl', 'country' => 'Netherlands'],
                            '^.*\.de$' => ['language' => 'de', 'country' => 'Germany'],
                            '^.*\.ru$' => ['language' => 'ru', 'country' => 'Russia'],
                            '^.*\.dk$' => ['language' => 'da', 'country' => 'Denmark'],
                            '^.*\.cz$' => ['language' => 'cs', 'country' => 'Czech Republic'],
                            '^.*\.pl$' => ['language' => 'pl', 'country' => 'Poland'],
                            '^.*\.nz$' => ['language' => 'en', 'country' => 'New Zealand'],
                            '^.*\.si$' => ['language' => 'sl', 'country' => 'Slovenia'],
                            '^.*\.kr$' => ['language' => 'ko', 'country' => 'South Korea'],
                            '^.*\.ee$' => ['language' => 'et', 'country' => 'Estonia'],
                            '^.*\.eu$' => ['language' => 'en', 'region' => 'Europe'],
                            '^.*\.com$' => ['language' => 'en', 'country' => 'UK'],
                            '^.*\.net$' => ['language' => 'en', 'country' => 'UK'],
                            '^.*\.org$' => ['language' => 'en', 'country' => 'UK'],
                        ],
                        'path_exclusion_patterns' => [
                            '.*\.php',
                            '.*wp-admin',
                            '.*wp-json',
                            '(?<=^search)\/.*$'
                        ],

                    ],
                    'request' => [
                        'class' => Request::class,
                        'restore_non_translated_urls' => true,
                        'allow_editor' => current_user_can('administrator'),

                        'source_type_map' => [
                            '/sitemap.xml/is' => 'sitemap_xml',
                            '/sitemap-index.xml/is' => 'sitemap_xml',
                        ],

                        'exclusions' => [
                            function ($request) {

                                if (is_404() || self::isWPRest()) {
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

                            self::logMissingUrl(
                                sprintf("%s -- %s",
                                    Module::instance()->request->getLanguage(),
                                    trim(Module::instance()->request->getDestination(), '/')
                                )
                            );

                            header('Location: ' . site_url() . Module::instance()->request->getDestination());

                            exit;

                            /*add_action('wp', function () {
                                global $wp_query;
                                $wp_query->set_404();
                                status_header(404);
                            });*/
                        }
                    ],
                    'rest' => [
                        'class' => Rest::class,
                        'api_keys' => [
                            'demo_key_123',
                            'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv'
                        ]
                    ],
                    'db' => [
                        'class' => DB::class,
                        'connection' => i18n::getOption('db_connection', [
                            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                            'username' => DB_USER,
                            'password' => DB_PASSWORD,
                            'charset' => 'utf8mb4',
                            'tablePrefix' => 'i18n_',
                            'enableQueryCache' => true,
                            'queryCacheDuration' => 10000,
                            /*'enableSchemaCache' => true,
                            'schemaCacheDuration' => 3000,
                            'schemaCache' => 'i18n',*/
                        ]),
                    ]
                ]
            );

            add_filter('wp_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

            add_filter('wp_safe_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

            add_filter('redirect_canonical', function () {
                if (Module::instance()->request->isReady()) {
                    return false;
                }
                return true;
            }, PHP_INT_MAX, 2);

            add_action('admin_init', function () {
                if (Module::instance()->request->isReady()) {
                    remove_action('admin_head', 'wp_admin_canonical_url');
                }
            }, PHP_INT_MAX);

        }, 10);

        add_action('init', function () {
            Module::instance()->start();
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

            if ($_SERVER['REQUEST_URI'] == "/sitemap.xml") {
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
        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use($to_translate) {
            if (is_string($val)) {
                if ($key == 'label') {
                    $to_translate[] = $val;
                }
            }
        });

        $translates = Module::instance()->request->getTranslation()->text->translate($to_translate);

        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use($translates) {
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
        $url = $_SERVER['REQUEST_URI'];
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

        $i18n = Module::instance();
        if (!$i18n->request->isReady()) {
            return $url;
        }

        $parsed = parse_url($url);

        if (
            isset($parsed['host']) &&
            !in_array($parsed['host'], [$_SERVER['HTTP_HOST'], parse_url(site_url(), PHP_URL_HOST)])
        ) {
            return $url;
        }


        $language = $i18n->request->getLanguage();
        if ($language !== null) {

            $url = $i18n->request->getTranslation()->url->translate([$url])[$url][$language] ?? $url;
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