<?php

use NovemBit\i18n\component\translation\type\JSON;

return
    [
        'class' => JSON::class,
        'name' => 'jsonld',
        'save_translations' => false,
        'type_autodetect' => false,
        'fields_to_translate' => [
            '/^(name|description)$/i' => 'text',
            '/^(@id|url)/i' => 'url',
            '/^(?>@?\w+>)+(name|description$|reviewBody)$/i' => 'text',
            '/^(?>@?\w+>)+(url|@id)$/i' => 'url',
            '/^potentialAction>target$/' => function ($val, $language) {
                $main_domain = parse_url($val, PHP_URL_HOST);
                $current_domain = $_SERVER['HTTP_HOST'] ?? null;

                if ($main_domain != $current_domain) {
                    $val = str_replace($main_domain, $current_domain, $val);
                }
                return $val;
            },
            '/^(?>@?\w+>)+category$/i' => 'html',
        ]
    ];