<?php

namespace Modules\Elegant\Controllers\Admin;

use Modules\Admin\Mappers\LayoutAdvSettings as LayoutAdvSettingsMapper;
use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

class Settings extends BaseAdmin
{
    private const LAYOUT_KEY = 'elegant';

    public function init()
    {
        $this->addElegantMenu('settings');
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('moduleName'), ['controller' => 'settings', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        $layoutPath = APPLICATION_PATH . '/layouts/' . self::LAYOUT_KEY;
        if (!is_dir($layoutPath) || !file_exists($layoutPath . '/config/config.php')) {
            $this->redirect()
                ->withMessage('layoutNotFoundOrInvalid', 'danger')
                ->to(['controller' => 'settings', 'action' => 'index']);
            return;
        }

        $configClass = '\\Modules\\Elegant\\Config\\Config';
        $config = new $configClass();
        $settings = $config->getLayoutSettings();

        $this->getLayout()->getTranslator()->load($layoutPath . '/translations/');

        $layoutAdvSettingsMapper = new LayoutAdvSettingsMapper();
        if ($this->getRequest()->isPost()) {
            $postedSettings = [];
            foreach ($settings as $key => $value) {
                if (($value['type'] ?? '') === 'separator') {
                    continue;
                }

                $layoutAdvSettingsModel = new LayoutAdvSettingsModel();
                $layoutAdvSettingsModel
                    ->setLayoutKey(self::LAYOUT_KEY)
                    ->setKey($key)
                    ->setValue((string) ($this->getRequest()->getPost($key) ?? ''));

                $postedSettings[] = $layoutAdvSettingsModel;
            }

            $layoutAdvSettingsMapper->save($postedSettings);
            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
            return;
        }

        $this->getView()->setArray([
            'layoutKey' => self::LAYOUT_KEY,
            'settings' => $settings,
            'settingsValues' => $layoutAdvSettingsMapper->getSettings(self::LAYOUT_KEY),
        ]);
    }
}
