<?php
/** @var \Ilch\View $this */
$sliderItems = $this->get('sliderItems');

if (empty($sliderItems)) {
    return;
}
?>
<section class="elegant-hero">
    <div id="<?=$this->escape($this->get('sliderId')) ?>" class="carousel slide elegant-slider" data-bs-ride="<?=$this->get('sliderAutoplay') ? 'carousel' : 'false' ?>" data-bs-interval="<?=$this->get('sliderAutoplay') ? (int) $this->get('sliderInterval') : 'false' ?>">
        <?php if (count($sliderItems) > 1): ?>
            <div class="carousel-indicators elegant-slider-indicators">
                <?php foreach ($sliderItems as $index => $sliderItem): ?>
                    <button type="button" data-bs-target="#<?=$this->escape($this->get('sliderId')) ?>" data-bs-slide-to="<?=$index ?>" class="<?=$index === 0 ? 'active' : '' ?>" <?=$index === 0 ? 'aria-current="true"' : '' ?> aria-label="Slide <?=$index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner">
            <?php foreach ($sliderItems as $index => $sliderItem): ?>
                <div class="carousel-item<?=$index === 0 ? ' active' : '' ?>">
                    <article class="elegant-slide">
                        <div class="elegant-slide-media <?=$this->escape($sliderItem['fallbackClass']) ?>">
                            <?php if ($sliderItem['leftImage'] !== ''): ?>
                                <img class="elegant-slide-background" src="<?=$this->escape($sliderItem['leftImage']) ?>" alt="<?=$this->escape($sliderItem['title']) ?>">
                            <?php endif; ?>
                            <?php if ($sliderItem['centerImage'] !== ''): ?>
                                <img class="elegant-slide-foreground" src="<?=$this->escape($sliderItem['centerImage']) ?>" alt="<?=$this->escape($sliderItem['title']) ?>">
                            <?php endif; ?>
                        </div>

                        <div class="elegant-container elegant-slide-inner">
                            <div class="elegant-slide-copy">
                                <?php if ($sliderItem['tag'] !== ''): ?>
                                    <p class="elegant-slide-kicker"><?=$this->escape($sliderItem['tag']) ?></p>
                                <?php endif; ?>
                                <?php if ($sliderItem['title'] !== ''): ?>
                                    <h1><?=$this->escape($sliderItem['title']) ?></h1>
                                <?php endif; ?>
                                <?php if ($sliderItem['text'] !== ''): ?>
                                    <p class="elegant-slide-text"><?=$this->escape($sliderItem['text']) ?></p>
                                <?php endif; ?>
                                <?php if ($sliderItem['buttonLabel'] !== '' && $sliderItem['buttonUrl'] !== ''): ?>
                                    <a class="elegant-button" href="<?=$this->escape($sliderItem['buttonUrl']) ?>"><?=$this->escape($sliderItem['buttonLabel']) ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($sliderItems) > 1): ?>
            <button class="carousel-control-prev elegant-slider-arrow elegant-slider-arrow-prev" type="button" data-bs-target="#<?=$this->escape($this->get('sliderId')) ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next elegant-slider-arrow elegant-slider-arrow-next" type="button" data-bs-target="#<?=$this->escape($this->get('sliderId')) ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        <?php endif; ?>
    </div>
</section>
