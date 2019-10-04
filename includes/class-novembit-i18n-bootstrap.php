<?php

use NovemBit\i18n\Module;

defined('ABSPATH') || exit;

class NovemBit_i18n_bootstrap
{

    public static function init()
    {

        self::includeFiles();

        self::defineConstants();

        NovemBit\i18n\Module::instance(
            [
                'translation' => [
                    'class' => NovemBit\i18n\component\translation\Translation::class,
                    'method' => [
                        /*'class' => NovemBit\i18n\component\translation\method\RestMethod::class,
                        'remote_host'=>'i18n.adcleandns.com',
                        'api_key' => 'demo_key_123',
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true*/

                        'class' => NovemBit\i18n\component\translation\method\Google::class,
                        'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true,


                        /*'class' => NovemBit\i18n\component\translation\method\Dummy::class,
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true*/
                    ],
                    'text' => [
                        'class' => NovemBit\i18n\component\translation\type\Text::class,
                        'save_translations' => true,
//                'exclusions' => [ "Hello"],
                    ],
                    'url' => [
                        'class' => NovemBit\i18n\component\translation\type\URL::class,
                        'url_validation_rules' => [
                            'host' => [
                                '^$|^swanson\.co\.uk$|^swanson\.fr$',
                            ]
                        ]
                    ],
                    'html' => [
                        'class' => NovemBit\i18n\component\translation\type\HTML::class,
                        'fields_to_translate' => [
                            ['rule' => ['tags' => ['title']], 'text' => 'text'],
                            ['rule' => ['tags' => ['button']], 'attrs' => ['data-value' => 'text'], 'text' => 'text'],
                            [
                                'rule' => ['tags' => ['input'], 'attrs' => ['type' => ['submit']]],
                                'attrs' => ['value' => 'text']
                            ],
                            [
                                'rule' => ['tags' => ['a']],
                                'attrs' => ['href' => 'url', 'data-tooltip' => 'text'],
                                'text' => 'text'
                            ],
                            [
                                'rule' => ['tags' => ['input', 'textarea']],
                                'attrs' => ['placeholder' => 'text']
                            ],
                            [
                                'rule' => [
                                    'tags' => [
                                        'div',
                                        'strong',
                                        'italic',
                                        'i',
                                        'b',
                                        'label',
                                        'span',
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
                                'attrs' => ['title' => 'text', 'alt' => 'text', 'data-tooltip' => 'text'],
                                'text' => 'text'
                            ],
                            ['rule' => ['tags' => ['form']], 'attrs' => ['action' => 'url'], 'text' => 'text'],
                        ],
                        'save_translations' => false,
                    ],
                    'json' => [
                        'class' => NovemBit\i18n\component\translation\type\JSON::class,
                        'save_translations' => false
                    ]
                ],
                'languages' => [
                    'class' => NovemBit\i18n\component\Languages\Languages::class,
                    'accept_languages' => ['ar', 'hy', 'fr', 'it', 'de', 'ru', 'en'],
                    'from_language' => 'en',
                    'default_language' => [
                        'swanson.fr' => 'fr',
                        'swanson.am' => 'hy',
                        'swanson.it' => 'it',
                        'swanson.ru' => 'ru',
                        'swanson.co.uk' => 'hy',
                        'default' => 'en'
                    ],
                    'path_exclusion_patterns' => [
                        '.*\.php',
                        '.*\.jpg',
                        '.*wp-admin',
                    ],
                ],
                'request' => [
                    'class' => NovemBit\i18n\component\Request\Request::class,
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
                    'class' => NovemBit\i18n\component\Rest\Rest::class,
                    'api_keys' => [
                        'demo_key_123'
                    ]
                ],
                'db' => [
                    'class' => NovemBit\i18n\system\component\DB::class,
                    'connection' => [
                        'dsn' => 'mysql:host=localhost;dbname=activerecord',
                        'username' => 'top',
                        'password' => 'top',
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

        NovemBit\i18n\Module::instance()->start();

        add_filter('redirect_canonical', function () {
            return false;
        }, PHP_INT_MAX, 2);

        add_action('admin_init', function () {
            remove_action('admin_head', 'wp_admin_canonical_url');
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

    /**
     * Include composer file
     */
    private static function includeFiles()
    {

        /*
         * Include composer vendor autoload.php file
         * */
        include_once __DIR__ . "/../vendor/autoload.php";

        include_once "class-novembit-i18n.php";
    }

    /**
     * Define constants
     */
    private static function defineConstants()
    {

    }
}