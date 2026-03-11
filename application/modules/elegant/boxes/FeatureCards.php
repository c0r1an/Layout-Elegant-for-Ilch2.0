<?php

namespace Modules\Elegant\Boxes;

class FeatureCards extends BaseBox
{
    public function render()
    {
        $cards = [];

        if (!$this->shouldRenderHomepageSection('cardRowEnabled', 'cardRowVisibility', 'home')) {
            $this->getView()->setArray([
                'cards' => [],
                'title' => '',
            ]);
            return;
        }

        for ($cardIndex = 1; $cardIndex <= 4; $cardIndex++) {
            if (!$this->boolSetting('card' . $cardIndex . 'Enabled')) {
                continue;
            }

            $title = $this->stringSetting('card' . $cardIndex . 'Title');
            $text = $this->stringSetting('card' . $cardIndex . 'Text');
            $image = $this->stringSetting('card' . $cardIndex . 'Image');

            if ($title === '' && $text === '' && $image === '') {
                continue;
            }

            $cards[] = [
                'tag' => $this->stringSetting('card' . $cardIndex . 'Tag'),
                'title' => $title,
                'text' => $text,
                'url' => $this->stringSetting('card' . $cardIndex . 'Url'),
                'image' => $this->assetUrl($image),
                'fallbackClass' => 'game-art-' . chr(101 + $cardIndex),
            ];
        }

        $this->getView()->setArray([
            'cards' => $cards,
            'title' => 'Portfolio',
        ]);
    }
}
