<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
/* @var $renderViewImages string */
/* @var $attributesForMainDetailViewWidget array */
/* @var $attributesForSystemDetailViewWidget array */

Yii::app()->clientScript->registerPackage('objectView');
?>

<?php if (!empty($Object->authorInitials)): ?>
    <p class="entitySubname">
        <span class="subname"><?= CHtml::encode($Object->authorInitials) ?></span>
        <?php if (!empty($Object->period)): ?>
            <span class="subname">, <?= CHtml::encode($Object->period) ?></span>
        <?php endif; ?>
    </p>
<?php endif; ?>


<div class="entityThumbnail">
    <a href="<?= CHtml::encode($Object->thumbnailBig) ?>" class="_fancybox" title="<?= CHtml::encode($Object->name) ?>"><img src="<?= CHtml::encode($Object->thumbnailMedium) ?>" alt="<?= CHtml::encode($Object->name) ?>" title="<?= CHtml::encode($Object->name) ?>" class="medium" /></a>
</div>

<div class="entityDescription">
    <?
    $this->widget(
        'bootstrap.widgets.TbDetailView',
        array(
            'data' => $Object,
            'attributes' => $attributesForMainDetailViewWidget,
        )
    );
    ?>
</div>

<div class="entityDescription2">
    <?
    $this->widget(
        'bootstrap.widgets.TbDetailView',
        array(
            'data' => $Object,
            'attributes' => $attributesForSystemDetailViewWidget,
        )
    );
    ?>
</div>

<div class="clearBoth"></div>

<!--<div class="gape"></div>-->

<?php
$this->widget(
    'bootstrap.widgets.TbTabs',
    array(
        'type' => 'tabs', // 'tabs' or 'pills'
        //'placement' => 'below',
        'tabs' => array(
            array(
                'label' => Yii::t('objects', 'Изображения объекта'),
                'content' => $this->renderPartial($renderViewImages, array('Object' => $Object, 'ImagesDataProvider' => $ImagesDataProvider), true),
                'active' => true
            ),

        ),
    )
);
?>



