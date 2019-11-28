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
        add_action('wp_enqueue_scripts', [self::class, 'renderAssets']);

        add_shortcode(self::$name, [self::class, 'callback']);
    }

    public static function renderAssets()
    {

        wp_enqueue_style(
            self::$name . '-style',
            plugins_url('/includes/shortcodes/assets/switcher/style.css', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.5'
        );

        wp_enqueue_script(
            self::$name . '-script',
            plugins_url('/includes/shortcodes/assets/switcher/script.js', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.5',
            true
        );

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
            'mode' => 'popup',
            'title' => __('Change language', 'novembit-18n'),
            'loading_label' => __('Edit Translations', 'novembit-18n'),
        ), $atts);

        $html = sprintf(
            "<a id=\"%s\" translate=\"no\" class=\"%s\" data-mode=\"%s\"><span class=\"loading\">{$atts['loading_label']}</span></a>",
            $atts['id'],
            $atts['class'],
            $atts['mode']
        );


        return $html;
    }
}