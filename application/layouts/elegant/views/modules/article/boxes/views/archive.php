<?php
/** @var \Ilch\View $this */
$articleMapper = $this->get('articleMapper');
$archive = $this->get('archive');
$readAccess = $this->get('readAccess');
?>

<?php if (!empty($archive)): ?>
    <div class="game-archive-box">
        <ul class="game-archive-list">
            <?php foreach ($archive as $archiveItem): ?>
                <?php
                $date = new \Ilch\Date($archiveItem->getDateCreated());
                $archiveUrl = $this->getUrl([
                    'module' => 'article',
                    'controller' => 'archive',
                    'action' => 'show',
                    'year' => $date->format('Y', true),
                    'month' => $date->format('m', true),
                ]);
                $archiveLabel = $this->getTrans($date->format('F', true)) . $date->format(' Y', true);
                $archiveCount = $articleMapper->getCountArticlesByMonthYearAccess($archiveItem->getDateCreated(), $readAccess);
                ?>
                <li>
                    <a href="<?=$archiveUrl ?>">
                        <span><?=$archiveLabel ?></span>
                        <span class="game-archive-badge"><?=$archiveCount ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <div class="game-article-empty"><?=$this->getTrans('noArticles') ?></div>
<?php endif; ?>
