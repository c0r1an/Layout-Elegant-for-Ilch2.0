<?php

/** @var \Ilch\View $this */

/**
 * @param string $name
 * @param array $value
 * @param array $settingsValues
 * @param \Ilch\View $obj
 * @return string
 */
function renderLayoutSettingInput(string $name, array $value, array $settingsValues, \Ilch\View $obj): string
{
    static $mediaInputCounter = 0;

    $settingsValue = empty($settingsValues[$name])
        ? $obj->escape($value['default'] ?? '')
        : $obj->escape($settingsValues[$name]->getValue());
    $name = $obj->escape($name);
    $input = '';

    switch ($value['type'] ?? '') {
        case 'bscolorpicker':
            $input = sprintf(
                '<input class="form-control color {hash:true}" id="%s" name="%s" data-jscolor="" value="%s">',
                $name,
                $name,
                $settingsValue
            );
            break;

        case 'ckeditorhtml':
            $input = sprintf(
                '<textarea class="form-control ckeditor" name="%s" id="%s" toolbar="ilch_html">%s</textarea>',
                $name,
                $name,
                $settingsValue
            );
            break;

        case 'colorpicker':
            $input = sprintf('<input type="color" id="%s" name="%s" value="%s">', $name, $name, $settingsValue);
            break;

        case 'flipswitch':
            $input = '<div class="flipswitch"><input type="radio" class="flipswitch-input" id="%s-on" name="%s" value="1" ' . (empty($settingsValue) ? '' : 'checked="checked"') . '/>
                      <label for="%s-on" class="flipswitch-label flipswitch-label-on">%s</label>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('on'));
            $input .= '<input type="radio" class="flipswitch-input" id="%s-off" name="%s" value="0" ' . (!empty($settingsValue) ? '' : 'checked="checked"') . ' />
                       <label for="%s-off" class="flipswitch-label flipswitch-label-off">%s</label><span class="flipswitch-selection"></span></div>';
            $input = sprintf($input, $name, $name, $name, $obj->getTrans('off'));
            break;

        case 'mediaselection':
            $mediaInputCounter++;
            $mediaInputId = '_' . $mediaInputCounter;
            $mediaElementId = 'selectedImage' . $mediaInputId;
            $mediaFunctionName = 'media' . $mediaInputId;
            $input = sprintf(
                '<div class="input-group">
                    <input class="form-control"
                           type="text"
                           name="%1$s"
                           id="%2$s"
                           value="%3$s"
                           autocomplete="off"
                           spellcheck="false" />
                    <button class="btn btn-outline-secondary"
                            type="button"
                            aria-label="%4$s"
                            onclick="%5$s()"><i class="fa-regular fa-image"></i></button>
                    <button class="btn btn-outline-secondary"
                            type="button"
                            data-media-clear-target="%2$s"
                            aria-label="%6$s"
                            onclick="document.getElementById(\'%2$s\').value = \'\'; document.getElementById(\'%2$s\').focus();"><i class="fa-solid fa-xmark"></i></button>
                </div>',
                $name,
                $mediaElementId,
                $settingsValue,
                $obj->getTrans('media'),
                $mediaFunctionName,
                $obj->getTrans('delete')
            );
            $input .= '<script>' . $obj->getMedia()
                    ->addMediaButton($obj->getUrl('admin/media/iframe/index/type/single/input/' . $mediaInputId . '/'))
                    ->addInputId($mediaInputId)
                    ->addUploadController($obj->getUrl('admin/media/index/upload')) .
                '</script>';
            break;

        case 'iconpicker':
            $pickerInputId = 'iconpicker_' . $name;
            $pickerPreviewId = $pickerInputId . '_preview';
            $pickerMenuId = $pickerInputId . '_menu';
            $pickerButtonId = $pickerInputId . '_button';
            $input = sprintf(
                '<div class="input-group">
                    <span class="input-group-text"><i id="%s" class="%s"></i></span>
                    <input class="form-control"
                           type="text"
                           name="%s"
                           id="%s"
                           value="%s"
                           autocomplete="off"
                           spellcheck="false" />
                    <button class="btn btn-outline-secondary dropdown-toggle"
                            type="button"
                            id="%s"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false">Select Icon</button>
                    <ul class="dropdown-menu dropdown-menu-end p-2"
                        id="%s"
                        style="max-height: 320px; overflow-y: auto; min-width: 280px;"
                        aria-labelledby="%s">',
                $pickerPreviewId,
                $settingsValue,
                $name,
                $pickerInputId,
                $settingsValue,
                $pickerButtonId,
                $pickerMenuId,
                $pickerButtonId
            );

            foreach (($value['options'] ?? []) as $optionValue => $optionLabel) {
                $optionValueEscaped = $obj->escape((string) $optionValue);
                $optionLabelEscaped = $obj->escape((string) $optionLabel);
                $input .= sprintf(
                    '<li><button class="dropdown-item d-flex align-items-center gap-2 py-1"
                                 type="button"
                                 data-iconpicker-target="%s"
                                 data-iconpicker-value="%s"><i class="%s"></i><span>%s</span></button></li>',
                    $pickerInputId,
                    $optionValueEscaped,
                    $optionValueEscaped,
                    $optionLabelEscaped
                );
            }

            $input .= '</ul></div>';
            $input .= sprintf(
                '<script>(function(){var input=document.getElementById("%1$s");var preview=document.getElementById("%2$s");if(!input||!preview){return;}var update=function(){preview.className=input.value.trim();};update();input.addEventListener("input",update);document.querySelectorAll("[data-iconpicker-target=\'%1$s\']").forEach(function(button){button.addEventListener("click",function(){input.value=button.getAttribute("data-iconpicker-value")||"";update();input.dispatchEvent(new Event("input",{bubbles:true}));input.dispatchEvent(new Event("change",{bubbles:true}));});});})();</script>',
                $pickerInputId,
                $pickerPreviewId
            );
            break;

        case 'select':
            $input = sprintf('<select class="form-select" name="%s" id="%s">', $name, $name);

            foreach (($value['options'] ?? []) as $optionValue => $optionLabel) {
                $input .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    $obj->escape((string) $optionValue),
                    ((string) $settingsValue === (string) $optionValue) ? 'selected="selected"' : '',
                    $obj->escape((string) $optionLabel)
                );
            }

            $input .= '</select>';
            break;

        case 'text':
            $input = sprintf(
                '<input class="form-control" type="text" name="%s" id="%s" maxlength="255" value="%s" />',
                $name,
                $name,
                $settingsValue
            );
            break;

        case 'url':
            $input = sprintf(
                '<input class="form-control" type="url" name="%s" id="%s" value="%s" />',
                $name,
                $name,
                $settingsValue
            );
            break;

        case 'textarea':
            $input = sprintf(
                '<textarea class="form-control" name="%s" id="%s">%s</textarea>',
                $name,
                $name,
                $settingsValue
            );
            break;

        default:
            break;
    }

    if ($input === '') {
        return $input;
    }

    return sprintf(
        '<input type="hidden" name="%s" value="" />%s',
        $name,
        $input
    );
}
?>

<div class="alert alert-info">
    <?=$this->getTrans('moduleInfo') ?>
</div>

<?php if (!$this->get('isElegantStartPage')) : ?>
    <div class="alert alert-warning">
        <p class="mb-2"><?=$this->getTrans('startPageWarning') ?></p>
        <p class="mb-3">
            <a href="<?=$this->escape((string) $this->get('adminSettingsUrl')) ?>" target="_blank" rel="noopener">
                <?=$this->getTrans('openGlobalSettings') ?>
            </a>
        </p>
        <form method="post" class="d-inline">
            <?=$this->getTokenField() ?>
            <input type="hidden" name="setElegantAsStartPage" value="1">
            <button type="submit" class="btn btn-warning btn-sm"><?=$this->getTrans('setAsStartPage') ?></button>
        </form>
    </div>
<?php endif; ?>

<form id="elegant-layout-settings" method="post">
    <?=$this->getTokenField() ?>

    <?php $sectionOpen = false; ?>
    <?php foreach ($this->get('settings') as $key => $value) : ?>
        <?php if (($value['type'] ?? '') === 'separator') : ?>
            <?php if ($sectionOpen) : ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="card mb-3">
                <div class="card-header"><?=$this->getOtherLayoutTrans($this->get('layoutKey'), $key) ?></div>
                <div class="card-body">
            <?php $sectionOpen = true; ?>
        <?php else : ?>
                    <div class="row mb-3">
                        <label for="<?=$this->escape($key) ?>" class="col-xl-3 col-form-label">
                            <?=$this->getOtherLayoutTrans($this->get('layoutKey'), $key) ?>
                        </label>
                        <div class="col-xl-9">
                            <?=renderLayoutSettingInput($key, $value, $this->get('settingsValues'), $this) ?>
                            <?php if (!empty($value['description'])) : ?>
                                <div class="text-end">
                                    <small><?=$this->getOtherLayoutTrans($this->get('layoutKey'), $value['description']) ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($sectionOpen) : ?>
                </div>
            </div>
    <?php endif; ?>

    <?=$this->getSaveBar('saveButton') ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>') ?>
<script src="<?=$this->getStaticUrl('js/jscolor/jscolor.min.js') ?>"></script>
