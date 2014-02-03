<?php
/* @var $this ObjectsController */
/* @var $Object Objects */
/* @var $collection Collections */

/* @var $ObjectsDataProvider CActiveDataProvider */
/* @var $ChildCollectionsDataProvider CActiveDataProvider */
/* @var $renderViewChildCollections string */
/* @var $renderViewObjects string */

Yii::app()->clientScript->registerPackage('objectView');
?>

<div class="entityThumbnail">
    <img src="<?= CHtml::encode($Object->thumbnailBig) ?>" alt="<?= CHtml::encode($Object->name) ?>" title="<?= CHtml::encode($Object->name) ?>" />
</div>

<div class="entityDescription">
    <?
    $attributes = array();
    if (!empty($Object->author->initials)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('author_id'), 'value' => CHtml::encode($Object->author->initials));
    }
    if (!empty($Object->period)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('period'), 'value' => CHtml::encode($Object->period));
    }
    if (!empty($Object->type->name)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('type_id'), 'value' => CHtml::encode($Object->type->name));
    }
    if (!empty($Object->width)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('width'), 'value' => CHtml::encode(floatval($Object->width).' '.Yii::t('common', 'см')));
    }
    if (!empty($Object->height)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('height'), 'value' => CHtml::encode(floatval($Object->height).' '.Yii::t('common', 'см')));
    }
    if (!empty($Object->depth)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('depth'), 'value' => CHtml::encode(floatval($Object->depth).' '.Yii::t('common', 'см')));
    }
    if (!empty($Object->description)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('description'), 'value' => CHtml::encode($Object->description));
    }
    if (!empty($Object->inventory_number)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('inventory_number'), 'value' => CHtml::encode($Object->inventory_number));
    }
    if (!empty($Object->department)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('department'), 'value' => CHtml::encode($Object->department));
    }
    if (!empty($Object->keeper)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('keeper'), 'value' => CHtml::encode($Object->keeper));
    }
    /*if (!empty($Object->width)) {
        $attributes[] = array('label' => $Object->getAttributeLabel('size'), 'value' => $Object->width.' x '.$Object->height.' x '.$Object->depth.' '.Yii::t('common', 'см'));
    }*/

    $this->widget(
        'bootstrap.widgets.TbDetailView',
        array(
            'data' => $Object,
            'attributes' => $attributes,
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
                'content' => $this->renderPartial($renderViewImages, array('Object' => $Object), true),
                'active' => true
            ),

        ),
    )
);
?>



