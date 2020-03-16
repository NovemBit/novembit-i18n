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
    public function getAll()
    {
        return $this->options('all');
    }

    public function getList()
    {
        $items = $this->options('all');

        return Arrays::map($items, 'alpha2', 'name');
    }

    /**
     *
     */
    private function getDefaultCountriesList()
    {
        return apply_filters(
            __METHOD__,
            \NovemBit\i18n\system\helpers\Countries::getData()
        );
    }

    /**
     * @param bool $is_form
     *
     * @return array
     */
    public function settings($is_form = false)
    {
        $countries_list = $this->getDefaultCountriesList();

        return [
            'all' => new Option(
                str_replace('\\', '_', self::class) . '_all',
                $countries_list,
                [
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    'values'      => $countries_list,
                    'template'    => [

                        'name'    => ['type' => Option::TYPE_TEXT],
                        'alpha2'  => ['type' => Option::TYPE_TEXT],
                        'alpha3'  => ['type' => Option::TYPE_TEXT],
                        'numeric' => ['type' => Option::TYPE_TEXT],
                        'domain'  => ['type' => Option::TYPE_TEXT, 'label' => 'Domain'],
                        'regions' => [
                            'type'   => Option::TYPE_TEXT,
                            'method' => Option::METHOD_MULTIPLE,
                            'values' => $is_form ? $this->parent->regions->getList() : [],
                            'label'  => 'Regions'
                        ],

                        'languages' => [
                            'type'   => Option::TYPE_TEXT,
                            'method' => Option::METHOD_MULTIPLE,
                            'values' => $is_form ? $this->parent->languages->getList() : [],
                            'label'  => 'Languages'
                        ],
                    ],
                    'label'       => 'To languages',
                    'description' => 'In what languages the site should be translated.'
                ]
            ),
        ];
    }

    public function options($name = null, $default = null)
    {
        $options = Option::expandOptions($this->settings(), Bootstrap::SLUG);

        if ($name === null) {
            return $options;
        } else {
            return $options[$name] ?? $default;
        }
    }


    public function adminContent()
    {
        Option::printForm(
            Bootstrap::SLUG,
            $this->settings(true)
        );
    }
}
