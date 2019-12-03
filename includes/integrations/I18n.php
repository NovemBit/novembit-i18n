<?php


namespace NovemBit\wp\plugins\i18n\integrations;


use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;
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

        add_action('init', function () {

            add_action('admin_bar_menu', [self::class, 'adminBarMenu'], 100);

            if (Module::instance()->request->isReady()) {

                add_filter('wp_redirect', [self::class, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                add_filter('wp_safe_redirect', [self::class, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                add_filter('redirect_canonical', function () {
                    return false;
                }, PHP_INT_MAX, 2);

                add_action('admin_init', function () {
                    remove_action('admin_head', 'wp_admin_canonical_url');
                }, PHP_INT_MAX);
            }

        }, 11);

        /**
         * Implement cache deletion
         * */
        if (isset($_GET['novembit-i18n-action'])
            && $_GET['novembit-i18n-action'] == 'clear-cache'
            && current_user_can('administrator')
        ) {
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
            } catch (\Exception $exception) {
                /**
                 * Prevent warnings
                 * */
            }

            wp_redirect(wp_get_referer() ?? site_url());

            exit;
        }
    }

    public function startRequestTranslation(){
        Module::instance()->request->start();
    }

    /**
     * @param $url
     * @return string
     * @throws \Exception
     * @throws InvalidArgumentException
     */
    public static function redirectUrlTranslation($url)
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

    public function createInstance()
    {
        Module::instance(
            [
                /**
                 * Runtime Dir for module global instance
                 * */
                'runtime_dir' => Bootstrap::RUNTIME_DIR,
                /**
                 * Components configs
                 * */
                'translation' => include(__DIR__ . '/I18n/config/translation.php'),
                'languages' => include(__DIR__ . '/I18n/config/languages.php'),
                'request' => include(__DIR__ . '/I18n/config/request.php'),
                'rest' => include(__DIR__ . '/I18n/config/rest.php'),
                'db' => include(__DIR__ . '/I18n/config/db.php'),
            ]
        );
    }
}