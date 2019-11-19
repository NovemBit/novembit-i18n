<?php

use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\system\parsers\xml\Rule;

return [
    'class' => HTML::class,
    'title_tag_template' => function (
        array $params
    ) {

        return sprintf(
            '%s | %s, %s',
            $params['translate'],
            mb_convert_case($params['country_native'] ?? ($params['region_native'] ?? ''),
                MB_CASE_TITLE, "UTF-8"),
            mb_convert_case(($params['language_native'] ?? $params['language_name'] ?? ''),
                MB_CASE_TITLE, "UTF-8")
        );
    },
    'xpath_query_map'=>require 'html/xpath_query_map.php',
    /*
     * Xpath for parser
     * */
    'parser_query' => './/*[not(ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main"]) and (text() or @*)]',
    'fields_to_translate' => [
        /*
         * Json+ld translation
         * */
        [
            'rule' => ['tags' => ['script'], 'attrs' => ['type' => ['application/ld+json']]],
            'text' => 'jsonld'
        ],
        /*
         * Standard SEO meta tags
         * */
        [
            'rule' => ['tags' => ['meta'], 'attrs' => ['name' => ['description']]],
            'attrs' => ['content' => 'text']
        ],
        /*
         * Canonical url
         * */
        [
            'rule' => ['tags' => ['link'], 'attrs' => ['rel' => ['canonical','next']]],
            'attrs' => ['href' => 'url']
        ],
        /*
         * Facebook open graph meta tags
         * */
        [
            'rule' => [
                'tags' => ['meta'],
                'attrs' => ['property' => ['og:description', 'og:title', 'og:site_name']]
            ],
            'attrs' => ['content' => 'text']
        ],
        [
            'rule' => ['tags' => ['meta'], 'attrs' => ['property' => ['og:url']]],
            'attrs' => ['content' => 'url']
        ],
        /*
         * Twitter SEO meta tags
         * */
        [
            'rule' => [
                'tags' => ['meta'],
                'attrs' => ['name' => ['twitter:description', 'twitter:title']]
            ],
            'attrs' => ['content' => 'text']
        ],
        /**
         * Urls with url text content
         * ```html
         *  <a href="http://test.com"> http://test.com </a>
         * ```
         * */
        [
            'rule' => [
                'tags' => ['/a/'],
                'texts' => [
                    sprintf(
                        "/^https?:\\/\\/(%s|%s)\\/.*\$/",
                        preg_quote($_SERVER['HTTP_HOST'] ?? ''),
                        preg_quote(parse_url(site_url(), PHP_URL_HOST))
                    )
                ],
                'mode' => Rule::REGEX
            ],
            'text' => 'url',
            'attrs' => [
                'title' => 'text',
                'alt' => 'text',
                'href' => 'url',
                'data-tooltip' => 'text',
                'data-tip' => 'text'
            ],
        ],
        ['rule' => ['tags' => ['title']], 'text' => 'text'],
        [
            'rule' => ['tags' => ['button']],
            'attrs' => ['data-value' => 'text'],
            'text' => 'text'
        ],
        [
            'rule' => [
                'tags' => ['input'],
                'attrs' => [
                    'type' => ['submit', 'button']
                ]
            ],
            'attrs' => ['value' => 'text']
        ],
        [
            'rule' => ['tags' => ['a']],
            'attrs' => [
                'title' => 'text',
                'alt' => 'text',
                'href' => 'url',
                'data-tooltip' => 'text',
                'data-tip' => 'text'
            ],
            'text' => 'text'
        ],
        [
            'rule' => ['tags' => ['input', 'textarea']],
            'attrs' => ['placeholder' => 'text']
        ],
        [
            'rule' => [
                'tags' => [
                    'title',
                    'div',
                    'strong',
                    'italic',
                    'i',
                    'b',
                    'label',
                    'span',
                    'em',
                    'h1',
                    'h2',
                    'h3',
                    'h4',
                    'h5',
                    'h6',
                    'li',
                    'p',
                    'time',
                    'th',
                    'td',
                    'option',
                    'nav',
                    'img'
                ],
            ],
            'attrs' => [
                'title' => 'text',
                'alt' => 'text',
                'data-tooltip' => 'text',
                'data-tip' => 'text'
            ],
            'text' => 'text'
        ],
        ['rule' => ['tags' => ['form']], 'attrs' => ['action' => 'url'], 'text' => 'text'],
    ],
    'save_translations' => false,
];