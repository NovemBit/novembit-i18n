<?php


namespace NovemBit\wp\plugins\i18n;


class Integration extends system\Integration
{

    public static $integrations = [
        \NovemBit\wp\plugins\i18n\integrations\I18n::class,
        \NovemBit\wp\plugins\i18n\integrations\Algolia::class,
        \NovemBit\wp\plugins\i18n\integrations\Woocommerce::class,
        \NovemBit\wp\plugins\i18n\integrations\TheSEOFramework::class
    ];

    protected function init(): void
    {
        // TODO: Implement init() method.
    }
}