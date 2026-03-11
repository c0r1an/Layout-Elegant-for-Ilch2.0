<?php

namespace Modules\Elegant\Controllers\Admin;

class BaseAdmin extends \Ilch\Controller\Admin
{
    protected function addElegantMenu(string $active): void
    {
        $this->getLayout()->addMenu('menuElegant', [
            [
                'name' => 'homepage',
                'active' => $active === 'homepage',
                'icon' => 'fa-solid fa-table-cells-large',
                'url' => $this->getLayout()->getUrl(['controller' => 'home', 'action' => 'index']),
            ],
            [
                'name' => 'settings',
                'active' => $active === 'settings',
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index']),
            ],
        ]);
    }
}
