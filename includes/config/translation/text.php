<?php

use NovemBit\i18n\component\translation\type\Text;
use NovemBit\wp\plugins\i18n\Bootstrap;

return [
    'class' => Text::class,
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'save_translations' => true,
    /*'exclusions' => [ "Hello"],*/
];