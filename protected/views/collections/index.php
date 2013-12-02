<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

    Yii::app()->clientScript->registerPackage('collections');
?>
<?php

$classThumbnails = (empty($_GET['v']) || $_GET['v'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['v']) && $_GET['v'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['v']) && $_GET['v'] == 'tb') ? 'selected' : '';

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => 'Отображение',
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => 'Картинками',
                'url' => $this->createUrl('collections/index', array('v' => 'th')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => 'Списком',
                'url' => $this->createUrl('collections/index', array('v' => 'ls')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => 'Таблицей',
                'url' => $this->createUrl('collections/index', array('v' => 'tb')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);
?>
<div class="gape"></div>
<div class="row-fluid">
<?
    $view = !empty($_GET['v']) ? $_GET['v'] : '';
    switch ($view) {
        case 'th':
            $this->widget(
                'bootstrap.widgets.TbThumbnails',
                array(
                    'dataProvider' => $dataProvider,
                    'template' => "{items}",
                    'itemView' => '_viewThumbnailNoCheckbox',
                    //'htmlOptions' => array('class' => 'collections_thumbnails')
                )
            );
            break;
        case 'ls':
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView'=>'_viewListCheckbox',
                'template' => '{items}',
                'emptyText' => ''
            ));
            break;
        case 'tb':
            $this->widget(
                'bootstrap.widgets.TbGridView',
                array(
                    'type' => 'striped bordered',
                    'dataProvider' => $dataProvider,
                    'template' => "{items}",
                    'columns' => array(
                        array(
                            'class' => 'CCheckBoxColumn',
                            'value' => 'return null;',
                            'checked' => 'return null;',
                            'selectableRows' => 2,
                        ),
                        array(
                            'class' => 'CLinkColumn',
                            'labelExpression' => '$data->name',
                            'urlExpression' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                            'header'=>'Название',
                        ),
                        array(
                            'header'=>'Действия',
                            'htmlOptions' => array('nowrap'=>'nowrap'),
                            'class'=>'bootstrap.widgets.TbButtonColumn',
                            'viewButtonUrl' => 'Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id));',
                            'updateButtonUrl'=> null,
                            'deleteButtonUrl' => null,
                            /*'buttons' => array(
                                'view' => array('visible' => 'return false;')
                            )*/
                        )
                    ),
                    'showTableOnEmpty' => false,
                    'emptyText' => ''
                )
            );
            break;
        default:
            $this->widget(
                'bootstrap.widgets.TbThumbnails',
                array(
                    'dataProvider' => $dataProvider,
                    'template' => "{items}\n{pager}",
                    'itemView' => '_viewThumbnailNoCheckbox',
                    //'htmlOptions' => array('class' => 'collections_thumbnails')
                )
            );
            break;
    }

 ?>
 </div>
