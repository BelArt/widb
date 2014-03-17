<?php
/* @var $this CollectionsController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerPackage('collections');
?>
<?php

$classThumbnails = (empty($_GET['cv']) || $_GET['cv'] == 'th') ? 'selected' : '';
$classList = (!empty($_GET['cv']) && $_GET['cv'] == 'ls') ? 'selected' : '';
$classTable = (!empty($_GET['cv']) && $_GET['cv'] == 'tb') ? 'selected' : '';

$this->widget(
    'bootstrap.widgets.TbMenu',
    array(
        'type' => 'pills',
        'items' => array(
            array(
                'label' => Yii::t('common', 'Отображение'),
                'itemOptions' => array('class' => 'nav-header')
            ),
            array(
                'label' => Yii::t('common', 'Картинками'),
                'url' => $this->createUrl('collections/index', array('cv' => 'th')),
                'itemOptions' => array('class' => 'small '.$classThumbnails)
            ),
            array(
                'label' => Yii::t('common', 'Списком'),
                'url' => $this->createUrl('collections/index', array('cv' => 'ls')),
                'itemOptions' => array('class' => 'small '.$classList)
            ),
            array(
                'label' => Yii::t('common', 'Таблицей'),
                'url' => $this->createUrl('collections/index', array('cv' => 'tb')),
                'itemOptions' => array('class' => 'small '.$classTable)
            ),
        )
    )
);
?>
<!--<div class="gape"></div>-->
<div class="row-fluid">
<?
    $view = !empty($_GET['cv']) ? $_GET['cv'] : '';
    switch ($view) {
        case 'th':
            $this->widget(
                'bootstrap.widgets.TbThumbnails',
                array(
                    'dataProvider' => $dataProvider,
                    'template' => '{items}'.PHP_EOL.'{pager}',
                    'itemView' => '_viewThumbnailNoCheckbox',
                    'ajaxUpdate' => false,
                    //'htmlOptions' => array('class' => 'collections_thumbnails')
                )
            );
            break;
        case 'ls':
            $this->widget('bootstrap.widgets.TbListView', array(
                'dataProvider' => $dataProvider,
                'itemView'=>'_viewListNoCheckbox',
                'template' => '{items}'.PHP_EOL.'{pager}',
                'ajaxUpdate' => false,
                'emptyText' => ''
            ));
            break;
        case 'tb':
            $this->widget(
                'bootstrap.widgets.TbGridView',
                array(
                    'type' => 'striped bordered',
                    'dataProvider' => $dataProvider,
                    'template' => "{items}\n{pager}",
                    'ajaxUpdate' => false,
                    'columns' => array(
                        /*array(
                            'class' => 'CCheckBoxColumn',
                            'value' => 'return null;',
                            'checked' => 'return null;',
                            'selectableRows' => 2,
                        ),*/
                        array(
                            'class' => 'CLinkColumn',
                            'labelExpression' => '$data->name',
                            'urlExpression' => '$data->temporary ? Yii::app()->urlManager->createUrl("collections/viewTemp", array("id" => $data->id)) : Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                            'header'=>'Название',
                            'headerHtmlOptions' => array(
                                'class' => 'valignedMiddle halignedCenter'
                            ),
                        ),
                        /*array(
                            'header'=>'Действия',
                            'htmlOptions' => array('nowrap'=>'nowrap'),
                            'class'=>'bootstrap.widgets.TbButtonColumn',
                            'viewButtonUrl' => '$data->temporary ? Yii::app()->urlManager->createUrl("collections/viewTemp", array("id" => $data->id)) : Yii::app()->urlManager->createUrl("collections/view", array("id" => $data->id))',
                            'updateButtonUrl'=> null,
                            'deleteButtonUrl' => null,
                        )*/
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
