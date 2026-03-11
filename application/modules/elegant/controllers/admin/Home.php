<?php

namespace Modules\Elegant\Controllers\Admin;

class Home extends BaseAdmin
{
    private const HOMEPAGE_CONFIG_KEY = 'elegant_homepage_sections';
    private const HOMEPAGE_CONTENT_PREFIX = 'elegant_homepage_customcontent_';
    private const HOMEPAGE_CONTENT_WIDTH_PREFIX = 'elegant_homepage_customcontent_width_';
    private const HOMEPAGE_CONTENT_COUNT = 6;

    public function init()
    {
        $this->addElegantMenu('homepage');
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('moduleName'), ['controller' => 'home', 'action' => 'index'])
            ->add($this->getTranslator()->trans('homepage'), ['action' => 'index']);

        $config = new \Modules\Elegant\Config\Config();
        $availableSectionKeys = $config->getHomepageSectionKeys();
        $sectionMeta = $this->getSectionMeta();
        $databaseConfig = $this->getConfig();

        if ($this->getRequest()->isPost()) {
            $postedOrder = (string) $this->getRequest()->getPost('homepageSections');
            $normalizedOrder = $this->normalizeSectionOrder($postedOrder, $availableSectionKeys);
            $databaseConfig->set(self::HOMEPAGE_CONFIG_KEY, json_encode($normalizedOrder));

            for ($contentIndex = 1; $contentIndex <= self::HOMEPAGE_CONTENT_COUNT; $contentIndex++) {
                $databaseConfig->set(
                    self::HOMEPAGE_CONTENT_PREFIX . $contentIndex,
                    (string) $this->getRequest()->getPost('homepageCustomContent' . $contentIndex)
                );
                $databaseConfig->set(
                    self::HOMEPAGE_CONTENT_WIDTH_PREFIX . $contentIndex,
                    $this->normalizeContentColumns((string) $this->getRequest()->getPost('homepageCustomContentWidth' . $contentIndex))
                );
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);

            return;
        }

        $savedOrder = (string) $databaseConfig->get(self::HOMEPAGE_CONFIG_KEY);
        $activeSectionKeys = $this->normalizeSectionOrder($savedOrder, $availableSectionKeys);
        $inactiveSectionKeys = array_values(array_diff($availableSectionKeys, $activeSectionKeys));

        $this->getView()->setArray([
            'activeSections' => array_map(
                static fn(string $key): array => ['key' => $key] + ($sectionMeta[$key] ?? []),
                $activeSectionKeys
            ),
            'inactiveSections' => array_map(
                static fn(string $key): array => ['key' => $key] + ($sectionMeta[$key] ?? []),
                $inactiveSectionKeys
            ),
            'frontendUrl' => $this->getLayout()->getBaseUrl('index.php/elegant/index'),
            'homepageSectionsJson' => json_encode($activeSectionKeys),
            'homepageCustomContents' => $this->getHomepageCustomContents(),
        ]);
    }

    /**
     * @param string $rawOrder
     * @param string[] $availableSectionKeys
     * @return string[]
     */
    private function normalizeSectionOrder(string $rawOrder, array $availableSectionKeys): array
    {
        $decoded = json_decode($rawOrder, true);

        if (!is_array($decoded)) {
            return $availableSectionKeys;
        }

        $normalized = [];
        foreach ($decoded as $sectionKey) {
            $sectionKey = (string) $sectionKey;

            if (in_array($sectionKey, $availableSectionKeys, true) && !in_array($sectionKey, $normalized, true)) {
                $normalized[] = $sectionKey;
            }
        }

        return $normalized === [] ? $availableSectionKeys : $normalized;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function getSectionMeta(): array
    {
        $meta = [
            'slider' => [
                'title' => $this->getTranslator()->trans('homepageSectionSlider'),
                'description' => $this->getTranslator()->trans('homepageSectionSliderText'),
            ],
            'intro' => [
                'title' => $this->getTranslator()->trans('homepageSectionIntro'),
                'description' => $this->getTranslator()->trans('homepageSectionIntroText'),
            ],
            'platformCards' => [
                'title' => $this->getTranslator()->trans('homepageSectionPlatformCards'),
                'description' => $this->getTranslator()->trans('homepageSectionPlatformCardsText'),
            ],
            'featureCards' => [
                'title' => $this->getTranslator()->trans('homepageSectionFeatureCards'),
                'description' => $this->getTranslator()->trans('homepageSectionFeatureCardsText'),
            ],
            'newsBox' => [
                'title' => $this->getTranslator()->trans('homepageSectionNewsBox'),
                'description' => $this->getTranslator()->trans('homepageSectionNewsBoxText'),
            ],
            'contactWidget' => [
                'title' => $this->getTranslator()->trans('homepageSectionContactWidget'),
                'description' => $this->getTranslator()->trans('homepageSectionContactWidgetText'),
            ],
            'videoWidget' => [
                'title' => $this->getTranslator()->trans('homepageSectionVideoWidget'),
                'description' => $this->getTranslator()->trans('homepageSectionVideoWidgetText'),
            ],
            'socialWidget' => [
                'title' => $this->getTranslator()->trans('homepageSectionSocialWidget'),
                'description' => $this->getTranslator()->trans('homepageSectionSocialWidgetText'),
            ],
        ];

        for ($contentIndex = 1; $contentIndex <= self::HOMEPAGE_CONTENT_COUNT; $contentIndex++) {
            $meta['customContent' . $contentIndex] = [
                'title' => $this->getTranslator()->trans('homepageSectionCustomContent', $contentIndex),
                'description' => $this->getTranslator()->trans('homepageSectionCustomContentText', $contentIndex),
            ];
        }

        return $meta;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function getHomepageCustomContents(): array
    {
        $contents = [];

        for ($contentIndex = 1; $contentIndex <= self::HOMEPAGE_CONTENT_COUNT; $contentIndex++) {
            $contents[] = [
                'index' => (string) $contentIndex,
                'title' => $this->getTranslator()->trans('homepageCustomContent', $contentIndex),
                'hint' => $this->getTranslator()->trans('homepageCustomContentHint', $contentIndex),
                'value' => (string) $this->getConfig()->get(self::HOMEPAGE_CONTENT_PREFIX . $contentIndex),
                'width' => $this->normalizeContentColumns((string) $this->getConfig()->get(self::HOMEPAGE_CONTENT_WIDTH_PREFIX . $contentIndex)),
                'widthOptions' => $this->getContentWidthOptions(),
            ];
        }

        return $contents;
    }

    private function normalizeContentColumns(string $value): string
    {
        return in_array($value, ['1', '2', '3', '4'], true) ? $value : '1';
    }

    /**
     * @return array<string, string>
     */
    private function getContentWidthOptions(): array
    {
        return [
            '1' => $this->getTranslator()->trans('homepageContentWidth1'),
            '2' => $this->getTranslator()->trans('homepageContentWidth2'),
            '3' => $this->getTranslator()->trans('homepageContentWidth3'),
            '4' => $this->getTranslator()->trans('homepageContentWidth4'),
        ];
    }
}
