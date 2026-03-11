<?php

namespace Modules\Elegant\Boxes;

class NewsBox extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('newsBoxEnabled', 'newsBoxVisibility', 'home')) {
            $this->getView()->setArray([
                'enabled' => false,
                'title' => '',
                'content' => '',
            ]);
            return;
        }

        $this->getView()->setArray([
            'enabled' => true,
            'title' => $this->stringSetting('newsBoxTitle') ?: 'Latest News',
            'content' => trim((string) $this->getLayout()->getBox('article', 'article')),
        ]);
    }
}
