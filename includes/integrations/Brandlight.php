<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n\Countries;
use NovemBit\wp\plugins\i18n\integrations\I18n\Languages;
use NovemBit\wp\plugins\i18n\integrations\I18n\Regions;
use NovemBit\wp\plugins\i18n\system\Integration;

class Brandlight extends Integration
{

    public static $integrations = [
    ];

    public static $rules = [
        [self::class, 'isBrandlightTheme']
    ];


    private static $xpath_query_accept = [
        '//head/title/text()'                                                                =>
            [
                'type' => 'text',
            ],
        '//head/meta[@name="description"]/@content'                                          =>
            [
                'type' => 'text',
            ],
        '//head/link[@rel="canonical" or @rel="next"][1]/@href'                              =>
            [
                'type' => 'url',
            ],
        '//head/meta[@property="og:title" or @property="og:description"]/@content'           =>
            [
                'type' => 'text',
            ],
        '//head/meta[@property="og:url"]/@content'                                           =>
            [
                'type' => 'url',
            ],
        '//head/meta[@name="twitter:title" or @name="twitter:description"]/@content'         =>
            [
                'type' => 'text',
            ],
        '//script[@type="application/ld+json"]/text()'                                       =>
            [
                'type' => 'jsonld',
            ],
        '//*[(self::a or self::strong) and starts-with(text(), "http://default.wp")]/text()' =>
            [
                'type' => 'url',
            ],
        '//input[(@id="affwp-url") and contains(@value, "http://default.wp")]/@value'        =>
            [
                'type' => 'url',
            ],
        '//p/text()'                                                                         =>
            [
                'type' => 'text',
            ],
        '//time/text()'                                                                      =>
            [
                'type' => 'text',
            ],
        '//small/text()'                                                                     =>
            [
                'type' => 'text',
            ],
        '//strong/text()'                                                                    =>
            [
                'type' => 'text',
            ],
        '//b/text()'                                                                         =>
            [
                'type' => 'text',
            ],
        '//bold/text()'                                                                      =>
            [
                'type' => 'text',
            ],
        '//italic/text()'                                                                    =>
            [
                'type' => 'text',
            ],
        '//i/text()'                                                                         =>
            [
                'type' => 'text',
            ],
        '//td/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//th/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//li/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//lo/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h1/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h2/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h3/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h4/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h5/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//h6/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//dt/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//dd/text()'                                                                        =>
            [
                'type' => 'text',
            ],
        '//a/text()'                                                                         =>
            [
                'type' => 'text',
            ],
        '//span/text()'                                                                      =>
            [
                'type' => 'text',
            ],
        '//div/text()'                                                                       =>
            [
                'type' => 'text',
            ],
        '//label/text()'                                                                     =>
            [
                'type' => 'text',
            ],
        '//@title'                                                                           =>
            [
                'type' => 'text',
            ],
        '//@alt'                                                                             =>
            [
                'type' => 'text',
            ],
        '//@data-tooltip'                                                                    =>
            [
                'type' => 'text',
            ],
        '//@data-tip'                                                                        =>
            [
                'type' => 'text',
            ],
        '//*[self::textarea or self::input]/@placeholder'                                    =>
            [
                'type' => 'text',
            ],
        '//*[self::input[@type="button" or @type="submit"]]/@value'                          =>
            [
                'type' => 'text',
            ],
        '//*[self::button]/text()'                                                           =>
            [
                'type' => 'text',
            ],
        '//a/@href'                                                                          =>
            [
                'type' => 'url',
            ],
        '//form/@action'                                                                     =>
            [
                'type' => 'url',
            ],
    ];
    private static $xpath_query_ignore = [
        'ancestor-or-self::*[@translate="no" or starts-with(@for, "payment_method_") or @id="wp-vaa-canonical" or @id="wpadminbar" or @id="query-monitor-main" or contains(@class,"dont-translate")]',
    ];

    /**
     * @param $site_name
     *
     * @return array
     */
    public static function brandlightConfig($site_name)
    {
        return ([
                'default.wp' => [
                    Countries::optionParent() => [
                        'all' => [
                            [
                                'name'      => 'Bulgaria',
                                'alpha2'    => 'bg',
                                'alpha3'    => 'bgr',
                                'numeric'   => '100',
                                'domain'    => '',
                                'regions'   =>
                                    [
                                        'as',
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'bg',
                                    ],
                            ],
                            [
                                'name'      => 'Croatia',
                                'alpha2'    => 'hr',
                                'alpha3'    => 'hrv',
                                'numeric'   => '191',
                                'domain'    => '',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'hr',
                                    ],
                            ],

                            [
                                'name'      => 'Czechia',
                                'alpha2'    => 'cz',
                                'alpha3'    => 'cze',
                                'numeric'   => '203',
                                'domain'    => 'swanson.co.cz',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'cs',
                                    ],
                            ],

                            [
                                'name'      => 'Denmark',
                                'alpha2'    => 'dk',
                                'alpha3'    => 'dnk',
                                'numeric'   => '208',
                                'domain'    => 'swanson.co.dk',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'da',
                                    ],
                            ],
                            [
                                'name'      => 'Estonia',
                                'alpha2'    => 'ee',
                                'alpha3'    => 'est',
                                'numeric'   => '233',
                                'domain'    => 'swanson.ee',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'et',
                                    ],
                            ],
                            [
                                'name'      => 'France',
                                'alpha2'    => 'fr',
                                'alpha3'    => 'fra',
                                'numeric'   => '250',
                                'domain'    => 'swanson.fr',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'fr',
                                    ],
                            ],
                            [
                                'name'      => 'Germany',
                                'alpha2'    => 'de',
                                'alpha3'    => 'deu',
                                'numeric'   => '276',
                                'domain'    => 'swanson.co.de',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'de',
                                    ],
                            ],
                            [
                                'name'      => 'Italy',
                                'alpha2'    => 'it',
                                'alpha3'    => 'ita',
                                'numeric'   => '380',
                                'domain'    => 'swanson.it',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'it',
                                    ],
                            ],
                            [
                                'name'      => 'Netherlands',
                                'alpha2'    => 'nl',
                                'alpha3'    => 'nld',
                                'numeric'   => '528',
                                'domain'    => 'swanson.nl',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'nl',
                                    ],
                            ],

                            [
                                'name'      => 'Poland',
                                'alpha2'    => 'pl',
                                'alpha3'    => 'pol',
                                'numeric'   => '616',
                                'domain'    => 'swanson.pl',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'pl',
                                    ],
                            ],

                            [
                                'name'      => 'Portugal',
                                'alpha2'    => 'pt',
                                'alpha3'    => 'prt',
                                'numeric'   => '620',
                                'domain'    => '',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'pt',
                                    ],
                            ],

                            [
                                'name'      => 'Romania',
                                'alpha2'    => 'ro',
                                'alpha3'    => 'rou',
                                'numeric'   => '642',
                                'domain'    => 'swanson.co.ro',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'ro',
                                    ],
                            ],

                            [
                                'name'    => 'Slovakia',
                                'alpha2'  => 'sk',
                                'alpha3'  => 'svk',
                                'numeric' => '703',
                                'domain'  => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                            ],

                            [
                                'name'      => 'Slovenia',
                                'alpha2'    => 'si',
                                'alpha3'    => 'svn',
                                'numeric'   => '705',
                                'domain'    => 'swanson.si',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'sl',
                                    ],
                            ],

                            [
                                'name'      => 'Spain',
                                'alpha2'    => 'es',
                                'alpha3'    => 'esp',
                                'numeric'   => '724',
                                'domain'    => '',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'es',
                                    ],
                            ],

                            [
                                'name'    => 'Sweden',
                                'alpha2'  => 'se',
                                'alpha3'  => 'swe',
                                'numeric' => '752',
                                'domain'  => '',
                                'regions' =>
                                    [
                                        'eu',
                                    ],
                            ],

                            [
                                'name'      => 'United Kingdom of Great Britain',
                                'alpha2'    => 'gb',
                                'alpha3'    => 'gbr',
                                'numeric'   => '826',
                                'domain'    => 'swanson.co.uk',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                    ],
                            ],

                            [
                                'name'      => 'Korea (Republic of)',
                                'alpha2'    => 'kr',
                                'alpha3'    => 'kor',
                                'numeric'   => '410',
                                'domain'    => 'swanson.kr',
                                'regions'   =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'ko',
                                    ],
                            ],

                            [
                                'name'      => 'Singapore',
                                'alpha2'    => 'sg',
                                'alpha3'    => 'sgp',
                                'numeric'   => '702',
                                'domain'    => 'swanson.sg',
                                'regions'   =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                    ],
                            ],

                            [
                                'name'      => 'New Zealand',
                                'alpha2'    => 'nz',
                                'alpha3'    => 'nzl',
                                'numeric'   => '554',
                                'domain'    => 'swanson.co.nz',
                                'regions'   =>
                                    [
                                        'oc',
                                    ],
                                'languages' =>
                                    [
                                        'en',
                                    ],
                            ],

                            [
                                'name'      => 'Greece',
                                'alpha2'    => 'gr',
                                'alpha3'    => 'grc',
                                'numeric'   => '300',
                                'domain'    => 'swanson.gr',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'el',
                                    ],
                            ],

                            [
                                'name'      => 'Sweden',
                                'alpha2'    => 'se',
                                'alpha3'    => 'swe',
                                'numeric'   => '752',
                                'domain'    => '',
                                'regions'   =>
                                    [
                                        'eu',
                                    ],
                                'languages' =>
                                    [
                                        'sv',
                                    ],
                            ],

                            [
                                'name'      => 'Russian',
                                'alpha2'    => 'ru',
                                'alpha3'    => 'rus',
                                'numeric'   => '643',
                                'domain'    => 'swanson.ru',
                                'regions'   =>
                                    [
                                        'as',
                                    ],
                                'languages' =>
                                    [
                                        'ru',
                                    ],
                            ],
                        ],
                    ],
                    Regions::optionParent()   => [
                        'all' => [
                            [
                                'name'              => 'Africa',
                                'code'              => 'af',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                            [
                                'name'              => 'North America',
                                'code'              => 'na',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                            [
                                'name'              => 'Oceania',
                                'code'              => 'oc',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                            [
                                'name'              => 'Antarctica',
                                'code'              => 'an',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                            [
                                'name'              => 'Asia',
                                'code'              => 'as',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                            [
                                'name'              => 'Europe',
                                'code'              => 'eu',
                                'domain'            => 'swanson.eu',
                                'include_languages' => '1',
                            ],
                            [
                                'name'              => 'South America',
                                'code'              => 'sa',
                                'domain'            => '',
                                'include_languages' => '',
                            ],
                        ]
                    ],
                    Bootstrap::SLUG           => [
                        'localization>global_domain' => 'swanson.co.uk'
                    ]
                ],
                'common'     => [
                    Bootstrap::SLUG => [
                        'request>source_type_map'                          => [
                            '/woocommerce_gpf\/google.*/is' => 'gpf_xml',
                            '/sitemap.xml/is'               => 'sitemap_xml',
                            '/sitemap-index.xml/is'         => 'sitemap_xml',
                            'asd'                           => 'html'
                        ],
                        'translation>url>path_exclusion_patterns'          => [
                            '/\/var\/.*/is',
                        ],
                        'translation>method>remote_host'                   => 'i18n.brandlight.org',
                        'translation>method>api_key'                       => 'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv',
                        'translation>method>exclusions'                    => [
                            'vitamin',
                            'Adidas',
                            'Terry Naturally',
                            'Twinlab',
                            'Shearer Candles',
                            'Stella Sport',
                            'Planetary Herbals',
                            'Reebok',
                            'Fairhaven Health',
                            'Garden of Life',
                            'Dr. Mercola',
                            'Ellyndale',
                            'Doctor\'s Best',
                            'Cosmesis Skin Care (by Life Extension)',
                            'Bounce',
                            'Now Foods',
                            'Jarrow Formulas',
                            'Pip & Nut',
                            'Liberation',
                            'PraNaturals',
                            'Life Extension',
                            'Regime London',
                            'Metabolife',
                            'Source Naturals',
                            'Milkies',
                            'Swanson',
                            'Natural Factors',
                            'Trèsutopia',
                            'Natures Aid',
                            'Brandlight',
                            'Activpet',
                        ],
                        'translation>method>class'                         => Rest::class,
                        'localization>languages>localize_host'             => true,
                        'localization>languages>accept_languages'          => [
                            'cs',
                            'da',
                            'el',
                            'et',
                            'es',
                            'hr',
                            'ja',
                            'ko',
                            'nl',
                            'bg',
                            'pl',
                            'pt',
                            'ro',
                            'sl',
                            'sv',
                            'fr',
                            'it',
                            'de',
                            'ru',
                            'en',
                        ],
                        'localization>languages>from_language'             => 'en',
                        'request>restore_non_translated_urls'              => true,
                        'request>localization_redirects'                   => true,
                        'translation>html_fragment>xpath_query_map>accept' => self::$xpath_query_accept,
                        'translation>html>xpath_query_map>accept'          => self::$xpath_query_accept,
                        'translation>html_fragment>xpath_query_map>ignore' => self::$xpath_query_ignore,
                        'translation>html>xpath_query_map>ignore'          => self::$xpath_query_ignore,
                    ]
                ]
            ])[$site_name] ?? [];
    }

    /**
     * @return bool
     */
    public static function isBrandlightTheme()
    {
        return true;
        $theme = wp_get_theme();
        if ($theme->get_template() == 'brandlight') {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    public function init(): void
    {
        /**
         * Restrict admin interface
         * */
        add_filter(Bootstrap::SLUG . '-admin-restricted-mode', '__return_true', PHP_INT_MAX);


        $site_config = array_merge(
            self::brandlightConfig(parse_url(site_url(), PHP_URL_HOST)),
            self::brandlightConfig('common')
        );

        foreach ($site_config as $parent => $bulk) {
            foreach ($bulk as $option => $config) {
                add_filter(
                    Option::getOptionFilterName($option, $parent),
                    function ($_config) use ($config, $parent) {
                        return $config;
                    },
                    PHP_INT_MAX
                );
            }
        }

//        add_filter(
//            Option::getOptionFilterName('request>source_type_map', Bootstrap::SLUG),
//            function ($patterns) {
//                $patterns                                  = $patterns ?? [];
//                $patterns['/woocommerce_gpf\/google.*/is'] = 'gpf_xml';
//
//                return $patterns;
//            }
//        );
//
//        add_filter(
//            Option::getOptionFilterName('translation>url>path_exclusion_patterns', Bootstrap::SLUG),
//            function ($patterns) {
//                $patterns = $patterns ?? [];
//                if (array_search('/\/var\/.*/is', $patterns) === false) {
//                    $patterns[] = '/\/var\/.*/is';
//                }
//
//                return $patterns;
//            }
//        );

        add_filter(
            Countries::class . '::getDefaultCountriesList',
            function ($list) {
                $allow = [
                    'be',
                    'el',
                    'lt',
                    'pt',
                    'bg',
                    'es',
                    'lu',
                    'ro',
                    'cz',
                    'fr',
                    'hu',
                    'si',
                    'dk',
                    'hr',
                    'mt',
                    'sk',
                    'de',
                    'it',
                    'nl',
                    'fi',
                    'ee',
                    'cy',
                    'at',
                    'se',
                    'ie',
                    'lv',
                    'pl',
                    'gb'
                ];
                foreach ($list as $key => $item) {
                    if ( ! in_array($item['alpha2'], $allow)) {
                        unset($list[$key]);
                    }
                }

                return $list;
            }
        );

        add_filter(
            Languages::class . '::getDefaultLanguagesList',
            function ($list) {
                $allow = [
                    'cs',
                    'da',
                    'el',
                    'et',
                    'es',
                    'hr',
                    'ja',
                    'ko',
                    'nl',
                    'bg',
                    'pl',
                    'pt',
                    'ro',
                    'sl',
                    'sv',
                    'fr',
                    'it',
                    'de',
                    'ru',
                    'en'
                ];
                foreach ($list as $key => $item) {
                    if ( ! in_array($item['alpha1'], $allow)) {
                        unset($list[$key]);
                    }
                }

                return $list;
            }
        );
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}
