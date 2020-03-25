<?php

namespace NovemBit\wp\plugins\i18n\integrations\I18n;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\i18n\component\localization\regions\Regions as CoreRegions;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

class Regions
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
     * @return string|string[]
     */
    public static function optionParent()
    {
        return Bootstrap::SLUG . '-' . str_replace('\\', '_', self::class);
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
                        'Regions',
                        'Regions',
                        'manage_options',
                        Bootstrap::SLUG . '-integration-i18n-regions',
                        [$this, 'adminContent']
                    );
                }
            }
        );
    }

    public function getList()
    {
        $items = $this->options('all');

        return Arrays::map($items, 'code', 'name');
    }

    public function getAll()
    {
        return $this->options('all');
    }

    public function options($name = null, $default = null)
    {
        $options = Option::expandOptions(
            $this->settings(),
            self::optionParent()
        );

        if ($name === null) {
            return $options;
        } else {
            return $options[$name] ?? $default;
        }
    }

    /**
     *
     */
    private function getDefaultRegionsList()
    {
        return apply_filters(
            __METHOD__,
            \NovemBit\i18n\system\helpers\Regions::getData()
        );
    }

    /**
     * @param bool $is_form
     *
     * @return array
     */
    private function settings($is_form = false)
    {
        $regions_list = $this->getDefaultRegionsList();

        return [
            'all' => new Option(
                [
                    'default'     => $regions_list,
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    'values'      => $regions_list,
                    'template'    => [
                        'name'              => ['type' => Option::TYPE_TEXT],
                        'code'              => [
                            'type'         => Option::TYPE_TEXT,
                            'label'        => 'Code',
                            'label_params' => ['style' => 'display:none']
                        ],
                        'domain'            => [
                            'type'         => Option::TYPE_TEXT,
                            'label'        => 'Domain',
                            'label_params' => ['style' => 'display:none']
                        ],
                        'include_languages' => [
                            'type'   => Option::TYPE_TEXT,
                            'values' => [
                                CoreRegions::DONT_INCLUDE_CHILD_LANGUAGES    => 'Dont include',
                                CoreRegions::INCLUDE_CHILD_PRIMARY_LANGUAGES => 'Countries primary languages',
                                CoreRegions::INCLUDE_CHILD_ALL_LANGUAGES     => 'Countries all languages',
                            ],
                            'label'  => 'Include languages'
                        ],
                        'languages'         => [
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

    public function adminContent()
    {
        Option::printForm(
            self::optionParent(),
            $this->settings(true),
            ['title' => 'Regions Configuration']
        );
    }
}
