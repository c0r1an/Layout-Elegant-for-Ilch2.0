<?php

namespace Modules\Elegant\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $config = new \Modules\Elegant\Config\Config();
        $homepageSectionKeys = $config->getHomepageSectionKeys();
        $defaultSectionKeys = $config->getDefaultHomepageSectionKeys();
        $savedOrder = (string) $this->getConfig()->get('elegant_homepage_sections');
        $activeSectionKeys = $this->normalizeSectionOrder($savedOrder, $homepageSectionKeys, $defaultSectionKeys);
        $sections = [];

        foreach ($activeSectionKeys as $sectionKey) {
            $html = '';

            switch ($sectionKey) {
                case 'slider':
                    $html = $this->renderHomepageBox('slider');
                    break;
                case 'intro':
                    $html = $this->renderHomepageBox('intro');
                    break;
                case 'platformCards':
                    $html = $this->renderHomepageBox('platformCards');
                    break;
                case 'featureCards':
                    $html = $this->renderHomepageBox('featureCards');
                    break;
                case 'newsBox':
                    $html = $this->renderHomepageBox('newsBox');
                    break;
                case 'contactWidget':
                    $html = $this->renderHomepageBox('contactWidget');
                    break;
                case 'videoWidget':
                    $html = $this->renderHomepageBox('videoWidget');
                    break;
                case 'socialWidget':
                    $html = $this->renderHomepageBox('socialWidget');
                    break;
                default:
                    if (strpos($sectionKey, 'customContent') === 0) {
                        $contentIndex = (int) substr($sectionKey, 13);
                        if ($contentIndex > 0) {
                            $html = trim((string) $this->getConfig()->get('elegant_homepage_customcontent_' . $contentIndex));
                            if ($html !== '') {
                                $html = $this->getLayout()->purify($html);
                                $sections[] = [
                                    'key' => $sectionKey,
                                    'html' => $html,
                                    'columns' => $this->normalizeContentColumns((string) $this->getConfig()->get('elegant_homepage_customcontent_width_' . $contentIndex)),
                                ];
                            }
                        }
                    }
                    break;
            }

            if ($html !== '' && strpos($sectionKey, 'customContent') !== 0) {
                $sections[] = [
                    'key' => $sectionKey,
                    'html' => $html,
                ];
            }
        }

        $this->getView()->setArray([
            'sections' => $sections,
            'siteName' => $this->layoutSettingWithFallback('siteName', 'Elegant*'),
            'siteTagline' => $this->layoutSettingWithFallback('siteTagline', ''),
            'eyebrow' => (string) $this->getConfig()->get('elegant_eyebrow'),
            'headline' => (string) $this->getConfig()->get('elegant_headline'),
            'text' => (string) $this->getConfig()->get('elegant_text'),
            'buttonLabel' => (string) $this->getConfig()->get('elegant_button_label'),
            'buttonUrl' => (string) $this->getConfig()->get('elegant_button_url'),
        ]);
    }

    /**
     * @param string $rawOrder
     * @param string[] $availableSectionKeys
     * @param string[] $defaultSectionKeys
     * @return string[]
     */
    private function normalizeSectionOrder(string $rawOrder, array $availableSectionKeys, array $defaultSectionKeys): array
    {
        $decoded = json_decode($rawOrder, true);

        if (!is_array($decoded)) {
            $decoded = $defaultSectionKeys;
        }

        $normalized = [];
        foreach ($decoded as $sectionKey) {
            $sectionKey = (string) $sectionKey;

            if (in_array($sectionKey, $availableSectionKeys, true) && !in_array($sectionKey, $normalized, true)) {
                $normalized[] = $sectionKey;
            }
        }

        return $normalized;
    }

    private function renderHomepageBox(string $boxKey): string
    {
        $this->getLayout()->set('elegantHomepageBuilderBox', true);
        $html = trim((string) $this->getLayout()->getBox('elegant', $boxKey));
        $this->getLayout()->set('elegantHomepageBuilderBox', false);

        return $html;
    }

    private function normalizeContentColumns(string $value): string
    {
        return in_array($value, ['1', '2', '3', '4'], true) ? $value : '1';
    }

    private function layoutSettingWithFallback(string $key, string $fallback = ''): string
    {
        try {
            return (string) $this->getLayout()->getLayoutSetting($key);
        } catch (\InvalidArgumentException $exception) {
            $settings = (new \Modules\Elegant\Config\Config())->getLayoutSettings();

            if (!empty($settings[$key]) && ($settings[$key]['type'] ?? '') !== 'separator') {
                return (string) ($settings[$key]['default'] ?? $fallback);
            }

            return $fallback;
        }
    }
}
