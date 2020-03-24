<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n\Countries;
use NovemBit\wp\plugins\i18n\integrations\I18n\Languages;
use NovemBit\wp\plugins\i18n\system\Integration;

class Brandlight extends Integration
{

    public static $integrations = [
    ];

    public static $rules = [
        [self::class, 'isBrandlightTheme']
    ];


    private $countries_config = [
            [
                'name' => 'Bulgaria',
                'alpha2' => 'bg',
                'alpha3' => 'bgr',
                'numeric' => '100',
                'domain' => '',
                'regions' =>
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
                'name' => 'Croatia',
                'alpha2' => 'hr',
                'alpha3' => 'hrv',
                'numeric' => '191',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'hr',
                    ],
            ],

            [
                'name' => 'Czechia',
                'alpha2' => 'cz',
                'alpha3' => 'cze',
                'numeric' => '203',
                'domain' => 'swanson.co.cz',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'cs',
                    ],
            ],

            [
                'name' => 'Denmark',
                'alpha2' => 'dk',
                'alpha3' => 'dnk',
                'numeric' => '208',
                'domain' => 'swanson.co.dk',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'da',
                    ],
            ],
            [
                'name' => 'Estonia',
                'alpha2' => 'ee',
                'alpha3' => 'est',
                'numeric' => '233',
                'domain' => 'swanson.ee',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'et',
                    ],
            ],
            [
                'name' => 'France',
                'alpha2' => 'fr',
                'alpha3' => 'fra',
                'numeric' => '250',
                'domain' => 'swanson.fr',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'fr',
                    ],
            ],
            [
                'name' => 'Germany',
                'alpha2' => 'de',
                'alpha3' => 'deu',
                'numeric' => '276',
                'domain' => 'swanson.co.de',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'de',
                    ],
            ],
            [
                'name' => 'Italy',
                'alpha2' => 'it',
                'alpha3' => 'ita',
                'numeric' => '380',
                'domain' => 'swanson.it',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'it',
                    ],
            ],
            [
                'name' => 'Netherlands',
                'alpha2' => 'nl',
                'alpha3' => 'nld',
                'numeric' => '528',
                'domain' => 'swanson.nl',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'nl',
                    ],
            ],

            [
                'name' => 'Poland',
                'alpha2' => 'pl',
                'alpha3' => 'pol',
                'numeric' => '616',
                'domain' => 'swanson.pl',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'pl',
                    ],
            ],

            [
                'name' => 'Portugal',
                'alpha2' => 'pt',
                'alpha3' => 'prt',
                'numeric' => '620',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'pt',
                    ],
            ],

            [
                'name' => 'Romania',
                'alpha2' => 'ro',
                'alpha3' => 'rou',
                'numeric' => '642',
                'domain' => 'swanson.co.ro',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'ro',
                    ],
            ],

            [
                'name' => 'Slovakia',
                'alpha2' => 'sk',
                'alpha3' => 'svk',
                'numeric' => '703',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
            ],

            [
                'name' => 'Slovenia',
                'alpha2' => 'si',
                'alpha3' => 'svn',
                'numeric' => '705',
                'domain' => 'swanson.si',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'sl',
                    ],
            ],

            [
                'name' => 'Spain',
                'alpha2' => 'es',
                'alpha3' => 'esp',
                'numeric' => '724',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'es',
                    ],
            ],

            [
                'name' => 'Sweden',
                'alpha2' => 'se',
                'alpha3' => 'swe',
                'numeric' => '752',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
            ],

            [
                'name' => 'United Kingdom of Great Britain',
                'alpha2' => 'gb',
                'alpha3' => 'gbr',
                'numeric' => '826',
                'domain' => 'swanson.co.uk',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'en',
                    ],
            ],

            [
                'name' => 'Korea (Republic of)',
                'alpha2' => 'kr',
                'alpha3' => 'kor',
                'numeric' => '410',
                'domain' => 'swanson.kr',
                'regions' =>
                    [
                         'as',
                    ],
                'languages' =>
                    [
                         'ko',
                    ],
            ],

            [
                'name' => 'Singapore',
                'alpha2' => 'sg',
                'alpha3' => 'sgp',
                'numeric' => '702',
                'domain' => 'swanson.sg',
                'regions' =>
                    [
                         'as',
                    ],
                'languages' =>
                    [
                         'en',
                    ],
            ],

            [
                'name' => 'New Zealand',
                'alpha2' => 'nz',
                'alpha3' => 'nzl',
                'numeric' => '554',
                'domain' => 'swanson.co.nz',
                'regions' =>
                    [
                         'oc',
                    ],
                'languages' =>
                    [
                         'en',
                    ],
            ],

            [
                'name' => 'Greece',
                'alpha2' => 'gr',
                'alpha3' => 'grc',
                'numeric' => '300',
                'domain' => 'swanson.gr',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'el',
                    ],
            ],

            [
                'name' => 'Sweden',
                'alpha2' => 'se',
                'alpha3' => 'swe',
                'numeric' => '752',
                'domain' => '',
                'regions' =>
                    [
                         'eu',
                    ],
                'languages' =>
                    [
                         'sv',
                    ],
            ],

            [
                'name' => 'Russian',
                'alpha2' => 'ru',
                'alpha3' => 'rus',
                'numeric' => '643',
                'domain' => 'swanson.ru',
                'regions' =>
                    [
                         'as',
                    ],
                'languages' =>
                    [
                         'ru',
                    ],
            ],
    ];

    public static function isBrandlightTheme()
    {
        $theme = wp_get_theme();
        if ($theme->get_template() == 'brandlight') {
            return true;
        }

        return false;
    }

    public function init(): void
    {
        /**
         * Restrict admin interface
         * */
        add_filter(Bootstrap::SLUG . '-admin-restricted-mode', '__return_true');

        add_filter(
            Option::getOptionFilterName('request>source_type_map', Bootstrap::SLUG),
            function ($patterns) {
                $patterns = $patterns ?? [];
                $patterns['/woocommerce_gpf\/google.*/is'] = 'gpf_xml';
                return $patterns;
            }
        );

        add_filter(
            Option::getOptionFilterName('translation>url>path_exclusion_patterns', Bootstrap::SLUG),
            function ($patterns) {
                $patterns = $patterns ?? [];
                if (array_search('/\/var\/.*/is', $patterns) === false) {
                    $patterns[] = '/\/var\/.*/is';
                }
                return $patterns;
            }
        );
        
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
                    if (! in_array($item['alpha2'], $allow)) {
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
                    if (! in_array($item['alpha1'], $allow)) {
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
