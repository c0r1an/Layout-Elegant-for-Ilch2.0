<?php
/** @var \Ilch\View $this */
$articles = $this->get('articles');
$boxId = 'gameNewsBox' . substr(md5((string) microtime(true) . random_int(0, PHP_INT_MAX)), 0, 8);
$newsItems = [];

foreach (($articles ?? []) as $article) {
    $date = new \Ilch\Date($article->getDateCreated());
    $image = trim((string) $article->getImage());
    $imageThumb = trim((string) $article->getImageThumb());
    $imageSource = trim((string) $article->getImageSource());
    $displayImage = '';

    if ($image !== '') {
        $displayImage = $this->getBaseUrl($image);
    } elseif ($imageThumb !== '') {
        $displayImage = $this->getBaseUrl($imageThumb);
    } elseif ($imageSource !== '') {
        $isImagePath = preg_match('/\.(jpe?g|png|gif|webp|avif|svg)(\?.*)?$/i', $imageSource) === 1;
        $isAbsoluteUrl = preg_match('#^https?://#i', $imageSource) === 1;
        $isRelativePath = str_starts_with($imageSource, 'application/') || str_starts_with($imageSource, 'uploads/') || str_starts_with($imageSource, '/');

        if ($isImagePath || $isAbsoluteUrl || $isRelativePath) {
            $displayImage = $isAbsoluteUrl ? $imageSource : $this->getBaseUrl($imageSource);
        }
    }

    $teaser = trim((string) $article->getTeaser());
    $contentPreview = $teaser;

    if ($contentPreview === '') {
        $rawContent = (string) $article->getContent();
        $rawContent = str_replace('[PREVIEWSTOP]', ' ', $rawContent);
        $contentPreview = trim(strip_tags($rawContent));
    }

    if (mb_strlen($contentPreview) > 120) {
        $contentPreview = mb_substr($contentPreview, 0, 117) . '...';
    }

    $newsItems[] = [
        'title' => (string) $article->getTitle(),
        'excerpt' => $contentPreview,
        'date' => $date->format('F j, Y', true),
        'url' => $this->getUrl(['module' => 'article', 'controller' => 'index', 'action' => 'show', 'id' => $article->getId()]),
        'image' => $displayImage,
    ];
}
?>

<?php if (!empty($newsItems)): ?>
    <?php $previewItem = $newsItems[0]; ?>
    <section id="<?=$boxId ?>" class="game-newsbox-wrap">
        <div class="game-newsbox">
        <div class="game-newsbox-list">
            <?php foreach ($newsItems as $index => $item): ?>
                <button
                    type="button"
                    class="game-newsbox-item<?=$index === 0 ? ' is-active' : '' ?>"
                    data-newsbox-item
                    data-title="<?=$this->escape($item['title']) ?>"
                    data-excerpt="<?=$this->escape($item['excerpt']) ?>"
                    data-date="<?=$this->escape($item['date']) ?>"
                    data-url="<?=$this->escape($item['url']) ?>"
                    data-image="<?=$this->escape($item['image']) ?>"
                >
                    <span class="game-newsbox-item-thumb<?=$item['image'] === '' ? ' game-art-k' : '' ?>">
                        <?php if ($item['image'] !== ''): ?><img src="<?=$this->escape($item['image']) ?>" alt="<?=$this->escape($item['title']) ?>"><?php endif; ?>
                    </span>
                    <span class="game-newsbox-item-content">
                        <strong><?=$this->escape($item['title']) ?></strong>
                        <em><i class="fa-regular fa-calendar"></i> <?=$this->escape($item['date']) ?></em>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>

        <article class="game-newsbox-preview">
            <a class="game-newsbox-preview-media<?=$previewItem['image'] === '' ? ' game-art-f' : '' ?>" data-newsbox-preview-link href="<?=$this->escape($previewItem['url']) ?>">
                <?php if ($previewItem['image'] !== ''): ?><img data-newsbox-preview-image src="<?=$this->escape($previewItem['image']) ?>" alt="<?=$this->escape($previewItem['title']) ?>"><?php endif; ?>
            </a>
            <div class="game-newsbox-preview-body">
                <h3><a data-newsbox-preview-link-title href="<?=$this->escape($previewItem['url']) ?>" data-newsbox-preview-title><?=$this->escape($previewItem['title']) ?></a></h3>
                <p data-newsbox-preview-excerpt><?=$this->escape($previewItem['excerpt']) ?></p>
                <div class="game-newsbox-preview-meta">
                    <a data-newsbox-preview-link-read href="<?=$this->escape($previewItem['url']) ?>">Read more</a>
                    <span><i class="fa-regular fa-calendar"></i> <span data-newsbox-preview-date><?=$this->escape($previewItem['date']) ?></span></span>
                </div>
            </div>
        </article>
        </div>
    </section>

    <script>
        (function () {
            var box = document.getElementById('<?=$boxId ?>');
            if (!box) {
                return;
            }

            var items = box.querySelectorAll('[data-newsbox-item]');
            var previewImage = box.querySelector('[data-newsbox-preview-image]');
            var previewMedia = box.querySelector('.game-newsbox-preview-media');
            var previewTitle = box.querySelector('[data-newsbox-preview-title]');
            var previewExcerpt = box.querySelector('[data-newsbox-preview-excerpt]');
            var previewDate = box.querySelector('[data-newsbox-preview-date]');
            var previewLink = box.querySelector('[data-newsbox-preview-link]');
            var previewLinkTitle = box.querySelector('[data-newsbox-preview-link-title]');
            var previewLinkRead = box.querySelector('[data-newsbox-preview-link-read]');

            var setPreview = function (item) {
                var title = item.getAttribute('data-title') || '';
                var excerpt = item.getAttribute('data-excerpt') || '';
                var date = item.getAttribute('data-date') || '';
                var url = item.getAttribute('data-url') || '#';
                var image = item.getAttribute('data-image') || '';

                previewTitle.textContent = title;
                previewExcerpt.textContent = excerpt;
                previewDate.textContent = date;
                previewLink.setAttribute('href', url);
                previewLinkTitle.setAttribute('href', url);
                previewLinkRead.setAttribute('href', url);

                if (image !== '') {
                    if (!previewImage) {
                        previewImage = document.createElement('img');
                        previewImage.setAttribute('data-newsbox-preview-image', '');
                        previewMedia.appendChild(previewImage);
                    }

                    previewImage.setAttribute('src', image);
                    previewImage.setAttribute('alt', title);
                    previewMedia.classList.remove('game-art-f');
                } else {
                    if (previewImage) {
                        previewImage.remove();
                        previewImage = null;
                    }

                    previewMedia.classList.add('game-art-f');
                }
            };

            items.forEach(function (item) {
                item.addEventListener('click', function () {
                    items.forEach(function (entry) {
                        entry.classList.remove('is-active');
                    });

                    item.classList.add('is-active');
                    setPreview(item);
                });
            });
        })();
    </script>
<?php else: ?>
    <div class="game-article-empty"><?=$this->getTrans('noArticles') ?></div>
<?php endif; ?>
