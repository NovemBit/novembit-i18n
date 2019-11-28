<?php

use NovemBit\i18n\component\translation\type\HTMLFragment;
use NovemBit\wp\plugins\i18n\Bootstrap;

return
    [
        'class' => HTMLFragment::class,
        'runtime_dir'=>Bootstrap::RUNTIME_DIR,
        'xpath_query_map' => include('html/xpath_query_map.php'),

    ];