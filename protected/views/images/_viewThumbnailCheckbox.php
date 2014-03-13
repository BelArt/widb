<?php
/* @var $this ObjectsController */
/* @var $data Objects */

$itemUrl = $this->createUrl('images/view', array('id' => $data->id));

?>

<li class="itemThumbnail" >
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailMedium) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
    </a>
    <table class="itemThumbnailDescriptionBlock">
        <tr>
            <td class="itemThumbnailCheckboxBlock"><input type="checkbox" class="_imageItem" data-image-id="<?= $data->id ?>" /></td>
            <td class="itemThumbnailTextBlock">
                <p class='itemThumbnailName longTextFadeCheckbox'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
                <?php if(!empty($data->resolution)): ?>
                    <p class='itemThumbnailResolution'><?= CHtml::encode($data->resolution) ?></p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</li>