<?php


namespace NovemBit\wp\plugins\i18n\shortcodes;


class Switcher
{

    public static $name = 'novembit-i18n-language-switcher';

    private static $id = 0;

    /**
     *
     */
    public static function init()
    {
        add_shortcode(self::$name, [self::class, 'callback']);
    }

    /**
     * @param $atts
     * @return string
     */
    public static function callback($atts)
    {
        self::$id++;

        $atts = shortcode_atts(array(
            'id' => self::$name . '-' . self::$id,
            'class' => self::$name,
            'mode'=>'popup',
            'title' => __('Change language', 'novembit-18n'),
        ), $atts);

        $html = sprintf(
            "<div id=\"%s\" class=\"%s\" data-mode=\"%s\"><span class=\"loading\">Loading...</span></div>",
            $atts['id'],
            $atts['class'],
            $atts['mode']
        );

        wp_enqueue_style(self::$name,
            plugins_url('/includes/shortcodes/assets/switcher/style.css', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.1'
        );

        wp_enqueue_script(self::$name,
            plugins_url('/includes/shortcodes/assets/switcher/script.js', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.2',
            true
        );

        return $html;
    }
}