<?php
/* @var $this CollectionsController */
/* @var $data Collections */

$itemUrl = $this->createUrl('collections/view', array('id' => $data->id));
?>

<li class="span3">
    <a href="<?= CHtml::encode($itemUrl) ?>" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" class="cls_thumbnailMedium" />
    </a>
    <p class='cls_collectionName'><a href="<?= CHtml::encode($itemUrl) ?>" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
</li>