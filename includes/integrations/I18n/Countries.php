<?php

namespace NovemBit\wp\plugins\i18n\integrations\I18n;

use diazoxide\wp\lib\option\v2\Option;
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
                if (! Bootstrap::instance()->isRestrictedMode()) {
                    add_submenu_page(
                        Bootstrap::SLUG,
                        'Countries',
                        'Countries',
                        'manage_options',
                        Bootstrap::SLUG . '-integration-i18n-countries',
                        [$this, 'adminContent']
                    );
                }
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
     * @return string
     */
    public static function optionParent(): string
    {
        return Bootstrap::SLUG . '-' . str_replace('\\', '_', self::class);
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
                [
                    'default'     => $countries_list,
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    'values'      => $countries_list,
                    'template'    => [
                        'name'      => ['type' => Option::TYPE_TEXT],
                        'alpha2'    => [
                            'type'         => Option::TYPE_TEXT,
                            'label_params' => ['style' => 'display:none'],
                            'label'        => 'Alpha 2 code'
                        ],
                        'alpha3'    => [
                            'type'         => Option::TYPE_TEXT,
                            'label_params' => ['style' => 'display:none'],
                            'label'        => 'Alpha 3 code'
                        ],
                        'numeric'   => [
                            'type'         => Option::TYPE_TEXT,
                            'label_params' => ['style' => 'display:none'],
                            'label'        => 'Numeric value'
                        ],
                        'domain'    => [
                            'type'         => Option::TYPE_TEXT,
                            'label'        => 'Domain',
                            'label_params' => ['style' => 'display:none']
                        ],
                        'regions'   => [
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

    /**
     * @param null $name
     * @param null $default
     *
     * @return array|mixed|null
     */
    public function options($name = null, $default = null)
    {
        $options = Option::expandOptions(
            $this->settings(),
            self::optionParent()
        );

        if ($name === null) {
            return $options;
        }

        return $options[$name] ?? $default;
    }


    /**
     * @return void
     */
    public function adminContent(): void
    {
        Option::printForm(
            self::optionParent(),
            $this->settings(true),
            ['title' => 'Countries Configuration']
        );
    }
}
