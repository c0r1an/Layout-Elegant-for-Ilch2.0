<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Layouts\Elegant\Config;

use Ilch\Config\Database;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'name' => 'Elegant*',
        'version' => '1.0.1',
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

    public function uninstall()
    {
        $databaseConfig = new Database($this->db());
        $startPage = strtolower((string) $databaseConfig->get('start_page'));

        if (in_array($startPage, ['module_elegant', 'layouts_elegant'], true)) {
            $databaseConfig->set('start_page', 'module_article');
        }
    }

    public function getUpdate(string $installedVersion): string
    {
        return '"elegant" update executed.';
    }
}
