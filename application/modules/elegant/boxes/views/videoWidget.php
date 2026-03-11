<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled')) {
    return;
}
?>
<section class="elegant-widget">
    <h3><?=$this->escape($this->get('title')) ?></h3>
    <?php if ($this->get('type') === 'iframe' && $this->get('src') !== ''): ?>
        <div class="elegant-video-embed">
            <iframe
                src="<?=$this->escape($this->get('src')) ?>"
                title="<?=$this->escape($this->get('title')) ?>"
                loading="lazy"
                allow="autoplay; encrypted-media; picture-in-picture; web-share"
                allowfullscreen
            ></iframe>
        </div>
    <?php elseif ($this->get('type') === 'video' && $this->get('src') !== ''): ?>
        <div class="elegant-video-embed">
            <video controls preload="metadata" playsinline<?=$this->get('autoplay') ? ' autoplay' : '' ?><?=$this->get('muted') ? ' muted' : '' ?>>
                <source src="<?=$this->escape($this->get('src')) ?>" type="video/mp4">
            </video>
        </div>
    <?php else: ?>
        <div class="elegant-video-placeholder game-art-t">
            <span>Video placeholder</span>
        </div>
    <?php endif; ?>
</section>
