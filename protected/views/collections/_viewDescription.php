<?php
/* @var $this CollectionsController */
/* @var $model Collections */
?>
<div class="entityThumbnail">
    <img src="<?= CHtml::encode($model->thumbnailBig) ?>" alt="<?= CHtml::encode($model->name) ?>" title="<?= CHtml::encode($model->name) ?>" class="medium" />
</div>

<div class="entityDescription">
    <?= $model->description ?>
</div>

<div class="clearBoth"></div>