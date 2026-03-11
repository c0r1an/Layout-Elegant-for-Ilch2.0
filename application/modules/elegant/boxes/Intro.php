<?php

namespace Modules\Elegant\Boxes;

class Intro extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('introEnabled', 'introVisibility', 'home')) {
            $this->getView()->setArray([
                'enabled' => false,
                'title' => '',
                'text' => '',
            ]);
            return;
        }

        $siteName = $this->stringSetting('siteName');
        $siteTagline = $this->stringSetting('siteTagline');
        $title = $this->stringSetting('introTitle');
        $text = $this->stringSetting('introText');

        $this->getView()->setArray([
            'enabled' => true,
            'title' => $title !== '' ? $title : 'Welcome To ' . ($siteName !== '' ? $siteName : 'Elegant*'),
            'text' => $text !== '' ? $text : ($siteTagline !== '' ? $siteTagline : 'A clean, elegant and professional presentation for your community.'),
        ]);
    }
}
