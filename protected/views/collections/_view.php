<?php
/* @var $this CollectionsController */
/* @var $data Collections */
// xdebug_var_dump($data);exit;
?>

<li class="span3">
    <a href="#" class="thumbnail">
        <img src="<?= CHtml::encode($data->thumbnailBig) ?>" alt="" />
    </a>
    <p class='cls_collectionName'><a href="#"><?= CHtml::encode($data->name) ?></a></p>
</li>