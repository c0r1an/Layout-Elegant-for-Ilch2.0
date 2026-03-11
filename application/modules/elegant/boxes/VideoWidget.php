<?php

namespace Modules\Elegant\Boxes;

class VideoWidget extends BaseBox
{
    public function render()
    {
        if (!$this->shouldRenderHomepageSection('videoWidgetEnabled', 'videoWidgetVisibility', 'all')) {
            $this->getView()->setArray([
                'enabled' => false,
                'title' => '',
                'type' => 'placeholder',
                'src' => '',
                'autoplay' => false,
                'muted' => true,
            ]);
            return;
        }

        $title = $this->stringSetting('latestVideoTitle');
        $source = $this->stringSetting('latestVideoSource');
        $url = $this->stringSetting('latestVideoUrl');
        $file = $this->stringSetting('latestVideoFile');
        $autoplay = $this->boolSetting('latestVideoAutoplay');
        $muted = $this->boolSetting('latestVideoMuted');
        $type = 'placeholder';
        $src = '';

        $extractYouTubeId = static function (string $videoUrl): string {
            $parts = parse_url($videoUrl);
            if (!$parts) {
                return '';
            }

            $host = strtolower((string) ($parts['host'] ?? ''));
            $path = trim((string) ($parts['path'] ?? ''), '/');

            if ($host === 'youtu.be') {
                return $path;
            }

            if (strpos($host, 'youtube.com') !== false) {
                if ($path === 'watch') {
                    parse_str((string) ($parts['query'] ?? ''), $query);
                    return (string) ($query['v'] ?? '');
                }

                if (strpos($path, 'embed/') === 0) {
                    return substr($path, 6);
                }

                if (strpos($path, 'shorts/') === 0) {
                    return substr($path, 7);
                }
            }

            return '';
        };

        $extractVimeoId = static function (string $videoUrl): string {
            $parts = parse_url($videoUrl);
            if (!$parts) {
                return '';
            }

            $host = strtolower((string) ($parts['host'] ?? ''));
            if (strpos($host, 'vimeo.com') === false) {
                return '';
            }

            $path = trim((string) ($parts['path'] ?? ''), '/');
            if ($path === '') {
                return '';
            }

            $segments = explode('/', $path);
            $lastSegment = end($segments);

            return preg_match('/^\d+$/', (string) $lastSegment) ? (string) $lastSegment : '';
        };

        switch ($source) {
            case 'youtube':
                $youtubeId = $extractYouTubeId($url);
                if ($youtubeId !== '') {
                    $type = 'iframe';
                    $src = 'https://www.youtube.com/embed/' . rawurlencode($youtubeId)
                        . '?rel=0&modestbranding=1&autoplay=' . ($autoplay ? '1' : '0')
                        . '&mute=' . ($muted ? '1' : '0');
                }
                break;

            case 'vimeo':
                $vimeoId = $extractVimeoId($url);
                if ($vimeoId !== '') {
                    $type = 'iframe';
                    $src = 'https://player.vimeo.com/video/' . rawurlencode($vimeoId)
                        . '?autoplay=' . ($autoplay ? '1' : '0')
                        . '&muted=' . ($muted ? '1' : '0');
                }
                break;

            case 'mp4':
                if ($file !== '') {
                    $type = 'video';
                    $src = $this->assetUrl($file);
                } elseif ($url !== '') {
                    $type = 'video';
                    $src = $url;
                }
                break;

            case 'embed':
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $type = 'iframe';
                    $src = $url;
                }
                break;
        }

        $this->getView()->setArray([
            'enabled' => true,
            'title' => $title !== '' ? $title : 'Featured Reel',
            'type' => $type,
            'src' => $src,
            'autoplay' => $autoplay,
            'muted' => $muted,
        ]);
    }
}
