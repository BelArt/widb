<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $this->createUrl('collections/view', array('id' => $data->id));
?>

<li class="itemThumbnailBig">
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
    </a>

    <table class="itemThumbnailDescriptionBlock">
        <tr>
            <td class="itemThumbnailTextBlock"><p class='itemThumbnailName'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p></td>
            <td class="itemThumbnailCheckboxBlock"><input type="checkbox" /></td>
        </tr>
    </table>
</li>