<?php

namespace Modules\Elegant\Boxes;

class Slider extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('sliderEnabled', 'sliderVisibility', 'home')) {
            $this->getView()->set('sliderItems', []);
            return;
        }

        $siteName = $this->stringSetting('siteName');
        $siteTagline = $this->stringSetting('siteTagline');
        $sliderItems = [];

        for ($slideIndex = 1; $slideIndex <= 3; $slideIndex++) {
            $title = $this->stringSetting('sliderTitle' . $slideIndex);
            $text = $this->stringSetting('sliderText' . $slideIndex);
            $leftImage = $this->stringSetting('sliderLeftImage' . $slideIndex);
            $centerImage = $this->stringSetting('sliderCenterImage' . $slideIndex);

            if ($title === '' && $text === '' && $leftImage === '' && $centerImage === '') {
                continue;
            }

            $sliderItems[] = [
                'tag' => $this->stringSetting('sliderTag' . $slideIndex),
                'title' => $title,
                'text' => $text,
                'buttonLabel' => $this->stringSetting('sliderButtonLabel' . $slideIndex),
                'buttonUrl' => $this->stringSetting('sliderButtonUrl' . $slideIndex),
                'leftImage' => $this->assetUrl($leftImage),
                'centerImage' => $this->assetUrl($centerImage),
                'fallbackClass' => 'game-art-' . chr(96 + $slideIndex),
            ];
        }

        if (empty($sliderItems)) {
            $sliderItems[] = [
                'tag' => 'Elegant*',
                'title' => $siteName !== '' ? $siteName : 'Elegant*',
                'text' => $siteTagline !== '' ? $siteTagline : 'A clean and elegant presentation for your community.',
                'buttonLabel' => '',
                'buttonUrl' => '',
                'leftImage' => '',
                'centerImage' => '',
                'fallbackClass' => 'game-art-a',
            ];
        }

        $this->getView()->setArray([
            'sliderId' => 'elegantHeroSlider_' . $this->getUniqid(),
            'sliderAutoplay' => $this->boolSetting('sliderAutoplay'),
            'sliderInterval' => max(2000, (int) $this->getLayout()->getLayoutSetting('sliderInterval')),
            'sliderItems' => $sliderItems,
        ]);
    }
}
