<?php

use NovemBit\i18n\component\translation\Translation;

return
    [
        'class' => Translation::class,
        'method' => include('translation/method.php'),
        'text' => include('translation/text.php'),
        'url' => include('translation/url.php'),
        'xml' => include('translation/xml.php'),
        'sitemap_xml' => include('translation/sitiemap_xml.php'),
        'html' => include('translation/html.php'),
        'html_fragment' => include('translation/html_fragment.php'),
        'json' => include('translation/json.php'),
        'jsonld' => include('translation/jsonld.php')
    ];
