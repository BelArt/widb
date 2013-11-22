<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

    Yii::app()->clientScript->registerPackage('collections');

    $this->widget(
        'bootstrap.widgets.TbMenu',
        array(
            'type' => 'pills',
            'items' => array(
                array(
                    'label' => 'Создать коллекцию',
                    'url' => $this->createUrl('collections/create'),
                    'itemOptions' => array('class' => 'active small')
                ),

            )
        )
    );
?>

<div class="gape"></div>

<div class="row-fluid">
<?
    $this->widget(
        'bootstrap.widgets.TbThumbnails',
        array(
            'dataProvider' => $dataProvider,
            'template' => "{items}\n{pager}",
            'itemView' => '_viewThumbnailNoCheckbox',
        )
    );
 ?>
 </div>
