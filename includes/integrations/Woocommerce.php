<?php


namespace NovemBit\wp\plugins\i18n\integrations;


use NovemBit\wp\plugins\i18n\system\Integration;

class Woocommerce extends Integration
{

    public static $integrations = [
    ];

    public static $plugins = [
        'woocommerce/woocommerce.php'
    ];

    public function init(): void
    {

    }
}