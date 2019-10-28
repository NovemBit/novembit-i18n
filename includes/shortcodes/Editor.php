<?php


namespace NovemBit\wp\plugins\i18n\shortcodes;


class Editor
{
    public static $name = 'novembit-i18n-translation-editor';

    private static $id = 0;

    public static function init()
    {

        /**
         * @Todo remove this line. This is temporary shortcode name (Prisna legacy)
         * */
        add_shortcode('edit-translations-menu', [self::class, 'callback']);

        add_shortcode(self::$name, [self::class, 'callback']);

    }


    public static function callback($atts)
    {
        self::$id++;

        $atts = shortcode_atts(array(
            'id' => self::$name . '-' . self::$id,
            'class' =>  self::$name,
            'title' => __('Edit Translations', 'novembit-18n'),
            'exit_label' => __('Exit Translations', 'novembit-18n')
        ), $atts);

        $html = sprintf(
            "<div id=\"%s\" class=\"%s\" data-title=\"%s\" data-exit_label=\"%s\"><span class=\"loading\">Loading...</span></div>",
            $atts['id'],
            $atts['class'],
            $atts['title'],
            $atts['exit_label']
        );

        wp_enqueue_style(self::$name,
            plugins_url('/includes/shortcodes/assets/editor/style.css', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.2'
        );

        wp_enqueue_script(self::$name,
            plugins_url('/includes/shortcodes/assets/editor/script.js', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.2',
            true
        );

        $html.=do_shortcode('['.Switcher::$name.']');

        return $html;
    }
}