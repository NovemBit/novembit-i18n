<?php

use NovemBit\i18n\component\translation\type\JSON;

return
    [
        'class' => JSON::class,
        'save_translations' => false,
        'fields_to_translate' => [
            '/^price_html$/i' => 'html',
            '/^availability_html$/i' => 'html',
        ]
    ];
