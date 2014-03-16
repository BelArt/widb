<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $data->temporary ? $this->createUrl('collections/viewTemp', array('id' => $data->id)) : $this->createUrl('collections/view', array('id' => $data->id));
?>

<table class="itemList">
    <tr>
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
        </td>
    </tr>
</table>