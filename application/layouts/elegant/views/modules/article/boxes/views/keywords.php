<?php if (!empty($this->get('keywordsList'))): ?>
    <div class="game-keywords-box">
        <?php foreach ($this->get('keywordsList') as $keyword => $count): ?>
            <?php
            $keyword = $this->escape($keyword);
            $size = 12;

            if ($count >= 5) {
                $size = (int) $this->get('keywordsH5');
            } elseif ($count == 4) {
                $size = (int) $this->get('keywordsH4');
            } elseif ($count == 3) {
                $size = (int) $this->get('keywordsH3');
            } elseif ($count == 2) {
                $size = (int) $this->get('keywordsH2');
            }
            ?>
            <a href="<?=$this->getUrl(['module' => 'article', 'controller' => 'keywords', 'action' => 'show', 'keyword' => $keyword]) ?>" style="font-size: <?=$size ?>px">
                <?=$keyword ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="game-vote-empty"><?=$this->getTrans('noKeywords') ?></p>
<?php endif; ?>
