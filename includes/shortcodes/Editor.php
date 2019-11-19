<?php


namespace NovemBit\wp\plugins\i18n\shortcodes;


class Editor
{
    public static $name = 'novembit-i18n-translation-editor';

    private static $_id = 0;

    public static function init()
    {

        /**
         * @Todo remove this line. This is temporary shortcode name (Prisna legacy)
         * */
        add_action('wp_enqueue_scripts',[self::class,'renderAssets']);
        add_shortcode('edit-translations-menu', [self::class, 'callback']);
        add_shortcode(self::$name, [self::class, 'callback']);

    }

    public static function renderAssets(){

        wp_enqueue_style(
            self::$name.'-style',
            plugins_url('/includes/shortcodes/assets/editor/style.css', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.4'
        );

        wp_enqueue_script(
            self::$name.'-script',
            plugins_url('/includes/shortcodes/assets/editor/script.js', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.4',
            true
        );

    }
    public static function callback($atts)
    {
        static::$_id++;

        $atts = shortcode_atts(array(
            'id' => self::$name . '-' . self::$_id,
            'class' => self::$name,
            'title' => __('Edit Translations', 'novembit-18n'),
            'exit_label' => __('Exit Translations', 'novembit-18n'),
            'loading_label' => __('Edit Translations', 'novembit-18n'),

        ), $atts);

        $html = sprintf(
            "<a translate=\"no\" id=\"%s\" class=\"%s\" data-title=\"%s\" data-exit_label=\"%s\"><span class=\"i18n-label\">{$atts['loading_label']}</span></a>",
            $atts['id'],
            $atts['class'],
            $atts['title'],
            $atts['exit_label']
        );

        return $html;
    }
}