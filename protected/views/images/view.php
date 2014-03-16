<?php
/* @var $this ImagesController */
/* @var $Image Images */
/* @var $attributes array */

Yii::app()->clientScript->registerPackage('imageView');
?>

<div class="entityThumbnail">
    <img src="<?= CHtml::encode($Image->thumbnailBig) ?>" alt="<?= CHtml::encode($imageName) ?>" title="<?= CHtml::encode($imageName) ?>" class="medium" />
</div>

<div class="entityDescription">
    <?
    $this->widget(
        'bootstrap.widgets.TbDetailView',
        array(
            'data' => $Image,
            'attributes' => $attributes,
        )
    );
    ?>
</div>

<div class="clearBoth"></div>



