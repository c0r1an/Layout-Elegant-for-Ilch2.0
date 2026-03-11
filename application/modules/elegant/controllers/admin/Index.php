<?php

namespace Modules\Elegant\Controllers\Admin;

class Index extends \Ilch\Controller\Admin
{
    public function indexAction()
    {
        $this->redirect(['controller' => 'settings', 'action' => 'index']);
    }
}
