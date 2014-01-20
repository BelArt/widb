<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $this->createUrl('objects/view', array('id' => $data->id));
?>

<table class="itemList">
    <tr>
        <td class="itemListCheckboxBlock"><input type="checkbox" class="_objectItem" data-object-id="<?= $data->id ?>" /></td>
        <td class="itemListImageBlock">
            <a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>" class="thumbnail ">
                <img src="<?= CHtml::encode($data->thumbnailSmall) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
            </a>
        </td>
        <td class="itemListDescriptionBlock">
            <p class="itemListName"><a href='<?= CHtml::encode($itemUrl) ?>'><?= CHtml::encode($data->name) ?></a></p>
            <?php if(!empty($data->author->initials)): ?>
                <p class='itemListAuthor'><?= CHtml::encode($data->author->initials) ?></p>
            <?php endif; ?>
        </td>
    </tr>
</table>