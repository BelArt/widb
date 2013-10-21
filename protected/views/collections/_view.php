<?php
/* @var $this CollectionsController */
/* @var $data Collections */
// xdebug_var_dump($data);exit;
?>

<li class="span3">
    <a href="#" class="thumbnail" title="<?= CHtml::encode($data->name) ?>">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="<?= CHtml::encode($data->name) ?>" title="<?= CHtml::encode($data->name) ?>" class="cls_thumbnailMedium" />
    </a>
    <p class='cls_collectionName'><a href="#" title="<?= CHtml::encode($data->name) ?>"><?= CHtml::encode($data->name) ?></a></p>
</li>