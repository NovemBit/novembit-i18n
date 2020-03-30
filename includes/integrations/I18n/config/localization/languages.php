<?php

/** @var I18n $this */

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

return [
    'runtime_dir'             => Bootstrap::RUNTIME_DIR,
    'all'                     => $this->languages->getAll(),
];
