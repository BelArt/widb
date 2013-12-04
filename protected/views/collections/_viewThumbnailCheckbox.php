<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $data->temporary ? $this->createUrl('collections/viewTemp', array('id' => $data->id)) : $this->createUrl('collections/view', array('id' => $data->id));
?>

<li class="itemThumbnail">
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
    </a>

    <table class="itemThumbnailDescriptionBlock">
        <tr>
            <td class="itemThumbnailCheckboxBlock"><input type="checkbox" /></td>
            <td class="itemThumbnailTextBlock"><p class='itemThumbnailName'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p></td>
        </tr>
    </table>
</li>