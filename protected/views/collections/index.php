<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

    Yii::app()->clientScript->registerPackage('collections');
?>

<div class="row-fluid">
<?
    $this->widget(
        'bootstrap.widgets.TbThumbnails',
        array(
            'dataProvider' => $dataProvider,
            'template' => "{items}\n{pager}",
            'itemView' => '_viewThumbnailNoCheckbox',
            //'htmlOptions' => array('class' => 'collections_thumbnails')
        )
    );
 ?>
 </div>
