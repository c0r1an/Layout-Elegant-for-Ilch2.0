<?php

namespace Modules\Elegant\Boxes;

abstract class BaseBox extends \Ilch\Box
{
    protected function isHomePage(): bool
    {
        $request = $this->getLayout()->getRequest();
        $requestModule = strtolower((string) $request->getModuleName());
        $requestController = strtolower((string) $request->getControllerName());
        $requestAction = strtolower((string) $request->getActionName());
        $startPage = strtolower((string) $this->getLayout()->getConfigKey('start_page'));

        if (strpos($startPage, 'module_') === 0) {
            $startModule = substr($startPage, 7);

            return $requestModule === $startModule
                && ($requestController === '' || $requestController === 'index')
                && ($requestAction === '' || $requestAction === 'index');
        }

        if (strpos($startPage, 'page_') === 0) {
            $startPageId = substr($startPage, 5);

            return $requestModule === 'admin'
                && $requestController === 'page'
                && $requestAction === 'show'
                && (string) $request->getParam('id') === (string) $startPageId;
        }

        if (strpos($startPage, 'layouts_') === 0) {
            $startLayoutModule = substr($startPage, 8);

            return $requestModule === $startLayoutModule
                && ($requestController === '' || $requestController === 'index')
                && ($requestAction === '' || $requestAction === 'index');
        }

        return $requestModule === 'index'
            && ($requestController === '' || $requestController === 'index')
            && ($requestAction === '' || $requestAction === 'index');
    }

    protected function stringSetting(string $key): string
    {
        return trim((string) $this->getLayout()->getLayoutSetting($key));
    }

    protected function boolSetting(string $key): bool
    {
        return $this->getLayout()->getLayoutSetting($key) == 1;
    }

    protected function visibilitySetting(string $key, string $default = 'all'): string
    {
        $visibility = strtolower($this->stringSetting($key));

        return in_array($visibility, ['all', 'home'], true) ? $visibility : $default;
    }

    protected function shouldRenderForVisibility(string $key, string $default = 'all'): bool
    {
        $visibility = $this->visibilitySetting($key, $default);

        return $visibility === 'all' || $this->isHomePage();
    }

    protected function isHomepageBuilderBox(): bool
    {
        return (bool) $this->getLayout()->get('elegantHomepageBuilderBox');
    }

    protected function shouldRenderHomepageSection(string $enabledKey, string $visibilityKey, string $default = 'all'): bool
    {
        if ($this->isHomepageBuilderBox()) {
            return true;
        }

        return $this->boolSetting($enabledKey) && $this->shouldRenderForVisibility($visibilityKey, $default);
    }

    protected function assetUrl(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('~^(?:https?:)?//|^data:~i', $value)) {
            return $value;
        }

        return $this->getLayout()->getBaseUrl($value);
    }
}
