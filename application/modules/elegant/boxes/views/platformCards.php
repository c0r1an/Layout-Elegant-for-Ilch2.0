<?php
/** @var \Ilch\View $this */
$cards = $this->get('cards');

if (empty($cards)) {
    return;
}
?>
<section class="elegant-services">
    <div class="elegant-container">
        <div class="elegant-feature-grid">
            <?php foreach ($cards as $card): ?>
                <article class="elegant-feature-card">
                    <div class="elegant-feature-icon">
                        <i class="<?=$this->escape($card['icon'] !== '' ? $card['icon'] : 'fa-solid fa-star') ?>"></i>
                    </div>
                    <?php if ($card['title'] !== ''): ?>
                        <h3><?=$this->escape($card['title']) ?></h3>
                    <?php endif; ?>
                    <?php if ($card['text'] !== ''): ?>
                        <p><?=$this->escape($card['text']) ?></p>
                    <?php endif; ?>
                    <?php if ($card['url'] !== ''): ?>
                        <a class="elegant-text-link" href="<?=$this->escape($card['url']) ?>">Open</a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
