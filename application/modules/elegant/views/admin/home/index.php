<?php /** @var \Ilch\View $this */ ?>
<style>
.elegant-home-builder {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.5rem;
}

.elegant-home-builder-column {
    border: 1px solid #d9dee3;
    background: #fff;
}

.elegant-home-builder-header {
    padding: 0.9rem 1rem;
    border-bottom: 1px solid #e7ebef;
    background: #f8f9fa;
    font-weight: 600;
}

.elegant-home-builder-list {
    min-height: 360px;
    margin: 0;
    padding: 1rem;
    list-style: none;
}

.elegant-home-builder-item {
    margin-bottom: 0.75rem;
    padding: 0.9rem 1rem;
    border: 1px solid #d9dee3;
    background: #fff;
    cursor: move;
}

.elegant-home-builder-item:last-child {
    margin-bottom: 0;
}

.elegant-home-builder-item h4 {
    margin: 0 0 0.35rem;
    font-size: 1rem;
}

.elegant-home-builder-item p {
    margin: 0;
    color: #6c757d;
    font-size: 0.92rem;
}

.elegant-home-builder-placeholder {
    height: 58px;
    margin-bottom: 0.75rem;
    border: 1px dashed #adb5bd;
    background: #f8f9fa;
}

@media (max-width: 991.98px) {
    .elegant-home-builder {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="alert alert-info">
    <?=$this->getTrans('homepageInfo') ?>
</div>

<form method="post" id="elegant-home-builder-form">
    <?=$this->getTokenField() ?>
    <input type="hidden" name="homepageSections" id="homepageSections" value="<?=$this->escape((string) $this->get('homepageSectionsJson')) ?>">

    <div class="elegant-home-builder mb-3">
        <div class="elegant-home-builder-column">
            <div class="elegant-home-builder-header"><?=$this->getTrans('homepageAvailable') ?></div>
            <ul class="elegant-home-builder-list connectedSortable" id="elegant-home-available">
                <?php foreach ($this->get('inactiveSections') as $section): ?>
                    <li class="elegant-home-builder-item" data-section-key="<?=$this->escape($section['key']) ?>">
                        <h4><?=$this->escape($section['title']) ?></h4>
                        <p><?=$this->escape($section['description']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="elegant-home-builder-column">
            <div class="elegant-home-builder-header"><?=$this->getTrans('homepageActive') ?></div>
            <ul class="elegant-home-builder-list connectedSortable" id="elegant-home-active">
                <?php foreach ($this->get('activeSections') as $section): ?>
                    <li class="elegant-home-builder-item" data-section-key="<?=$this->escape($section['key']) ?>">
                        <h4><?=$this->escape($section['title']) ?></h4>
                        <p><?=$this->escape($section['description']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php foreach ($this->get('homepageCustomContents') as $customContent): ?>
        <div class="card mb-3">
            <div class="card-header"><?=$this->escape($customContent['title']) ?></div>
            <div class="card-body">
                <p class="text-muted"><?=$this->escape($customContent['hint']) ?></p>
                <div class="row mb-3">
                    <label class="col-xl-3 col-form-label" for="homepageCustomContentWidth<?=$this->escape($customContent['index']) ?>">
                        <?=$this->getTrans('homepageContentWidth') ?>
                    </label>
                    <div class="col-xl-9">
                        <select class="form-select" name="homepageCustomContentWidth<?=$this->escape($customContent['index']) ?>" id="homepageCustomContentWidth<?=$this->escape($customContent['index']) ?>">
                            <?php foreach ($customContent['widthOptions'] as $widthValue => $widthLabel): ?>
                                <option value="<?=$this->escape($widthValue) ?>"<?=((string) $customContent['width'] === (string) $widthValue) ? ' selected="selected"' : '' ?>><?=$this->escape($widthLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <textarea class="form-control ckeditor" name="homepageCustomContent<?=$this->escape($customContent['index']) ?>" id="homepageCustomContent<?=$this->escape($customContent['index']) ?>" toolbar="ilch_html"><?=$this->escape((string) $customContent['value']) ?></textarea>
            </div>
        </div>
    <?php endforeach; ?>

    <?=$this->getSaveBar('saveButton') ?>
</form>

<script>
(function ($) {
    if (typeof $ === 'undefined' || !$.fn.sortable) {
        return;
    }

    var activeList = $('#elegant-home-active');
    var hiddenInput = $('#homepageSections');

    function updateOrderField() {
        var order = [];

        activeList.find('[data-section-key]').each(function () {
            order.push($(this).attr('data-section-key'));
        });

        hiddenInput.val(JSON.stringify(order));
    }

    $('.connectedSortable').sortable({
        connectWith: '.connectedSortable',
        placeholder: 'elegant-home-builder-placeholder',
        items: '> li',
        tolerance: 'pointer',
        update: updateOrderField,
        receive: updateOrderField
    }).disableSelection();

    updateOrderField();
})(window.jQuery);
</script>
