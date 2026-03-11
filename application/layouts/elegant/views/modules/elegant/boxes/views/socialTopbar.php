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
<ul class="elegant-social-menu">
    <?php foreach ($items as $item): ?>
        <li>
            <a href="<?=$item['url'] !== '' ? $this->escape($item['url']) : '#' ?>"<?=($item['url'] !== '' ? ' target="_blank" rel="noopener"' : '') ?>>
                <i class="<?=$this->escape($item['icon']) ?>"></i>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
