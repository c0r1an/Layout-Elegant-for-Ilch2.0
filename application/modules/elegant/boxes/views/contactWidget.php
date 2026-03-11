<?php
/** @var \Ilch\View $this */
if (!$this->get('enabled')) {
    return;
}

$validation = $this->get('validation');
$values = $this->get('values') ?? [];
$receivers = $this->get('receivers') ?? [];
$formId = (string) $this->get('formId');
$hasError = static function ($validation, string $field): bool {
    return $validation && $validation->getErrorBag()->hasError($field);
};
?>
<section class="elegant-widget elegant-contact-widget">
    <h3><?=$this->escape($this->get('title')) ?></h3>

    <?php if (!empty($this->get('welcomeMessage'))): ?>
        <div class="elegant-contact-intro ck-content"><?=$this->alwaysPurify($this->get('welcomeMessage')) ?></div>
    <?php endif; ?>

    <?php if (empty($receivers)): ?>
        <p class="elegant-widget-empty"><?=$this->getTrans('contactWidgetNoReceivers') ?></p>
    <?php else: ?>
        <?php if ($this->get('sent')): ?>
            <div class="alert alert-success"><?=$this->getTrans('contactWidgetSendSuccess') ?></div>
        <?php endif; ?>

        <form id="<?=$this->escape($formId) ?>" class="elegant-contact-form" method="post">
            <?=$this->getTokenField() ?>
            <input type="hidden" name="elegantContactWidget" value="1">

            <div class="d-none">
                <input type="text" name="bot" value="">
            </div>

            <div class="elegant-contact-field<?=$hasError($validation, 'receiver') ? ' has-error' : '' ?>">
                <label for="<?=$this->escape($formId) ?>_receiver"><?=$this->getTrans('contactWidgetReceiver') ?></label>
                <select class="form-select" id="<?=$this->escape($formId) ?>_receiver" name="receiver">
                    <?php foreach ($receivers as $receiver): ?>
                        <option value="<?=$receiver->getId() ?>"<?=((string) ($values['receiver'] ?? '') === (string) $receiver->getId()) ? ' selected="selected"' : '' ?>>
                            <?=$this->escape($receiver->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="elegant-contact-grid">
                <div class="elegant-contact-field<?=$hasError($validation, 'senderName') ? ' has-error' : '' ?>">
                    <label for="<?=$this->escape($formId) ?>_name"><?=$this->getTrans('contactWidgetName') ?></label>
                    <input class="form-control" type="text" id="<?=$this->escape($formId) ?>_name" name="senderName" value="<?=$this->escape((string) ($values['senderName'] ?? '')) ?>">
                </div>

                <div class="elegant-contact-field<?=$hasError($validation, 'senderEmail') ? ' has-error' : '' ?>">
                    <label for="<?=$this->escape($formId) ?>_email"><?=$this->getTrans('contactWidgetEmail') ?></label>
                    <input class="form-control" type="email" id="<?=$this->escape($formId) ?>_email" name="senderEmail" value="<?=$this->escape((string) ($values['senderEmail'] ?? '')) ?>">
                </div>
            </div>

            <div class="elegant-contact-field<?=$hasError($validation, 'message') ? ' has-error' : '' ?>">
                <label for="<?=$this->escape($formId) ?>_message"><?=$this->getTrans('contactWidgetMessage') ?></label>
                <textarea class="form-control" id="<?=$this->escape($formId) ?>_message" name="message" rows="6"><?=$this->escape((string) ($values['message'] ?? '')) ?></textarea>
            </div>

            <div class="elegant-contact-check<?=$hasError($validation, 'privacy') ? ' has-error' : '' ?>">
                <label>
                    <input type="checkbox" name="privacy" value="1"<?=((string) ($values['privacy'] ?? '') === '1') ? ' checked="checked"' : '' ?>>
                    <span><?=$this->getTrans('contactWidgetAcceptPrivacy') ?></span>
                </label>
            </div>

            <?php if ($this->get('captchaNeeded') && $this->get('defaultcaptcha')): ?>
                <div class="elegant-contact-captcha">
                    <?=$this->get('defaultcaptcha')->getCaptcha($this) ?>
                </div>
            <?php endif; ?>

            <div class="elegant-contact-actions">
                <?php
                if ($this->get('captchaNeeded') && $this->get('googlecaptcha')) {
                    echo $this->get('googlecaptcha')->setForm($formId)->getCaptcha($this, 'contactWidgetSend', 'saveElegantContact');
                } else {
                    echo '<button type="submit" class="elegant-button">' . $this->escape($this->getTrans('contactWidgetSend')) . '</button>';
                }
                ?>
            </div>
        </form>
    <?php endif; ?>
</section>
