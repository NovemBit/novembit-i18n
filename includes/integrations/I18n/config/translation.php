<?php

defined('ABSPATH') || exit;

use NovemBit\i18n\component\translation\Translation;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class' => Translation::class,
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'method' => include('translation/method.php'),
        'text' => include('translation/text.php'),
        'url' => include('translation/url.php'),
        'xml' => include('translation/xml.php'),
        'sitemap_xml' => include('translation/sitiemap_xml.php'),
        'gpf_xml' => include('translation/gpf_xml.php'),
        'html' => include('translation/html.php'),
        'html_fragment' => include('translation/html_fragment.php'),
        'json' => include('translation/json.php'),
        'jsonld' => include('translation/jsonld.php')
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;