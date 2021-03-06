<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $data->temporary ? Yii::app()->urlManager->createTempCollectionUrl($data) : Yii::app()->urlManager->createNormalCollectionUrl($data);
?>

<table class="itemList">
    <tr>
        <td class="itemListImageBlock">

            <a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>" class="thumbnail _normalCollectionLink">
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
            <p class="itemListName"><a href='<?= CHtml::encode($itemUrl) ?>' class="_normalCollectionLink"><?= CHtml::encode($data->name) ?></a></p>
        </td>
    </tr>
</table>