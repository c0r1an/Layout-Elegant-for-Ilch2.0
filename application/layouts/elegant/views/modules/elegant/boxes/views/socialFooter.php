<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled')) {
    return;
}

$items = $this->get('items');

if (empty($items)) {
    return;
}
?>
<div class="elegant-social-grid">
    <?php foreach ($items as $item): ?>
        <a href="<?=$item['url'] !== '' ? $this->escape($item['url']) : '#' ?>"<?=($item['url'] !== '' ? ' target="_blank" rel="noopener"' : '') ?>>
            <i class="<?=$this->escape($item['icon']) ?>"></i>
        </a>
    <?php endforeach; ?>
</div>
