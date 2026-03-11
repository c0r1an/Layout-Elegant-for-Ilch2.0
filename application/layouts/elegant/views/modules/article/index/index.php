<?php
/** @var \Ilch\View $this */
$articles = $this->get('articles');
$categoryMapper = $this->get('categoryMapper');
$commentMapper = $this->get('commentMapper');
?>
<section class="game-article-shell">
    <header class="game-article-hero">
        <h1><?=$this->getTrans('menuArticle') ?></h1>
    </header>

    <?php if ($articles != ''): ?>
        <div class="game-article-list">
            <?php foreach ($articles as $article): ?>
                <?php
                $date = new \Ilch\Date($article->getDateCreated());
                $commentsCount = $commentMapper->getCountComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()));
                $image = $article->getImage();
                $imageSource = $article->getImageSource();
                $content = $article->getContent();
                $previewContent = $content;
                $hasPreviewStop = strpos($content, '[PREVIEWSTOP]') !== false;

                if ($hasPreviewStop) {
                    $contentParts = explode('[PREVIEWSTOP]', $content);
                    $previewContent = reset($contentParts);
                }

                $catIds = explode(',', $article->getCatId());
                $categories = [];

                foreach ($catIds as $catId) {
                    if (!$catId) {
                        continue;
                    }

                    $articlesCats = $categoryMapper->getCategoryById($catId);

                    if ($articlesCats) {
                        $categories[] = '<a href="' . $this->getUrl(['controller' => 'cats', 'action' => 'show', 'id' => $catId]) . '">' . $this->escape($articlesCats->getName()) . '</a>';
                    }
                }

                $votes = explode(',', $article->getVotes());
                $countOfVotes = max(0, count($votes) - 1);
                ?>
                <article class="game-article-card">
                    <?php if (!empty($image)): ?>
                        <figure class="game-article-media">
                            <img class="article_image" src="<?=$this->getBaseUrl($image) ?>" alt="<?=$this->escape($article->getTitle()) ?>">
                            <?php if (!empty($imageSource)): ?>
                                <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$this->escape($imageSource) ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <div class="game-article-card-body">
                        <?php if ($article->getTeaser()): ?>
                            <p class="game-article-teaser"><?=$this->escape($article->getTeaser()) ?></p>
                        <?php endif; ?>

                        <h2><a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->escape($article->getTitle()) ?></a></h2>

                        <div class="game-article-copy ck-content"><?=$this->purify($previewContent) ?></div>

                        <?php if ($hasPreviewStop): ?>
                            <a class="game-article-readmore" href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId()]) ?>"><?=$this->getTrans('readMore') ?></a>
                        <?php endif; ?>

                        <div class="game-article-meta">
                            <?php if ($article->getAuthorId() != '' && $article->getAuthorName() != ''): ?>
                                <span><i class="fa-solid fa-user"></i> <a href="<?=$this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $article->getAuthorId()]) ?>"><?=$this->escape($article->getAuthorName()) ?></a></span>
                            <?php endif; ?>
                            <span><i class="fa-solid fa-calendar"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a></span>
                            <span><i class="fa-regular fa-clock"></i> <?=$date->format('H:i', true) ?></span>
                            <?php if (!empty($categories)): ?>
                                <span><i class="fa-regular fa-folder-open"></i> <?=implode(', ', $categories) ?></span>
                            <?php endif; ?>
                            <span><i class="fa-regular fa-comment"></i> <a href="<?=$this->getUrl(['action' => 'show', 'id' => $article->getId() . '#comment']) ?>"><?=$commentsCount ?></a></span>
                            <span><i class="fa-regular fa-eye"></i> <?=$article->getVisits() ?></span>
                            <?php if ($article->getTopArticle()) : ?>
                                <span><i class="fa-regular fa-star"></i> <?=$this->getTrans('topArticle') ?></span>
                            <?php endif; ?>
                            <?php if ($this->get('article_articleRating')) : ?>
                                <span><i class="fa-solid fa-thumbs-up"></i> <?=$countOfVotes ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($article->getKeywords() != ''): ?>
                            <?php
                            $keywordsListArray = explode(', ', $article->getKeywords());
                            $keywordsList = [];

                            foreach ($keywordsListArray as $keyword) {
                                $keywordsList[] = '<a href="' . $this->getUrl(['controller' => 'keywords', 'action' => 'show', 'keyword' => urlencode($keyword)]) . '">' . $this->escape($keyword) . '</a>';
                            }
                            ?>
                            <div class="game-article-tags"><i class="fa-solid fa-hashtag"></i> <?=implode(', ', $keywordsList) ?></div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="game-article-pagination">
            <?=$this->get('pagination')->getHtml($this, ['action' => 'index']) ?>
        </div>
    <?php else: ?>
        <div class="game-article-empty"><?=$this->getTrans('noArticles') ?></div>
    <?php endif; ?>
</section>
