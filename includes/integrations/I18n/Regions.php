<?php

namespace NovemBit\wp\plugins\i18n\integrations\I18n;

use diazoxide\wp\lib\option\Option;
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
                    'Regions',
                    'Regions',
                    'manage_options',
                    Bootstrap::SLUG . '-integration-i18n-regions',
                    [$this, 'adminContent']
                );
            }
        );
    }

    public function getList()
    {
        $items = $this->options('all');

        return Arrays::map($items, 'code', 'name');
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

    /**
     * @param bool $is_form
     *
     * @return array
     */
    private function settings($is_form = false)
    {
        $regions_list = \NovemBit\i18n\system\helpers\Regions::getData();

        return [
            'all' => new Option(
                str_replace('\\', '_', self::class) . '_all',
                $regions_list,
                [
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    'values'      => $regions_list,
                    'template'    => [
                        'name' => ['type' => Option::TYPE_TEXT],
                        'code' => ['type' => Option::TYPE_TEXT, 'label' => 'Code']
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
            $this->settings(true)
        );
    }
}
