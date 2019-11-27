<?php

use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\wp\plugins\i18n\Bootstrap;

return
    [
        'class' => JSON::class,
        'runtime_dir'=>Bootstrap::RUNTIME_DIR,
        'save_translations' => false,
        'fields_to_translate' => [
            '/^price_html$/i' => 'html',
            '/^availability_html$/i' => 'html',
        ]
    ];
