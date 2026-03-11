<?php

namespace Modules\Elegant\Boxes;

class PlatformCards extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('platformCardsEnabled', 'platformCardsVisibility', 'home')) {
            $this->getView()->set('cards', []);
            return;
        }

        $cards = [];

        for ($platformIndex = 1; $platformIndex <= 3; $platformIndex++) {
            $card = [
                'icon' => $this->stringSetting('platformIcon' . $platformIndex),
                'title' => $this->stringSetting('platformTitle' . $platformIndex),
                'text' => $this->stringSetting('platformText' . $platformIndex),
                'url' => $this->stringSetting('platformUrl' . $platformIndex),
            ];

            if ($card['title'] === '' && $card['text'] === '' && $card['url'] === '') {
                continue;
            }

            $cards[] = $card;
        }

        $this->getView()->set('cards', $cards);
    }
}
