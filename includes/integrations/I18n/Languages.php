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

    public function options()
    {
        return Option::expandOptions($this->settings(), Bootstrap::SLUG);
    }

    public function getList()
    {
        $countries = $this->options()['all_languages'] ?? [];

        return Arrays::map($countries, 'alpha1', 'name');
    }

    public function getAll()
    {
        return $this->options()['all_languages'] ?? [];
    }


    private function settings()
    {
        $languages_list = \NovemBit\i18n\system\helpers\Languages::getData();

        return [
            'all_languages' => new Option(
                str_replace('\\', '_', self::class) . '_all_languages',
                $languages_list,
                [
                    'type'        => Option::TYPE_GROUP,
                    'method'      => Option::METHOD_MULTIPLE,
                    //                    'main_params' => ['style' => 'grid-template-columns: repeat(1, 1fr);'],
                    'values'      => $languages_list,
                    'template'    => [
                        'alpha1' => ['type' => Option::TYPE_TEXT],
                        'name'   => ['type' => Option::TYPE_TEXT],
                        'native' => ['type' => Option::TYPE_TEXT],
                        'flag'   => ['type' => Option::TYPE_TEXT, 'values' => $this->parent->countries->getList()]
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
