<?php

/** @var \Ilch\View $this */

/** @var Modules\Vote\Mappers\Result $resultMapper */
$resultMapper = $this->get('resultMapper');

/** @var Modules\Vote\Models\Vote[]|null $votes */
$votes = $this->get('votes');

/** @var array $groupIds */
$groupIds = $this->get('readAccess');

/** @var string $clientIP */
$clientIP = $this->get('clientIP');
?>

<?php if ($votes) : ?>
    <div class="game-vote-box">
        <?php foreach ($votes as $voteIndex => $groupVote) : ?>
            <?php
            $voteRes = $resultMapper->getVoteRes($groupVote->getId());
            if (!$voteRes) {
                continue;
            }

            $canVote = $groupVote->canVote($clientIP, $this->getUser(), $groupIds);
            $totalResults = (int) $resultMapper->getResultById($groupVote->getId());
            ?>
            <form class="game-vote-poll<?=$voteIndex > 0 ? ' game-vote-poll-spaced' : '' ?>" action="<?=$this->getUrl(['module' => 'vote']) ?>" method="POST">
                <?=$this->getTokenField() ?>
                <input type="hidden" name="id" value="<?=$groupVote->getId() ?>">

                <h4 class="game-vote-question"><?=$this->escape($groupVote->getQuestion()) ?></h4>

                <div class="game-vote-options">
                    <?php foreach ($voteRes as $resultIndex => $voteResModel) : ?>
                        <?php
                        $reply = (string) $voteResModel->getReply();
                        $fieldId = 'game_vote_' . $groupVote->getId() . '_' . $resultIndex;
                        ?>
                        <div class="game-vote-option">
                            <?php if (!$canVote) : ?>
                                <?php
                                $percent = (float) $voteResModel->getPercent($totalResults);
                                $result = (int) $voteResModel->getResult();
                                ?>
                                <div class="game-vote-result-head">
                                    <span><?=$this->escape($reply) ?></span>
                                    <strong><?=$result ?> (<?=number_format($percent, 1) ?>%)</strong>
                                </div>
                                <div class="game-vote-progress" role="progressbar" aria-valuenow="<?=$percent ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="game-vote-progress-bar" style="width: <?=$percent ?>%"></div>
                                </div>
                            <?php else : ?>
                                <label class="game-vote-choice" for="<?=$fieldId ?>">
                                    <input
                                        type="<?=$groupVote->getMultipleReply() ? 'checkbox' : 'radio' ?>"
                                        name="reply[]"
                                        id="<?=$fieldId ?>"
                                        value="<?=$this->escape($reply) ?>"
                                    >
                                    <span><?=$this->escape($reply) ?></span>
                                </label>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($canVote) : ?>
                    <div class="game-vote-actions">
                        <?=$this->getSaveBar('voteButton', 'Vote') ?>
                    </div>
                <?php endif; ?>
            </form>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p class="game-vote-empty"><?=$this->getTrans('noVote') ?></p>
<?php endif; ?>
