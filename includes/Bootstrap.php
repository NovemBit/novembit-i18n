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
use NovemBit\i18n\system\parsers\xml\Rule;

class Bootstrap
{

    /**
     */
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
                                    sprintf("^\$|^%s$|^%s$",
                                        preg_quote($_SERVER['HTTP_HOST']),
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
                                /*
                                 * Json+ld translation
                                 * */
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
                                    $params['language_name'],
                                    $params['country'] ?? ($params['region'] ?? '')
                                );
                            },
                            /*
                             * Xpath for parser
                             * */
                            'parser_query' => './/*[not(ancestor-or-self::*[@id=\'wpadminbar\']) and not(ancestor-or-self::*[@translate=\'no\']) and (text() or @*)]',
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
                                            '/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$/'
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
                                    'rule' => ['tags' => ['input'], 'attrs' => ['type' => ['submit']]],
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
                            'save_translations' => false
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
                                /*                                '/^(?>@?\w+>)+category$/i' => 'html',*/
                            ]
                        ]
                    ],
                    'languages' => [
                        'class' => Languages::class,
                        'accept_languages' => i18n::getOption('accept_languages', [
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
                        ]),
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
                            '.*wp-json'
                        ],

                    ],
                    'request' => [
                        'class' => Request::class,
                        'allow_editor' => current_user_can('administrator'),
                        'exclusions' => [
                            function ($request) {

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
                            add_action('wp', function () {
                                global $wp_query;
                                $wp_query->set_404();
                                status_header(404);
                            });
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
                        'connection' => [
                            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                            'username' => DB_USER,
                            'password' => DB_PASSWORD,
                            'charset' => 'utf8mb4',
                            'tablePrefix' => 'i18n_',
                            /*'enableQueryCache' => true,
                            'enableSchemaCache' => true,
                            'schemaCacheDuration' => 3000,
                            'schemaCache' => 'cache',*/
                        ],
                    ]
                ]
            );
        }, 10);

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
            Module::instance()->start();
        }, 11);

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

        add_filter( 'robots_txt', function ( $output, $public ){
            $current_domain = $_SERVER['HTTP_HOST'];
            $default_domain = parse_url( site_url(), PHP_URL_HOST );
            if( ! empty( $default_domain ) ){
                $output = str_replace( $default_domain, $current_domain, $output );
            }
            return str_replace( 'sitemap.xml', 'sitemap-index.xml', $output );
        }, 30, 2 );

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

        add_filter('wp_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

        add_filter('wp_safe_redirect', [self::class, 'i18n_redirect_fix'], PHP_INT_MAX, 1);

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
        $language = $i18n->request->getLanguage();
        if ($language !== null) {
            $url = $i18n->request->getTranslation()->url->translate([$url])[$url][$language];
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                return $url;
            }
            $url = '/' . ltrim($url, '/');
        }
        return $url;
    }
}