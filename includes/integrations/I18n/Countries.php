<?php

namespace NovemBit\wp\plugins\i18n\integrations\I18n;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

class Countries
{

    /**
     * @var I18n
     * */
    private $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;

        if (is_admin()) {
            $this->adminInit();
        }
    }

    /**
     * Admin init
     *
     * @return void
     */
    protected function adminInit(): void
    {
        add_action(
            'admin_menu',
            function () {
                add_submenu_page(
                    Bootstrap::SLUG,
                    'Countries',
                    'Countries',
                    'manage_options',
                    Bootstrap::SLUG . '-integration-i18n-countries',
                    [$this, 'adminContent']
                );
            }
        );
    }

    public function getList()
    {
        $countries = $this->options()['all_countries'];
        return Arrays::map($countries, 'alpha2', 'name');
    }

    public function options()
    {
        return Option::expandOptions($this->settings(), Bootstrap::SLUG);
    }

    private function settings()
    {
        $countries_list = \NovemBit\i18n\system\helpers\Countries::getData();

        return [
            'all_countries' => new Option(
                str_replace('\\', '_', self::class) . '_all_countries',
                $countries_list,
                [
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    'values'      => $countries_list,
                    'template'    => [
                        'name'     => ['type' => Option::TYPE_TEXT],
                        'alpha2'   => ['type' => Option::TYPE_TEXT],
                        'alpha3'   => ['type' => Option::TYPE_TEXT],
                        'numeric'  => ['type' => Option::TYPE_TEXT],
                        'currency' => [
                            'type'   => Option::TYPE_TEXT,
                            'method' => Option::METHOD_MULTIPLE,
                            'label'  => 'Currency'
                        ],
                    ],
                    'label'       => 'To languages',
                    'description' => 'In what languages the site should be translated.'
                ]
            ),
        ];
    }

    public function adminContent()
    {
        Option::printForm(
            Bootstrap::SLUG,
            $this->settings()
        //            [
        //                'on_save_success_message' => 'Successfully saved. To clear cache click <a href="' .
        //                                             $this->getClearCacheUrl() . '">here</a>.'
        //            ]
        );
    }
}
