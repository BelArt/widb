<?php
/* @var $this ObjectsController */
/* @var $data Objects */

$itemUrl = $this->createUrl('images/view', array('id' => $data->id));

?>

<li class="itemThumbnail" >
    <a href="<?= CHtml::encode($data->thumbnailBig) ?>" class="thumbnail _fancybox" title="<?= CHtml::encode($data->name) ?>">
        <table class="itemThumbnailImageWrapper">
            <tr>
                <td>
                    <img src="<?= CHtml::encode($data->thumbnailMedium) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" />
                </td>
            </tr>
        </table>

    </a>
    <table class="itemThumbnailDescriptionBlock">
        <tr>
            <td class="itemThumbnailCheckboxBlock"><input type="checkbox" class="_imageItem" data-image-id="<?= $data->id ?>" /></td>
            <td class="itemThumbnailTextBlock">
                <p class='itemThumbnailName longTextFadeCheckbox'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
                <p class='itemThumbnailResolution'><?= CHtml::encode($data->getPhotoDateWithIntroWord()) ?></p>
            </td>
        </tr>
    </table>
</li>