<?php

namespace NovemBit\wp\plugins\i18n\integrations\I18n;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

class Languages
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
                    'Languages',
                    'Languages',
                    'manage_options',
                    Bootstrap::SLUG . '-integration-i18n-languages',
                    [$this, 'adminContent']
                );
            }
        );
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return array|mixed|null
     */
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
     * @return array
     */
    public function getList()
    {
        return Arrays::map($this->getAll(), 'alpha1', 'name');
    }

    /**
     * @return array|mixed|null
     */
    public function getAll()
    {
        return $this->options('all', []);
    }

    /**
     * @return array
     */
    private function getDefaultLanguagesList()
    {
        return apply_filters(
            __METHOD__,
            \NovemBit\i18n\system\helpers\Languages::getData()
        );
    }

    /**
     * @param bool $is_form
     *
     * @return array
     */
    private function settings($is_form = false)
    {
        $languages_list = $this->getDefaultLanguagesList();

        return [
            'all' => new Option(
                str_replace('\\', '_', self::class) . '_all',
                $languages_list,
                [
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    //                    'main_params' => ['style' => 'grid-template-columns: repeat(1, 1fr);'],
                    'values'      => $languages_list,
                    'template'    => [
                        'alpha1'    => ['type' => Option::TYPE_TEXT],
                        'name'      => ['type' => Option::TYPE_TEXT, 'label' => 'Name'],
                        'native'    => ['type' => Option::TYPE_TEXT, 'label' => 'Native'],
                        'domain'    => ['type' => Option::TYPE_TEXT, 'label' => 'Domain'],
                        'countries' => [
                            'type'   => Option::TYPE_TEXT,
                            'method' => Option::METHOD_MULTIPLE,
                            'values' => $is_form ? $this->parent->countries->getList() : [],
                            'label'  => 'Countries'
                        ],
                    ],
                    'label'       => 'To languages',
                    'description' => 'In what languages the site should be translated.'
                ]
            ),
        ];
    }

    /**
     * @return void
     */
    public function adminContent(): void
    {
        Option::printForm(
            Bootstrap::SLUG,
            $this->settings(true),
            ['title' => 'Languages Configuration']
        );
    }
}
