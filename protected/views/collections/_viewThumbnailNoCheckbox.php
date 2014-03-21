<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $data->temporary ? Yii::app()->urlManager->createTempCollectionUrl($data) : Yii::app()->urlManager->createNormalCollectionUrl($data);
?>

<li class="itemThumbnail">
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
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
            <td class="itemThumbnailTextBlock">
                <p class='itemThumbnailName longTextFadeNoCheckbox'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
            </td>

        </tr>
    </table>
</li>