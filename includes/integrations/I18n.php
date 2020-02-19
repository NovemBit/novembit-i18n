<?php


namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use Exception;
use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Integration;
use Psr\SimpleCache\InvalidArgumentException;

class I18n extends Integration
{

    public static $classes = [
        Module::class
    ];


    public function init(): void
    {
        /**
         * Creating instance of i18n
         * */
        $this->createInstance();

        add_action('init', [$this, 'startRequestTranslation'], 11);

        add_action(
            'init',
            function () {
                add_action('admin_bar_menu', [$this, 'adminBarMenu'], 100);

                add_action('wp_before_admin_bar_render', [$this, 'adminToolbar'], 100);

                if (Module::instance()->request->isReady()) {
                    add_filter('wp_redirect', [$this, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                    add_filter('wp_safe_redirect', [$this, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                    add_filter(
                        'redirect_canonical',
                        function () {
                            return false;
                        },
                        PHP_INT_MAX,
                        2
                    );

                    add_action(
                        'admin_init',
                        function () {
                            remove_action('admin_head', 'wp_admin_canonical_url');
                        },
                        PHP_INT_MAX
                    );
                }
            },
            11
        );

        /**
         * Implement cache deletion
         * */
        if (
            is_admin()
            && isset($_GET[Bootstrap::SLUG . '-action'])
            && $_GET[Bootstrap::SLUG . '-action'] == 'clear-cache'
            && current_user_can('administrator')
        ) {
            self::deleteI18nCache();
            if (is_admin()) {
                Bootstrap::addNotice('Translation cache successfully cleared.');
            }
            wp_redirect(wp_get_referer() ?? site_url());
            exit;
        }
    }


    /**
     * Delete translation cache pools
     *
     * @return bool
     */
    public static function deleteI18nCache()
    {
        try {
            Module::instance()->getCachePool()->clear();
            Module::instance()->request->getCachePool()->clear();
            Module::instance()->translation->getCachePool()->clear();
            Module::instance()->translation->method->getCachePool()->clear();
            Module::instance()->translation->text->getCachePool()->clear();
            Module::instance()->translation->url->getCachePool()->clear();
            Module::instance()->translation->html_fragment->getCachePool()->clear();
            Module::instance()->translation->html->getCachePool()->clear();
            Module::instance()->translation->xml->getCachePool()->clear();
            Module::instance()->translation->json->getCachePool()->clear();
        } catch (Exception $exception) {
            /**
             * Prevent warnings
             * */
            Bootstrap::addNotice(
                'Can not clear translation cache. Please contact your system administrator.',
                'error'
            );
        }
        return true;
    }

    public function adminToolbar()
    {
        global $wp_admin_bar;

        $languages = Module::instance()->languages->getAcceptLanguages(true);

        /**
         * Translation editor
         * */
        $urls = Module::instance()->request->getEditorUrlTranslations();
        if (!empty($urls)) {
            $args = array(
                'id' => Bootstrap::SLUG . "_item_edit_translation",
                'parent' => Bootstrap::SLUG,
                'title' => __("Edit translation", 'novembit-i18n'),
            );
            $wp_admin_bar->add_node($args);
            foreach ($urls as $language => $url) {
                $flag = $languages[$language]['flag'];
                $name = $languages[$language]['name'];
                $args = [
                    'id' => Bootstrap::SLUG . "_item_edit_translation_item_" . $language,
                    'parent' => Bootstrap::SLUG . "_item_edit_translation",
                    'title' => sprintf(
                        '<img alt="%2$s" src="%1$s" style="display:inline; width: 18px !important; height: 12px !important;"/>&nbsp;%2$s',
                        $flag,
                        $name
                    ),
                    'href' => $url
                ];
                $wp_admin_bar->add_node($args);
            }
        }

        /**
         * Language Switcher
         * */
        $urls = Module::instance()->request->getUrlTranslations();
        if (!empty($urls)) {
            $args = array(
                'id' => Bootstrap::SLUG . "_item_change_language",
                'parent' => Bootstrap::SLUG,
                'title' => __("Change language", 'novembit-i18n'),
            );

            $wp_admin_bar->add_node($args);


            foreach ($urls as $language => $url) {
                $flag = $languages[$language]['flag'];
                $name = $languages[$language]['name'];
                $args = [
                    'id' => Bootstrap::SLUG . "_item_change_language_item" . $language,
                    'parent' => Bootstrap::SLUG . "_item_change_language",
                    'title' => sprintf(
                        '<img alt="%2$s" src="%1$s" style="display:inline; width: 18px !important; height: 12px !important;"/>&nbsp;%2$s',
                        $flag,
                        $name
                    ),
                    'href' => $url
                ];
                $wp_admin_bar->add_node($args);
            }
        }

        return;
    }


    public function getClearCacheUrl()
    {
        return add_query_arg([Bootstrap::SLUG . '-action' => 'clear-cache'], admin_url());
    }

    /**
     * @param \WP_Admin_Bar $admin_bar
     */
    public function adminBarMenu($admin_bar): void
    {
        $admin_bar->add_menu(
            array(
                'id' => 'clear-cache',
                'parent' => Bootstrap::SLUG,
                'title' => __('Clear cache', 'novembit-i18n'),
                'href' => $this->getClearCacheUrl(),
                'meta' => array(
                    'title' => __('Delete temporary caches.', 'novembit-i18n')
                ),
            )
        );
    }

    /**
     * Start request content translation
     *
     * @return void
     * */
    public function startRequestTranslation(): void
    {
        Module::instance()->request->start();
        $language_code = Module::instance()->request->getLanguage();
        $language_data = Module::instance()->languages->getLanguageData($language_code);
        $direction = $language_data['direction'] ?? null;
        if ($direction) {
            global $wp_locale;
            $wp_locale->text_direction = $direction;
        }
    }

    /**
     * @param $url
     *
     * @return string
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function redirectUrlTranslation($url)
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
            $url = Module::instance()->request->getTranslation()->url->translate([$url])[$url][$language] ?? $url;
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                return $url;
            }
            $url = '/' . ltrim($url, '/');
        }
        return $url;
    }

    private $options = [];

    public function createInstance(): void
    {
        add_filter(
            Bootstrap::SLUG . '_translation_content_types',
            function ($types) {
                $types += [
                    'text' => 'Text',
                    'url' => 'URL',
                    'xml' => 'XML',
                    'html' => 'HTML',
                    'html_fragment' => 'HTML Fragment',
                    'sitemap_xml' => 'Sitemap XML',
                    'gpf_xml' => 'Google Product Feed XML',
                    'json' => 'JSON',
                    'jsonld' => "JSON LD"
                ];
                return $types;
            }
        );

        $this->options = [
            /**
             * Runtime Dir for module global instance
             * */
            'runtime_dir' => Bootstrap::RUNTIME_DIR,
            /**
             * Components configs
             * */
            'languages' => include(__DIR__ . '/I18n/config/languages.php'),
            'translation' => include(__DIR__ . '/I18n/config/translation.php'),
            'request' => include(__DIR__ . '/I18n/config/request.php'),
            'rest' => include(__DIR__ . '/I18n/config/rest.php'),
            'db' => include(__DIR__ . '/I18n/config/db.php'),
        ];

        $options = Option::expandOptions($this->options);

        Module::instance(
            $options
        );

        if (is_admin()) {
            $this->adminInit();
        }
    }

    public function getOptionGroup()
    {
        return str_replace('\\', '-', static::class);
    }

    protected function adminInit(): void
    {
        add_action(
            'admin_menu',
            function () {
                add_submenu_page(
                    Bootstrap::SLUG,
                    'i18n options',
                    'i18n configurations',
                    'manage_options',
                    Bootstrap::SLUG . '-integration-i18n',
                    [$this, 'adminContent'],
                    1
                );
            }
        );
    }

    public function adminContent()
    {
        Option::printForm(
            Bootstrap::SLUG,
            $this->options,
            [
                'on_save_success_message' => 'Successfully saved. To clear cache click <a href="' . $this->getClearCacheUrl(
                    ) . '">here</a>.'
            ]
        );
    }
}