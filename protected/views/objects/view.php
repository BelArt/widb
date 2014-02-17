<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $ImagesDataProvider CActiveDataProvider */
/* @var $renderViewImages string */
/* @var $attributesForDetailViewWidget array */

Yii::app()->clientScript->registerPackage('objectView');
?>

<div class="entityThumbnail">
    <img src="<?= CHtml::encode($Object->thumbnailBig) ?>" alt="<?= CHtml::encode($Object->name) ?>" title="<?= CHtml::encode($Object->name) ?>" />
</div>

<div class="entityDescription">
    <?
    $this->widget(
        'bootstrap.widgets.TbDetailView',
        array(
            'data' => $Object,
            'attributes' => $attributesForDetailViewWidget,
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



