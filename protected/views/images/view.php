<?php
/* @var $this ImagesController */
/* @var $Image Images */

Yii::app()->clientScript->registerPackage('imageView');
?>

<div class="entityThumbnail">
    <img src="<?= CHtml::encode($Image->thumbnailBig) ?>" alt="<?= CHtml::encode($imageName) ?>" title="<?= CHtml::encode($imageName) ?>" />
</div>

<div class="entityDescription">
    <?
    $attributes = array();
    //$attributes[] = array('label' => $Image->getAttributeLabel('object_id'), 'value' => CHtml::encode($Image->object->name));
    $attributes[] = array('label' => $Image->getAttributeLabel('date_photo'), 'value' => CHtml::encode($Image->date_photo != '0000-00-00' ? $Image->date_photo : Yii::t('images', 'Дата съемки не задана')));
    $attributes[] = array('label' => $Image->getAttributeLabel('photo_type_id'), 'value' => CHtml::encode($Image->photoType->name));
    $attributes[] = array('label' => $Image->getAttributeLabel('description'), 'value' => CHtml::encode($Image->description));
    $attributes[] = array('label' => $Image->getAttributeLabel('width'), 'value' => CHtml::encode($Image->width.' '.Yii::t('common', 'px')));
    $attributes[] = array('label' => $Image->getAttributeLabel('height'), 'value' => CHtml::encode($Image->height.' '.Yii::t('common', 'px')));
    $attributes[] = array('label' => $Image->getAttributeLabel('dpi'), 'value' => CHtml::encode($Image->dpi));
    $attributes[] = array('label' => $Image->getAttributeLabel('original'), 'value' => CHtml::encode($Image->original));
    $attributes[] = array('label' => $Image->getAttributeLabel('source'), 'value' => CHtml::encode($Image->source));
    $attributes[] = array('label' => $Image->getAttributeLabel('deepzoom'), 'value' => CHtml::encode($Image->deepzoom ? Yii::t('common', 'Да') : Yii::t('common', 'Нет')));
    $attributes[] = array('label' => $Image->getAttributeLabel('request'), 'value' => CHtml::encode($Image->request));
    $attributes[] = array('label' => $Image->getAttributeLabel('code'), 'value' => CHtml::encode($Image->code));
    $attributes[] = array('label' => $Image->getAttributeLabel('sort'), 'value' => CHtml::encode($Image->sort));

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



