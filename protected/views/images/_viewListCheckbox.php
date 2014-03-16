<?php
/* @var $this ObjectsController */
/* @var $data Objects */

$itemUrl = $this->createUrl('images/view', array('id' => $data->id));
?>

<table class="itemList">
    <tr>
        <td class="itemListCheckboxBlock"><input type="checkbox" class="_imageItem" data-image-id="<?= $data->id ?>" /></td>
        <td class="itemListImageBlock">
            <a href="<?= CHtml::encode($data->thumbnailBig) ?>" title="<?= CHtml::encode($data->name) ?>" class="thumbnail _fancybox">
                <table class="itemListImageWrapper">
                    <tr>
                        <td>
                            <img src="<?= CHtml::encode($data->thumbnailSmall) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
                        </td>
                    </tr>
                </table>

            </a>
        </td>
        <td class="itemListDescriptionBlock">
            <p class="itemListName"><a href='<?= CHtml::encode($itemUrl) ?>'><?= CHtml::encode($data->name) ?></a></p>
            <p class="itemListData1">
                <?php /*if(!empty($data->resolution)): */?><!--
                    <span class='itemListResolution'><?/*= CHtml::encode($data->resolution) */?></span>
                --><?php /*endif; */?>
                <?php if (!empty($data->date_photo) && $data->date_photo != '0000-00-00'): ?>
                    <span class="itemListPhotoDate"><?= CHtml::encode($data->getPhotoDateWithIntroWord()) ?></span>
                <?php endif; ?>
                <?php if (!empty($data->original)): ?>
                    <span class="itemListPathToOriginal">, <?= CHtml::encode($data->original) ?></span>
                <?php endif; ?>
            </p>
            <p class="itemListData2">
                <?php if(!empty($data->description)): ?>
                    <span class="itemListDescription"><?= CHtml::encode($data->description) ?></span>
                <?php endif; ?>
            </p>
        </td>
    </tr>
</table>