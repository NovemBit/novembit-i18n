<?php

namespace NovemBit\wp\plugins\i18n;

use Exception;
use NovemBit\i18n\component\languages\exceptions\LanguageException;
use NovemBit\i18n\component\languages\Languages;
use NovemBit\i18n\component\request\exceptions\RequestException;
use NovemBit\i18n\component\request\Request;
use NovemBit\i18n\component\rest\Rest;
use NovemBit\i18n\component\translation\exceptions\TranslationException;
use NovemBit\i18n\component\translation\method\Dummy;
use NovemBit\i18n\component\translation\method\Google;
use NovemBit\i18n\component\translation\method\Method;
use NovemBit\i18n\component\translation\rest\Dynamic;
use NovemBit\i18n\component\translation\Translation;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\i18n\component\translation\type\Text;
use NovemBit\i18n\component\translation\type\URL;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\component\DB;
use NovemBit\i18n\system\parsers\html\Rule;

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
                            'class' => Dynamic::class,
                            'type' => 'method',
                            'remote_host' => 'i18n.brandlight.org',
                            'ssl' => false,
                            'api_key' => 'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv',
                            'validation' => true,
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
                                    '^$|^' . preg_quote($_SERVER['HTTP_HOST'] ?? parse_url(site_url(),
                                            PHP_URL_HOST)) . '$',
                                ],
                                'path' => [
                                    /**
                                     * @todo query string
                                     * */
                                    '^.*(?<!js|css|map|png|gif|webp|jpg|sass|less)$'
                                ]
                            ]
                        ],
                        'html' => [
                            'class' => HTML::class,

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
                                '/^name$/i' => 'text',
                                '/^(?>@?\w+>)+name$/i' => 'text',
                                '/^(?>@?\w+>)+description$/i' => 'text',
                                '/^(?>@?\w+>)+url/i' => 'url',
                            ]
                        ]
                    ],
                    'languages' => [
                        'class' => Languages::class,
                        'accept_languages' => [
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
                        ],
                        'from_language' => 'en',
                        'default_language' => [
                            'swanson.fr' => 'fr',
                            'swanson.it' => 'it',
                            'swanson.ru' => 'ru',
                            'swanson.co.uk' => 'en',
                            'default' => 'en'
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

            Module::instance()->start();

        });


        add_filter('redirect_canonical', function () {
            if (Module::instance()->request->isIsReady()) {
                return false;
            }
            return true;
        }, PHP_INT_MAX, 2);

        add_action('admin_init', function () {
            if (Module::instance()->request->isIsReady()) {
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
        if (!$i18n->request->isIsReady()) {
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