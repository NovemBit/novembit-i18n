<?php

namespace NovemBit\wp\plugins\i18n;

use Exception;
use NovemBit\i18n\component\languages\Languages;
use NovemBit\i18n\component\request\Request;
use NovemBit\i18n\component\rest\Rest;
use NovemBit\i18n\component\translation\method\Dummy;
use NovemBit\i18n\component\translation\method\Google;
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

    public static function init()
    {

        self::defineConstants();

        Module::instance(
            [
                'translation' => [
                    'class' => Translation::class,
                    'method' => [
                        /*'class' => NovemBit\i18n\component\translation\method\RestMethod::class,
                        'remote_host'=>'i18n.adcleandns.com',
                        'api_key' => 'demo_key_123',
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true*/

                        'class' => Google::class,
                        'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true,

                        /*
                        'class' => Dummy::class,
                        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
                        'validation' => true,
                        'save_translations' => true*/
                    ],
                    'text' => [
                        'class' => Text::class,
                        'save_translations' => true,
                        /*'exclusions' => [ "Hello"],*/
                    ],
                    'url' => [
                        'class' => URL::class,
                        'url_validation_rules' => [
                            'host' => [
                                '^$|^swanson\.co\.uk$|^swanson\.fr$',
                            ]
                        ]
                    ],
                    'html' => [
                        'class' => HTML::class,
                        'fields_to_translate' => [
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
                            ['rule' => ['tags' => ['button']], 'attrs' => ['data-value' => 'text'], 'text' => 'text'],
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
                            /*vaa_cap_publish_ebay_listings*/
                            /*[
                                'rule' => [
                                    'tags' => [
                                        '/label/'
                                    ],
                                    'attrs'=>[
                                        'for'=>['/(\b(?!vaa_cap_publish_ebay_listings)\b\S+)/']
                                    ],
                                    'mode'=>Rule::REGEX
                                ],
                                'attrs' => ['title' => 'text', 'alt' => 'text', 'data-tooltip' => 'text', 'data-tip'=>'text'],
                                'text' => 'text'
                            ],*/
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
                    ]
                ],
                'languages' => [
                    'class' => Languages::class,
                    'accept_languages' => ['ar', 'hy', 'fr', 'it', 'de', 'ru', 'en'],
                    'from_language' => 'en',
                    'default_language' => [
                        'swanson.fr' => 'fr',
                        'swanson.am' => 'hy',
                        'swanson.it' => 'it',
                        'swanson.ru' => 'ru',
                        'swanson.co.uk' => 'en',
                        'default' => 'en'
                    ],
                    'path_exclusion_patterns' => [
                        '.*\.php',
                        '.*\.jpg',
                        '.*wp-admin',
                    ],
                ],
                'request' => [
                    'class' => Request::class,
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
                        'demo_key_123'
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

//        add_action('init',function (){
        Module::instance()->start();
//        });

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
     * Define constants
     */
    private static function defineConstants()
    {

    }
}