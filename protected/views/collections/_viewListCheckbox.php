<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $this->createUrl('collections/view', array('id' => $data->id));
?>

<table class="itemList">
    <tr>
        <td class="itemListCheckboxBlock"><input type="checkbox" /></td>
        <td class="itemListImageBlock">
            <a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>" class="linkWithCoolBorder">
                <img src="<?= CHtml::encode($data->thumbnailMedium) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
            </a>
        </td>
        <td class="itemListDescriptionBlock">
            <p>
                <span class="name"><?= CHtml::encode($data->name) ?></span>
            </p>
        </td>
    </tr>
</table>