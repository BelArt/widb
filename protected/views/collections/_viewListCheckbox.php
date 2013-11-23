<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $this->createUrl('collections/view', array('id' => $data->id));
?>

<table class="itemList">
    <tr>
        <td class="itemListCheckboxBlock"><input type="checkbox" /></td>
        <td class="itemListImageBlock">
            <a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>" class="thumbnail ">
                <img src="<?= CHtml::encode($data->thumbnailMedium) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
            </a>
        </td>
        <td class="itemListDescriptionBlock">
            <p class="itemListName"><a href='<?= CHtml::encode($itemUrl) ?>'><?= CHtml::encode($data->name) ?></a></p>
        </td>
    </tr>
</table>