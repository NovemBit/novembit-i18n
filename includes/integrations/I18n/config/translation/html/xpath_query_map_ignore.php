<?php

use NovemBit\wp\plugins\i18n\system\Option;

return
    [
        'ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main" or contains(@class,"dont-translate")]',
    ];