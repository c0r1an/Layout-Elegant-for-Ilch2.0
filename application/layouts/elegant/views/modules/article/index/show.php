<?php if ($this->get('hasReadAccess')) : ?>
    <?php
    $article = $this->get('article');
    $categoryMapper = $this->get('categoryMapper');
    $userMapper = $this->get('userMapper');
    $content = str_replace('[PREVIEWSTOP]', '', $article->getContent());
    $preview = $this->getRequest()->getParam('preview');
    $config = $this->get('config');
    $date = new \Ilch\Date($article->getDateCreated() ?? '');

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

    $authorMarkup = '';

    if ($article->getAuthorId() != '') {
        $user = $userMapper->getUserById($article->getAuthorId());

        if ($user != '') {
            $authorMarkup = '<a href="' . $this->getUrl(['module' => 'user', 'controller' => 'profil', 'action' => 'index', 'user' => $user->getId()]) . '">' . $this->escape($user->getName()) . '</a>';
        }
    }

    $votes = explode(',', $article->getVotes());
    $countOfVotes = max(0, count($votes) - 1);
    ?>
    <article class="game-article-shell game-article-detail">
        <?php if ($preview): ?>
            <div class="article_preview"></div>
        <?php endif; ?>

        <header class="game-article-hero">
            <?php if ($article->getTeaser()): ?>
                <p class="game-article-teaser"><?=$this->escape($article->getTeaser()) ?></p>
            <?php endif; ?>
            <h1><?=$this->escape($article->getTitle()) ?></h1>
            <div class="game-article-meta">
                <?php if ($authorMarkup !== ''): ?><span><i class="fa-solid fa-user"></i> <?=$authorMarkup ?></span><?php endif; ?>
                <span><i class="fa-solid fa-calendar"></i> <a href="<?=$this->getUrl(['controller' => 'archive', 'action' => 'show', 'year' => $date->format('Y', true), 'month' => $date->format('m', true)]) ?>"><?=$date->format('d.', true) ?> <?=$this->getTrans($date->format('F', true)) ?> <?=$date->format('Y', true) ?></a></span>
                <span><i class="fa-regular fa-clock"></i> <?=$date->format('H:i', true) ?></span>
                <?php if (!empty($categories)): ?><span><i class="fa-regular fa-folder-open"></i> <?=implode(', ', $categories) ?></span><?php endif; ?>
                <span><i class="fa-regular fa-comment"></i> <a href="#comment"><?=$this->get('commentsCount') ?></a></span>
                <span><i class="fa-regular fa-eye"></i> <?=$article->getVisits() ?></span>
                <?php if ($article->getTopArticle()) : ?><span><i class="fa-regular fa-star"></i> <?=$this->getTrans('topArticle') ?></span><?php endif; ?>
                <?php if ($config->get('article_articleRating')) : ?><span><i class="fa-solid fa-thumbs-up"></i> <?=$countOfVotes ?></span><?php endif; ?>
            </div>
        </header>

        <?php if (!empty($article->getImage())): ?>
            <figure class="game-article-media game-article-media-large">
                <img class="article_image" src="<?=$this->getBaseUrl($article->getImage()) ?>" alt="<?=$this->escape($article->getTitle()) ?>">
                <?php if (!empty($article->getImageSource())): ?>
                    <figcaption class="article_image_source"><?=$this->getTrans('imageSource') ?>: <?=$this->escape($article->getImageSource()) ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <div class="game-article-card-body">
            <div class="game-article-copy ck-content"><?=$this->purify($content) ?></div>

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

        <?php if (empty($preview) && !$article->getCommentsDisabled()): ?>
            <div id="comment" class="game-article-comments">
                <?php
                $commentsClass = new Ilch\Comments();
                echo $commentsClass->getComments(sprintf(Modules\Article\Config\Config::COMMENT_KEY_TPL, $article->getId()), $article, $this);
                ?>
            </div>
        <?php endif; ?>
    </article>
<?php else: ?>
    <div class="game-article-empty"><?=$this->getTrans('noArticles') ?></div>
<?php endif; ?>
