<?php

/** @var \Ilch\View $this */

$imageMapper = new \Modules\Gallery\Mappers\Image();

/** @var \Modules\Gallery\Models\Image|null $currentImage */
$currentImage = $this->get('image');

$images = [];

if ($currentImage && $currentImage->getGalleryId() > 0) {
    $galleryImages = $imageMapper->getImageByGalleryId($currentImage->getGalleryId());
    if (!empty($galleryImages)) {
        $images = array_slice($galleryImages, 0, 4);
    }
}

if (empty($images) && $currentImage) {
    $images[] = $currentImage;
}

if (!empty($images) && count($images) < 4) {
    $firstImage = $images[0];
    while (count($images) < 4) {
        $images[] = $firstImage;
    }
}
?>

<?php if (!empty($images)) : ?>
    <div class="game-pictureofx-grid">
        <?php foreach ($images as $image) : ?>
            <?php
            $url = $this->getUrl(['module' => 'gallery', 'controller' => 'index', 'action' => 'showimage', 'id' => $image->getId()]);
            $altText = empty($image->getImageTitle()) ? basename((string) $image->getImageUrl()) : $image->getImageTitle();
            $thumbExists = (string) $image->getImageThumb() !== '' && file_exists($image->getImageThumb());
            ?>
            <article class="game-pictureofx-item">
                <a class="game-pictureofx-media<?=$thumbExists ? '' : ' game-art-k' ?>" href="<?=$url ?>">
                    <?php if ($thumbExists) : ?>
                        <img src="<?=$this->getUrl() . '/' . $image->getImageThumb() ?>" alt="<?=$this->escape($altText) ?>">
                    <?php endif; ?>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p class="game-vote-empty"><?=$this->getTrans('noPictures') ?></p>
<?php endif; ?>
