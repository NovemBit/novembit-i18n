<?php

return
    [
        'ignore' => [
            'ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main"]',
            'ancestor-or-self::*[contains(@class,"dont-translate")]'
        ],
        'accept' => [
            /**
             *
             * */
            '//head/title/text()' => ['type' => 'text'],
            '//head/meta[@name="description"]/@content' => ['type' => 'text'],
            '//head/link[@rel="canonical" or @rel="next"][1]/@href' => ['type' => 'url'],
            '//head/meta[@property="og:title" or @property="og:description"]/@content' => ['type' => 'text'],
            '//head/meta[@property="og:url"]/@content' => ['type' => 'url'],
            '//head/meta[@name="twitter:title" or @name="twitter:description"]/@content' => ['type' => 'text'],

            '//script[@type="application/ld+json"]/text()' => ['type' => 'jsonld'],

            /**
             * Headings
             * */
            '//p/text()' => ['type' => 'text'],

            '//small/text()' => ['type' => 'text'],
            '//strong/text()' => ['type' => 'text'],
            '//b/text()' => ['type' => 'text'],
            '//bold/text()' => ['type' => 'text'],
            '//italic/text()' => ['type' => 'text'],
            '//i/text()' => ['type' => 'text'],
            '//td/text()' => ['type' => 'text'],
            '//th/text()' => ['type' => 'text'],
            '//li/text()' => ['type' => 'text'],
            '//lo/text()' => ['type' => 'text'],
            '//h1/text()' => ['type' => 'text'],
            '//h2/text()' => ['type' => 'text'],
            '//h3/text()' => ['type' => 'text'],
            '//h4/text()' => ['type' => 'text'],
            '//h5/text()' => ['type' => 'text'],
            '//h6/text()' => ['type' => 'text'],
            '//a/text()' => ['type' => 'text'],
            '//span/text()' => ['type' => 'text'],
            '//div/text()' => ['type' => 'text'],
            '//label/text()' => ['type' => 'text'],

            '//@title' => ['type' => 'text'],
            '//@alt' => ['type' => 'text'],
            '//@data-tooltip' => ['type' => 'text'],
            '//@data-tip' => ['type' => 'text'],

            '//*[self::textarea or self::input]/@placeholder' => ['type' => 'text'],
            '//*[self::input[@type="button" or @type="submit"]]/@value' => ['type' => 'text'],
            '//*[self::button]/text()' => ['type' => 'text'],

            '//a/@href' => ['type' => 'url'],
            '//form/@action' => ['type' => 'url'],
        ]
    ];