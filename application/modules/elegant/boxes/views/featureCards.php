<?php
/** @var \Ilch\View $this */
$cards = $this->get('cards');

if (empty($cards)) {
    return;
}
?>
<section class="elegant-portfolio">
    <div class="elegant-container">
        <h2><em><?=$this->escape($this->get('title')) ?></em></h2>
        <div class="elegant-portfolio-grid">
            <?php foreach ($cards as $card): ?>
                <article class="elegant-portfolio-card">
                    <a href="<?=$card['url'] !== '' ? $this->escape($card['url']) : '#' ?>">
                        <div class="elegant-portfolio-media<?=$card['image'] === '' ? ' ' . $this->escape($card['fallbackClass']) : '' ?>">
                            <?php if ($card['image'] !== ''): ?>
                                <img src="<?=$this->escape($card['image']) ?>" alt="<?=$this->escape($card['title']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="elegant-portfolio-copy">
                            <?php if ($card['title'] !== ''): ?>
                                <h3><?=$this->escape($card['title']) ?></h3>
                            <?php endif; ?>
                            <?php if ($card['tag'] !== ''): ?>
                                <p><?=$this->escape($card['tag']) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
