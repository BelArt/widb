<?php
/* @var $this ImagesController */
/* @var $Image Images */
/* @var $attributesForMainDetailViewWidget array */
/* @var $attributesForSystemDetailViewWidget array */

Yii::app()->clientScript->registerPackage('imageView');
?>

<?php if ($Image->date_photo != '0000-00-00'): ?>
    <p class="entitySubname">
        <span class="subname"><?= CHtml::encode($Image->getPhotoDateWithIntroWord()) ?></span>
        <?php if (!empty($Image->photoType->name)): ?>
            <span class="subname">, <?= CHtml::encode(MyOutputHelper::stringToLower($Image->photoType->name)) ?></span>
        <?php endif; ?>
    </p>
<?php endif; ?>

<div class="entityThumbnail">
    <a href="<?= CHtml::encode($Image->thumbnailBig) ?>" class="_fancybox" title="<?= CHtml::encode($Image->name) ?>"><img src="<?= CHtml::encode($Image->thumbnailMedium) ?>" alt="<?= CHtml::encode($Image->name) ?>" title="<?= CHtml::encode($Image->name) ?>" class="medium" /></a>
</div>

<div class="entityDescription">
    <?
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $Image,
        'attributes' => $attributesForMainDetailViewWidget,
    ));
    ?>
</div>

<div class="entityDescription2">
    <?
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $Image,
        'attributes' => $attributesForSystemDetailViewWidget,
    ));
    ?>
</div>

<div class="clearBoth"></div>



