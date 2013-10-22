<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerPackage('collections');
?>

<?php
    echo CHtml::openTag('div', array('class' => 'row-fluid'));
    $this->widget(
        'bootstrap.widgets.TbThumbnails',
        array(
            'dataProvider' => $dataProvider,
            'template' => "{items}\n{pager}",
            'itemView' => '_view',
        )
    );
    echo CHtml::closeTag('div');
 ?>
