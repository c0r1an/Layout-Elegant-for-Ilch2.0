<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Layouts\Elegant\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'name' => 'Elegant*',
        'version' => '1.0.0',
        'ilchCore' => '2.2.0',
        'author' => 'c0r1an',
        'link' => 'https://ilch.de',
        'desc' => 'Edles Premium-Layout mit goldenen Akzenten, Magazin-Bloecken, Hero-Slider und rechter Sidebar.',
        'modulekey' => 'elegant',
        'layouts' => [
            'panel' => [
                ['module' => 'user', 'controller' => 'login'],
                ['module' => 'user', 'controller' => 'regist'],
            ],
        ],
        'settings' => [],
    ];

    public function __construct(?\Ilch\Translator $translator = null)
    {
        parent::__construct($translator);

        $moduleConfig = new \Modules\Elegant\Config\Config($translator);
        $this->config['settings'] = $moduleConfig->getLayoutSettings();
    }

    public function getUpdate(string $installedVersion): string
    {
        return '"elegant" update executed.';
    }
}
