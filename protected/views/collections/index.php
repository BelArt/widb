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
                    'label' => 'Добавить коллекцию',
                    'url' => '#',
                    'itemOptions' => array('class' => 'active')
                ),

            )
        )
    );

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
