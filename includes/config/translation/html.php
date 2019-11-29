<?php

use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\system\parsers\xml\Rule;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => HTML::class,
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'title_tag_template' => function (array $params) {
        return sprintf(
            '%s | %s, %s',
            $params['translate'],
            mb_convert_case($params['country_native'] ?? ($params['region_native'] ?? ''),
                MB_CASE_TITLE, "UTF-8"),
            mb_convert_case(($params['language_native'] ?? $params['language_name'] ?? ''),
                MB_CASE_TITLE, "UTF-8")
        );
    },
    'xpath_query_map' => include('html/xpath_query_map.php'),
    'save_translations' => false,
];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;