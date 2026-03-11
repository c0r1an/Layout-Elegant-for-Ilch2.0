<?php

namespace Modules\Elegant\Boxes;

class SocialWidget extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('socialWidgetEnabled', 'socialWidgetVisibility', 'all')) {
            $this->getView()->setArray([
                'enabled' => false,
                'title' => '',
                'items' => [],
            ]);
            return;
        }

        $items = [];

        for ($socialIndex = 1; $socialIndex <= 6; $socialIndex++) {
            $icon = $this->stringSetting('socialItem' . $socialIndex . 'Icon');
            $url = $this->stringSetting('socialItem' . $socialIndex . 'Url');

            if ($icon === '') {
                continue;
            }

            $items[] = [
                'icon' => $icon,
                'url' => $url,
            ];
        }

        $this->getView()->setArray([
            'enabled' => true,
            'title' => $this->stringSetting('socialWidgetTitle') ?: 'House Links',
            'items' => $items,
        ]);
    }
}
