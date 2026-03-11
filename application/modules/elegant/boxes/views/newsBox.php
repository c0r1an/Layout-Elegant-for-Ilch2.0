<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled') || $this->get('content') === '') {
    return;
}
?>
<section class="elegant-story-band">
    <div class="elegant-container">
        <h2><em><?=$this->escape($this->get('title')) ?></em></h2>
        <?=$this->get('content') ?>
    </div>
</section>
