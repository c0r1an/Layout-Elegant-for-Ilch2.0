<?php

namespace Modules\Elegant\Config;

use Ilch\Config\Database;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'elegant',
        'version' => '1.0.0',
        'icon_small' => 'fa-regular fa-gem',
        'author' => 'c0r1an',
        'link' => 'https://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Elegant*',
                'description' => 'Begleitmodul zum Layout Elegant* mit Settings, Homepage-Builder und eigenen Layout-Boxen.',
            ],
            'en_EN' => [
                'name' => 'Elegant*',
                'description' => 'Companion module for the Elegant* layout with settings, homepage builder, and dedicated layout boxes.',
            ],
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3',
    ];

    public function __construct(?\Ilch\Translator $translator = null)
    {
        parent::__construct($translator);
        $this->config['layoutSettings'] = $this->buildLayoutSettings();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getLayoutSettings(): array
    {
        return $this->config['layoutSettings'];
    }

    /**
     * @return string[]
     */
    public function getHomepageSectionKeys(): array
    {
        return [
            'slider',
            'intro',
            'platformCards',
            'featureCards',
            'newsBox',
            'contactWidget',
            'videoWidget',
            'socialWidget',
            'customContent1',
            'customContent2',
            'customContent3',
            'customContent4',
            'customContent5',
            'customContent6',
        ];
    }

    public function install()
    {
        $databaseConfig = new Database($this->db());
        $databaseConfig
            ->set('elegant_eyebrow', 'Elegant*')
            ->set('elegant_headline', 'Modul und Layout arbeiten zusammen')
            ->set('elegant_text', 'Diese Seite ist ein einfacher Platzhalter fuer das begleitende Elegant*-Modul. Hier kann spaeter eine Landingpage, ein Intro oder eine individuelle Startseite entstehen.')
            ->set('elegant_button_label', 'Zur Startseite')
            ->set('elegant_button_url', '')
            ->set('elegant_homepage_customcontent_1', '')
            ->set('elegant_homepage_customcontent_2', '')
            ->set('elegant_homepage_customcontent_3', '')
            ->set('elegant_homepage_customcontent_4', '')
            ->set('elegant_homepage_customcontent_5', '')
            ->set('elegant_homepage_customcontent_6', '')
            ->set('elegant_homepage_customcontent_width_1', '1')
            ->set('elegant_homepage_customcontent_width_2', '1')
            ->set('elegant_homepage_customcontent_width_3', '1')
            ->set('elegant_homepage_customcontent_width_4', '1')
            ->set('elegant_homepage_customcontent_width_5', '1')
            ->set('elegant_homepage_customcontent_width_6', '1')
            ->set('elegant_homepage_sections', json_encode($this->getHomepageSectionKeys()));
    }

    public function uninstall()
    {
        $databaseConfig = new Database($this->db());
        $databaseConfig->delete([
            'elegant_eyebrow',
            'elegant_headline',
            'elegant_text',
            'elegant_button_label',
            'elegant_button_url',
            'elegant_homepage_customcontent_1',
            'elegant_homepage_customcontent_2',
            'elegant_homepage_customcontent_3',
            'elegant_homepage_customcontent_4',
            'elegant_homepage_customcontent_5',
            'elegant_homepage_customcontent_6',
            'elegant_homepage_customcontent_width_1',
            'elegant_homepage_customcontent_width_2',
            'elegant_homepage_customcontent_width_3',
            'elegant_homepage_customcontent_width_4',
            'elegant_homepage_customcontent_width_5',
            'elegant_homepage_customcontent_width_6',
            'elegant_homepage_sections',
        ]);
    }

    public function getUpdate(string $installedVersion): string
    {
        return '"' . $this->config['key'] . '" update executed.';
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function buildLayoutSettings(): array
    {
        $platformIconOptions = $this->getPlatformIconOptions();
        $socialIconOptions = $this->getSocialIconOptions();
        $videoSourceOptions = $this->getVideoSourceOptions();
        $visibilityOptions = $this->getCardVisibilityOptions();

        return [
            'generalSection' => [
                'type' => 'separator',
            ],
            'siteName' => [
                'type' => 'text',
                'default' => 'Elegant*',
                'description' => '',
            ],
            'siteTagline' => [
                'type' => 'text',
                'default' => 'A curated stage for your community',
                'description' => '',
            ],
            'footerCopyright' => [
                'type' => 'text',
                'default' => 'Copyright {year} Elegant*',
                'description' => '',
            ],
            'siteLogo' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'pageHeroBackgroundImage' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'headerMainSticky' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'showRootMenuItems' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'accentColor' => [
                'type' => 'colorpicker',
                'default' => '#c7a15d',
                'description' => '',
            ],
            'accentSoftColor' => [
                'type' => 'colorpicker',
                'default' => '#f1dec0',
                'description' => '',
            ],
            'sidebarBoxes' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'sidebarBoxesHome' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'contentMaxWidth' => [
                'type' => 'text',
                'default' => '1480px',
                'description' => '',
            ],
            'introSection' => [
                'type' => 'separator',
            ],
            'introEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'introVisibility' => [
                'type' => 'select',
                'default' => 'home',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'introTitle' => [
                'type' => 'text',
                'default' => '',
                'description' => '',
            ],
            'introText' => [
                'type' => 'textarea',
                'default' => '',
                'description' => '',
            ],
            'sliderSection' => [
                'type' => 'separator',
            ],
            'sliderEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'sliderVisibility' => [
                'type' => 'select',
                'default' => 'home',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'sliderAutoplay' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'sliderInterval' => [
                'type' => 'text',
                'default' => '5000',
                'description' => '',
            ],
            'sliderTag1' => [
                'type' => 'text',
                'default' => "Editor's Choice",
                'description' => '',
            ],
            'sliderTitle1' => [
                'type' => 'text',
                'default' => 'Build a refined home for your community stories',
                'description' => '',
            ],
            'sliderText1' => [
                'type' => 'textarea',
                'default' => 'Elegant* keeps the full Game feature set but presents content in a calmer, premium editorial look.',
                'description' => '',
            ],
            'sliderButtonLabel1' => [
                'type' => 'text',
                'default' => 'Discover more',
                'description' => '',
            ],
            'sliderButtonUrl1' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'sliderLeftImage1' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'sliderCenterImage1' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'sliderTag2' => [
                'type' => 'text',
                'default' => 'Feature Story',
                'description' => '',
            ],
            'sliderTitle2' => [
                'type' => 'text',
                'default' => 'Give announcements a quieter, sharper stage',
                'description' => '',
            ],
            'sliderText2' => [
                'type' => 'textarea',
                'default' => 'Use this slide for sponsor news, team updates or the next important release.',
                'description' => '',
            ],
            'sliderButtonLabel2' => [
                'type' => 'text',
                'default' => 'Open feature',
                'description' => '',
            ],
            'sliderButtonUrl2' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'sliderLeftImage2' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'sliderCenterImage2' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'sliderTag3' => [
                'type' => 'text',
                'default' => 'Spotlight',
                'description' => '',
            ],
            'sliderTitle3' => [
                'type' => 'text',
                'default' => 'Present one message with more atmosphere',
                'description' => '',
            ],
            'sliderText3' => [
                'type' => 'textarea',
                'default' => 'The third slide is ideal for event trailers, premium posts or campaign highlights.',
                'description' => '',
            ],
            'sliderButtonLabel3' => [
                'type' => 'text',
                'default' => 'Read article',
                'description' => '',
            ],
            'sliderButtonUrl3' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'sliderLeftImage3' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'sliderCenterImage3' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'platformSection' => [
                'type' => 'separator',
            ],
            'platformCardsEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'platformCardsVisibility' => [
                'type' => 'select',
                'default' => 'home',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'platformIcon1' => [
                'type' => 'text',
                'default' => 'fa-solid fa-desktop',
                'description' => '',
                'options' => $platformIconOptions,
            ],
            'platformTitle1' => [
                'type' => 'text',
                'default' => 'PC',
                'description' => '',
            ],
            'platformText1' => [
                'type' => 'text',
                'default' => 'Explore section',
                'description' => '',
            ],
            'platformUrl1' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'platformIcon2' => [
                'type' => 'text',
                'default' => 'fa-brands fa-playstation',
                'description' => '',
                'options' => $platformIconOptions,
            ],
            'platformTitle2' => [
                'type' => 'text',
                'default' => 'PS5',
                'description' => '',
            ],
            'platformText2' => [
                'type' => 'text',
                'default' => 'Explore section',
                'description' => '',
            ],
            'platformUrl2' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'platformIcon3' => [
                'type' => 'text',
                'default' => 'fa-brands fa-xbox',
                'description' => '',
                'options' => $platformIconOptions,
            ],
            'platformTitle3' => [
                'type' => 'text',
                'default' => 'Xbox',
                'description' => '',
            ],
            'platformText3' => [
                'type' => 'text',
                'default' => 'Explore section',
                'description' => '',
            ],
            'platformUrl3' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'cardRowSection' => [
                'type' => 'separator',
            ],
            'cardRowEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'cardRowVisibility' => [
                'type' => 'select',
                'default' => 'home',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'card1Enabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'card1Tag' => [
                'type' => 'text',
                'default' => 'New',
                'description' => '',
            ],
            'card1Title' => [
                'type' => 'text',
                'default' => 'Editorial card for your first highlight',
                'description' => '',
            ],
            'card1Text' => [
                'type' => 'textarea',
                'default' => 'Use concise copy to guide visitors towards your most relevant content.',
                'description' => '',
            ],
            'card1Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'card1Image' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'card2Enabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'card2Tag' => [
                'type' => 'text',
                'default' => 'Feature',
                'description' => '',
            ],
            'card2Title' => [
                'type' => 'text',
                'default' => 'A second card for a polished announcement',
                'description' => '',
            ],
            'card2Text' => [
                'type' => 'textarea',
                'default' => 'Pair a strong title with a short description and optional image.',
                'description' => '',
            ],
            'card2Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'card2Image' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'card3Enabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'card3Tag' => [
                'type' => 'text',
                'default' => 'Update',
                'description' => '',
            ],
            'card3Title' => [
                'type' => 'text',
                'default' => 'Important news, releases and changelogs',
                'description' => '',
            ],
            'card3Text' => [
                'type' => 'textarea',
                'default' => 'This slot works well for changelogs, patch notes or staff updates.',
                'description' => '',
            ],
            'card3Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'card3Image' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'card4Enabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'card4Tag' => [
                'type' => 'text',
                'default' => 'Action',
                'description' => '',
            ],
            'card4Title' => [
                'type' => 'text',
                'default' => 'Reserve one slot for campaigns and events',
                'description' => '',
            ],
            'card4Text' => [
                'type' => 'textarea',
                'default' => 'Use this space for registrations, cup dates or community campaigns.',
                'description' => '',
            ],
            'card4Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'card4Image' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'newsBoxSection' => [
                'type' => 'separator',
            ],
            'newsBoxEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'newsBoxVisibility' => [
                'type' => 'select',
                'default' => 'home',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'newsBoxTitle' => [
                'type' => 'text',
                'default' => 'Latest News',
                'description' => '',
            ],
            'videoWidgetSection' => [
                'type' => 'separator',
            ],
            'videoWidgetEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'videoWidgetVisibility' => [
                'type' => 'select',
                'default' => 'all',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'latestVideoTitle' => [
                'type' => 'text',
                'default' => 'Featured Reel',
                'description' => '',
            ],
            'latestVideoSource' => [
                'type' => 'select',
                'default' => 'youtube',
                'description' => 'latestVideoSourceHint',
                'options' => $videoSourceOptions,
            ],
            'latestVideoUrl' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'latestVideoFile' => [
                'type' => 'mediaselection',
                'default' => '',
                'description' => '',
            ],
            'latestVideoAutoplay' => [
                'type' => 'flipswitch',
                'default' => '0',
                'description' => '',
            ],
            'latestVideoMuted' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'socialWidgetSection' => [
                'type' => 'separator',
            ],
            'socialWidgetEnabled' => [
                'type' => 'flipswitch',
                'default' => '1',
                'description' => '',
            ],
            'socialWidgetVisibility' => [
                'type' => 'select',
                'default' => 'all',
                'description' => 'boxVisibilityHint',
                'options' => $visibilityOptions,
            ],
            'socialWidgetTitle' => [
                'type' => 'text',
                'default' => 'House Links',
                'description' => '',
            ],
            'socialItem1Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-facebook-f',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem1Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'socialItem2Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-x-twitter',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem2Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'socialItem3Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-instagram',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem3Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'socialItem4Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-youtube',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem4Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'socialItem5Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-twitch',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem5Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'socialItem6Icon' => [
                'type' => 'iconpicker',
                'default' => 'fa-brands fa-discord',
                'description' => '',
                'options' => $socialIconOptions,
            ],
            'socialItem6Url' => [
                'type' => 'url',
                'default' => '',
                'description' => '',
            ],
            'footerSection' => [
                'type' => 'separator',
            ],
            'footerBlockThreeHtml' => [
                'type' => 'ckeditorhtml',
                'default' => '<h3>Companion Module</h3><p>Use the attached Elegant* module for a dedicated landing page and direct admin access from the layout overview.</p><a class="elegant-text-link" href="index.php/elegant/index/index">Open module page</a>',
                'description' => '',
            ],
            'footerBlockFourHtml' => [
                'type' => 'ckeditorhtml',
                'default' => '<h3>Layout Boxes</h3><p>Slider, platform cards, feature cards, social widget and video widget are now rendered as dedicated Elegant* boxes.</p>',
                'description' => '',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getPlatformIconOptions(): array
    {
        return [
            'fa-solid fa-desktop' => 'Desktop',
            'fa-solid fa-gamepad' => 'Gamepad',
            'fa-solid fa-computer' => 'Computer',
            'fa-solid fa-mobile-screen' => 'Mobile',
            'fa-solid fa-tv' => 'TV',
            'fa-solid fa-headset' => 'Headset',
            'fa-solid fa-rocket' => 'Rocket',
            'fa-solid fa-trophy' => 'Trophy',
            'fa-solid fa-shield-halved' => 'Shield',
            'fa-solid fa-joystick' => 'Joystick',
            'fa-brands fa-playstation' => 'PlayStation',
            'fa-brands fa-xbox' => 'Xbox',
            'fa-brands fa-steam' => 'Steam',
            'fa-brands fa-discord' => 'Discord',
            'fa-brands fa-twitch' => 'Twitch',
            'fa-brands fa-youtube' => 'YouTube',
            'fa-brands fa-windows' => 'Windows',
            'fa-brands fa-apple' => 'Apple',
            'fa-brands fa-linux' => 'Linux',
            'fa-brands fa-android' => 'Android',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getSocialIconOptions(): array
    {
        return [
            'fa-brands fa-facebook-f' => 'Facebook',
            'fa-brands fa-x-twitter' => 'X',
            'fa-brands fa-instagram' => 'Instagram',
            'fa-brands fa-youtube' => 'YouTube',
            'fa-brands fa-twitch' => 'Twitch',
            'fa-brands fa-discord' => 'Discord',
            'fa-brands fa-tiktok' => 'TikTok',
            'fa-brands fa-linkedin-in' => 'LinkedIn',
            'fa-brands fa-github' => 'GitHub',
            'fa-brands fa-steam' => 'Steam',
            'fa-brands fa-reddit-alien' => 'Reddit',
            'fa-solid fa-globe' => 'Website',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getVideoSourceOptions(): array
    {
        return [
            'youtube' => 'YouTube',
            'vimeo' => 'Vimeo',
            'mp4' => 'MP4 / Datei',
            'embed' => 'Embed URL',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getCardVisibilityOptions(): array
    {
        return [
            'home' => 'Nur Startseite',
            'all' => 'Alle Seiten',
        ];
    }
}
