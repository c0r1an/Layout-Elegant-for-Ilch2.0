<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled')) {
    return;
}

$items = $this->get('items');
?>
<section class="elegant-widget">
    <h3><?=$this->escape($this->get('title')) ?></h3>
    <?php if (!empty($items)): ?>
        <div class="elegant-social-grid">
            <?php foreach ($items as $item): ?>
                <a href="<?=$item['url'] !== '' ? $this->escape($item['url']) : '#' ?>"<?=($item['url'] !== '' ? ' target="_blank" rel="noopener"' : '') ?>>
                    <i class="<?=$this->escape($item['icon']) ?>"></i>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="elegant-widget-empty">Add social links in the Elegant* settings to populate this area.</p>
    <?php endif; ?>
</section>
