<?php

/** @var \Ilch\View $this */

use Ilch\Date;

/** @var bool $DESCPostorder */
$DESCPostorder = $this->get('DESCPostorder');
/** @var int $postsPerPage */
$postsPerPage = $this->get('postsPerPage');
?>

<?php if (!empty($this->get('lastActiveTopicsToShow'))) : ?>
    <div class="game-archive-box">
        <ul class="game-archive-list game-forum-archive-list">
        <?php foreach ($this->get('lastActiveTopicsToShow') as $topic) : ?>
            <?php $date = new Date($topic['lastPost']->getDateCreated()); ?>
            <li>
                <a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $topic['topicId'], 'page' => ($DESCPostorder ? 1 : ceil($topic['countPosts'] / $postsPerPage))]) ?>#<?=$topic['lastPost']->getId() ?>">
                    <span><?=$this->escape($topic['topicTitle']) ?></span>
                    <span class="game-archive-badge"><?=$topic['countPosts'] ?></span>
                    <small class="game-forum-archive-meta">
                        <?=$this->getTrans('by') ?> <?=$this->escape($topic['lastPost']->getAutor()->getName()) ?>
                        - <?=$date->format('d.m.y - H:i', true) ?> <?=$this->getTrans('clock') ?>
                    </small>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php else : ?>
    <p class="game-vote-empty"><?=$this->getTrans('noPosts') ?></p>
<?php endif; ?>
