<?php
/** @var \Ilch\View $this */
$sections = $this->get('sections') ?? [];
$siteTagline = trim((string) $this->get('siteTagline'));

$flushCustomGrid = static function (array &$customContentSections) {
    if ($customContentSections === []) {
        return;
    }
    ?>
    <section class="elegant-content-zone">
        <div class="elegant-container">
            <div class="row g-4">
                <?php foreach ($customContentSections as $customSection): ?>
                    <?php
                    $columns = (string) ($customSection['columns'] ?? '1');
                    $columnClass = 'col-12';

                    if ($columns === '2') {
                        $columnClass .= ' col-lg-6';
                    } elseif ($columns === '3') {
                        $columnClass .= ' col-lg-4';
                    } elseif ($columns === '4') {
                        $columnClass .= ' col-lg-3';
                    }
                    ?>
                    <div class="<?=$columnClass ?>">
                        <div class="elegant-panel h-100">
                            <div class="elegant-panel-content">
                                <?= $customSection['html'] ?? '' ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    $customContentSections = [];
};

$customContentSections = [];
?>

<?php if (empty($sections)): ?>
    <section class="elegant-content-zone">
        <div class="elegant-container">
            <div class="elegant-columns elegant-columns-full">
                <div class="elegant-main-column">
                    <div class="elegant-panel">
                        <div class="elegant-panel-content">
                            <p><?= $this->escape($siteTagline !== '' ? $siteTagline : 'Elegant* homepage builder is ready.') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php foreach ($sections as $section): ?>
    <?php if (strpos((string) ($section['key'] ?? ''), 'customContent') === 0): ?>
        <?php $customContentSections[] = $section; ?>
        <?php continue; ?>
    <?php endif; ?>

    <?php $flushCustomGrid($customContentSections); ?>

    <?php if (in_array(($section['key'] ?? ''), ['videoWidget', 'socialWidget', 'contactWidget'], true)): ?>
        <section class="elegant-content-zone">
            <div class="elegant-container">
                <div class="elegant-columns elegant-columns-full">
                    <div class="elegant-main-column">
                        <?= $section['html'] ?? '' ?>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <?= $section['html'] ?? '' ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php $flushCustomGrid($customContentSections); ?>
