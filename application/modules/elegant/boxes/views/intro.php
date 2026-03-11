<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled')) {
    return;
}
?>
<section class="elegant-intro">
    <div class="elegant-container">
        <div class="elegant-intro-copy">
            <h2><?=$this->escape($this->get('title')) ?></h2>
            <p><?=$this->escape($this->get('text')) ?></p>
        </div>
    </div>
</section>
