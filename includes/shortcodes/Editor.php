<?php


namespace NovemBit\wp\plugins\i18n\shortcodes;


use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;

class Editor
{
    public static $name = 'novembit-i18n-translation-editor';

    private static $_id = 0;

    public static function init()
    {

        /**
         * @Todo remove this line. This is temporary shortcode name (Prisna legacy)
         * */
        add_action('wp_enqueue_scripts', [self::class, 'renderAssets']);
        add_shortcode('edit-translations-menu', [self::class, 'callback']);
        add_shortcode(self::$name, [self::class, 'callback']);

    }

    public static function renderAssets()
    {

        wp_enqueue_style(
            self::$name . '-style',
            plugins_url('/includes/shortcodes/assets/dropdown/style.css', Bootstrap::instance()->getPluginFile()),
            [],
            '1.0.6'
        );

    }

    /**
     * @param $atts
     * @return string
     */
    public static function callback($atts)
    {
        $html = '';

        if (!Module::instance()->request->isAllowEditor()) {
            return $html;
        }

        static::$_id++;

        $atts = shortcode_atts(array(
            'id' => self::$name . '-' . self::$_id,
            'class' => implode(' ', [self::$name, 'novembit-i18n-translation-dropdown']),
            'title' => __('Edit Translations', 'novembit-18n'),
            'exit_label' => __('Exit Translations', 'novembit-18n')
        ), $atts);

        $html .= sprintf(
            '<a href="%s" translate="no" id="%s" class="%s"><span class="i18n-label">%s</span></a>',
            Module::instance()->request->isEditor() ? Module::instance()->request->getOrigRequestUri() : '#',
            $atts['id'],
            $atts['class'],
            Module::instance()->request->isEditor() ? $atts['exit_label'] : $atts['title']
        );

        $html .= self::_getURLList();

        return $html;
    }

    /**
     * Get url list
     *
     * @return string
     * */
    private static function _getURLList()
    {
        $html = '<ul translate="no" class="i18n-list">';

        $urls = Module::instance()->request->getEditorUrlTranslations();

        $languages = Module::instance()->localization->languages->getAcceptLanguages(true);

        foreach ($urls as $code => $url) {

            $name = $languages[$code]['name'] ?? $code;
            $flag = $languages[$code]['flag'] ?? null;

            $html .= sprintf(
                '<li><a href="%1$s"><img alt="%3$s" title="%3$s" src="%2$s"><span>%3$s</span></a></li>',
                $url,
                $flag,
                $name
            );
        }

        $html .= "</ul>";

        return $html;
    }
}